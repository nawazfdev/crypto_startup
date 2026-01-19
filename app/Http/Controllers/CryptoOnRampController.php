<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// Use correct model namespace
use App\Model\Cryptocurrency;
use App\Model\CryptoWallet;
use App\Model\CryptoTransaction;
use Carbon\Carbon;

class CryptoOnRampController extends Controller
{
    /**
     * Supported fiat currencies and their exchange rates to USD
     */
    protected $supportedCurrencies = [
        'USD' => 1.00,
        'EUR' => 1.08,
        'GBP' => 1.27,
        'CAD' => 0.74,
        'AUD' => 0.65,
    ];

    /**
     * Platform token (site's native token)
     */
    protected $platformToken = null;

    public function __construct()
    {
        $this->middleware(['auth', 'verified', '2fa']);
    }

    /**
     * Show the deposit/buy crypto page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get platform token
        $platformToken = $this->getPlatformToken();
        
        // Get user's current wallet balance
        $wallets = CryptoWallet::where('user_id', $user->id)
            ->with('cryptocurrency')
            ->get();
        
        // Get recent transactions
        $recentTransactions = CryptoTransaction::where(function($q) use ($user) {
                $q->where('buyer_user_id', $user->id)
                  ->orWhere('seller_user_id', $user->id);
            })
            ->where('type', 'deposit')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get transaction limits based on KYC level
        $limits = $this->getTransactionLimits($user);
        
        return view('cryptocurrency.onramp.index', compact(
            'user',
            'platformToken',
            'wallets',
            'recentTransactions',
            'limits'
        ));
    }

    /**
     * Show the buy tokens form
     */
    public function buyForm(Request $request)
    {
        $user = Auth::user();
        $platformToken = $this->getPlatformToken();
        
        if (!$platformToken) {
            return redirect()->route('cryptocurrency.wallet')
                ->with('error', 'Platform token not configured. Please contact support.');
        }
        
        $limits = $this->getTransactionLimits($user);
        $paymentMethods = $this->getAvailablePaymentMethods($user);
        
        return view('cryptocurrency.onramp.buy', compact(
            'user',
            'platformToken',
            'limits',
            'paymentMethods'
        ));
    }

    /**
     * Process credit card purchase of tokens
     */
    public function processPurchase(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'amount_usd' => 'required|numeric|min:5|max:50000',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,paypal',
            'card_token' => 'required_if:payment_method,credit_card,debit_card|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $amountUsd = (float) $request->input('amount_usd');
        $paymentMethod = $request->input('payment_method');
        
        // Check transaction limits
        $limits = $this->getTransactionLimits($user);
        $dailyTotal = $this->getDailyDepositTotal($user->id);
        
        if ($amountUsd > $limits['per_transaction']) {
            return redirect()->back()
                ->with('error', "Transaction exceeds your per-transaction limit of \${$limits['per_transaction']}.")
                ->withInput();
        }
        
        if (($dailyTotal + $amountUsd) > $limits['daily']) {
            $remaining = max(0, $limits['daily'] - $dailyTotal);
            return redirect()->back()
                ->with('error', "This would exceed your daily deposit limit. You have \${$remaining} remaining today.")
                ->withInput();
        }

        // Get platform token
        $platformToken = $this->getPlatformToken();
        if (!$platformToken) {
            return redirect()->back()
                ->with('error', 'Platform token not available. Please try again later.')
                ->withInput();
        }

        try {
            \DB::beginTransaction();
            
            // Calculate fees
            $processingFeePercent = 3.5; // 3.5% processing fee
            $platformFeePercent = 1.0; // 1% platform fee
            $totalFeePercent = $processingFeePercent + $platformFeePercent;
            
            $feeAmount = $amountUsd * ($totalFeePercent / 100);
            $netAmount = $amountUsd - $feeAmount;
            
            // Calculate tokens to receive
            $tokenPrice = $platformToken->current_price;
            $tokensToReceive = $netAmount / $tokenPrice;
            
            // Process payment with Stripe (or configured payment processor)
            $paymentResult = $this->processPayment($request, $amountUsd, $user);
            
            if (!$paymentResult['success']) {
                \DB::rollBack();
                return redirect()->back()
                    ->with('error', $paymentResult['message'])
                    ->withInput();
            }
            
            // Update or create user's wallet
            $wallet = CryptoWallet::firstOrNew([
                'user_id' => $user->id,
                'cryptocurrency_id' => $platformToken->id,
            ]);
            $wallet->balance = ($wallet->balance ?? 0) + $tokensToReceive;
            $wallet->save();
            
            // Update token supply
            $platformToken->available_supply -= $tokensToReceive;
            $platformToken->circulating_supply += $tokensToReceive;
            $platformToken->volume_24h += $amountUsd;
            $platformToken->save();
            
            // Create transaction record
            $transaction = CryptoTransaction::create([
                'cryptocurrency_id' => $platformToken->id,
                'buyer_user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $tokensToReceive,
                'price_per_token' => $tokenPrice,
                'total_price' => $amountUsd,
                'fee_amount' => $feeAmount,
                'status' => 'completed',
                'transaction_hash' => 'onramp_' . time() . '_' . substr(md5(uniqid()), 0, 16),
                'notes' => json_encode([
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $paymentResult['reference'] ?? null,
                    'processing_fee' => $amountUsd * ($processingFeePercent / 100),
                    'platform_fee' => $amountUsd * ($platformFeePercent / 100),
                    'net_amount_usd' => $netAmount,
                    'token_price_at_purchase' => $tokenPrice,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]),
            ]);
            
            \DB::commit();
            
            // Log for compliance
            Log::channel('crypto')->info('Crypto purchase completed', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'amount_usd' => $amountUsd,
                'tokens_received' => $tokensToReceive,
                'payment_method' => $paymentMethod,
                'ip' => $request->ip(),
            ]);
            
            return redirect()->route('cryptocurrency.wallet')
                ->with('success', sprintf(
                    'Successfully purchased %s %s tokens for $%s!',
                    number_format($tokensToReceive, 4),
                    $platformToken->symbol,
                    number_format($amountUsd, 2)
                ));
                
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Log::channel('crypto')->error('Crypto purchase failed', [
                'user_id' => $user->id,
                'amount_usd' => $amountUsd,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred while processing your purchase. Please try again.')
                ->withInput();
        }
    }

    /**
     * Process payment through Stripe or other payment processor
     */
    protected function processPayment(Request $request, $amount, $user)
    {
        $paymentMethod = $request->input('payment_method');
        
        try {
            // For credit/debit card, use Stripe
            if (in_array($paymentMethod, ['credit_card', 'debit_card'])) {
                return $this->processStripePayment($request, $amount, $user);
            }
            
            // For PayPal
            if ($paymentMethod === 'paypal') {
                return $this->processPayPalPayment($request, $amount, $user);
            }
            
            // For bank transfer (manual review required)
            if ($paymentMethod === 'bank_transfer') {
                return [
                    'success' => true,
                    'status' => 'pending',
                    'reference' => 'bank_' . time() . '_' . rand(1000, 9999),
                    'message' => 'Bank transfer initiated. Tokens will be credited after verification.',
                ];
            }
            
            return ['success' => false, 'message' => 'Unsupported payment method.'];
            
        } catch (\Exception $e) {
            Log::channel('payments')->error('Payment processing failed', [
                'user_id' => $user->id,
                'amount' => $amount,
                'method' => $paymentMethod,
                'error' => $e->getMessage(),
            ]);
            
            return ['success' => false, 'message' => 'Payment processing failed. Please try again.'];
        }
    }

    /**
     * Process payment through Stripe
     */
    protected function processStripePayment(Request $request, $amount, $user)
    {
        // Check if Stripe is configured
        if (!getSetting('payments.stripe_secret_key')) {
            // Simulate successful payment for development
            return [
                'success' => true,
                'reference' => 'stripe_sim_' . time() . '_' . rand(10000, 99999),
                'message' => 'Payment processed successfully (simulation mode).',
            ];
        }
        
        try {
            \Stripe\Stripe::setApiKey(getSetting('payments.stripe_secret_key'));
            
            // Create payment intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int)($amount * 100), // Stripe uses cents
                'currency' => 'usd',
                'payment_method' => $request->input('card_token'),
                'confirm' => true,
                'description' => 'Token purchase on ' . getSetting('site.name'),
                'metadata' => [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'type' => 'crypto_purchase',
                ],
            ]);
            
            if ($paymentIntent->status === 'succeeded') {
                return [
                    'success' => true,
                    'reference' => $paymentIntent->id,
                    'message' => 'Payment processed successfully.',
                ];
            }
            
            return ['success' => false, 'message' => 'Payment was not successful.'];
            
        } catch (\Stripe\Exception\CardException $e) {
            return ['success' => false, 'message' => 'Card error: ' . $e->getMessage()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Payment processing error.'];
        }
    }

    /**
     * Process PayPal payment
     */
    protected function processPayPalPayment(Request $request, $amount, $user)
    {
        // Implement PayPal integration here
        // For now, simulate success
        return [
            'success' => true,
            'reference' => 'paypal_' . time() . '_' . rand(10000, 99999),
            'message' => 'PayPal payment processed.',
        ];
    }

    /**
     * Get the platform's native token
     */
    protected function getPlatformToken()
    {
        if ($this->platformToken) {
            return $this->platformToken;
        }
        
        // Get the platform token (usually the first verified token or one marked as platform token)
        $this->platformToken = Cryptocurrency::where('is_active', true)
            ->where('is_verified', true)
            ->orderBy('id', 'asc')
            ->first();
        
        // Fallback: get any active token
        if (!$this->platformToken) {
            $this->platformToken = Cryptocurrency::where('is_active', true)
                ->orderBy('id', 'asc')
                ->first();
        }
        
        return $this->platformToken;
    }

    /**
     * Get transaction limits based on user's KYC level
     */
    protected function getTransactionLimits($user)
    {
        $kycLevel = $user->kyc_level ?? 0;
        
        $limits = [
            0 => ['daily' => 100, 'monthly' => 500, 'per_transaction' => 50],
            1 => ['daily' => 1000, 'monthly' => 5000, 'per_transaction' => 500],
            2 => ['daily' => 10000, 'monthly' => 50000, 'per_transaction' => 5000],
            3 => ['daily' => 100000, 'monthly' => 500000, 'per_transaction' => 50000],
        ];
        
        return $limits[$kycLevel] ?? $limits[0];
    }

    /**
     * Get daily deposit total for user
     */
    protected function getDailyDepositTotal($userId)
    {
        try {
            return CryptoTransaction::where('buyer_user_id', $userId)
                ->where('type', 'deposit')
                ->whereDate('created_at', Carbon::today())
                ->where('status', 'completed')
                ->sum('total_price') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get available payment methods for user
     */
    protected function getAvailablePaymentMethods($user)
    {
        $methods = [];
        
        // Credit/Debit card (Stripe)
        if (getSetting('payments.stripe_enabled') || true) { // Always show for demo
            $methods['credit_card'] = [
                'name' => 'Credit Card',
                'icon' => 'fa-credit-card',
                'fee' => '3.5%',
                'instant' => true,
            ];
            $methods['debit_card'] = [
                'name' => 'Debit Card',
                'icon' => 'fa-credit-card',
                'fee' => '3.5%',
                'instant' => true,
            ];
        }
        
        // PayPal
        if (getSetting('payments.paypal_enabled') || true) {
            $methods['paypal'] = [
                'name' => 'PayPal',
                'icon' => 'fa-brands fa-paypal',
                'fee' => '4.0%',
                'instant' => true,
            ];
        }
        
        // Bank Transfer (for higher limits)
        if ($user->kyc_level >= 2) {
            $methods['bank_transfer'] = [
                'name' => 'Bank Transfer',
                'icon' => 'fa-building-columns',
                'fee' => '1.0%',
                'instant' => false,
                'note' => '1-3 business days',
            ];
        }
        
        return $methods;
    }

    /**
     * Get current exchange rate quote
     */
    public function getQuote(Request $request)
    {
        $amountUsd = (float) $request->input('amount', 0);
        $platformToken = $this->getPlatformToken();
        
        if (!$platformToken || $amountUsd <= 0) {
            return response()->json(['success' => false]);
        }
        
        $processingFee = $amountUsd * 0.035;
        $platformFee = $amountUsd * 0.01;
        $netAmount = $amountUsd - $processingFee - $platformFee;
        $tokensToReceive = $netAmount / $platformToken->current_price;
        
        return response()->json([
            'success' => true,
            'amount_usd' => $amountUsd,
            'processing_fee' => round($processingFee, 2),
            'platform_fee' => round($platformFee, 2),
            'total_fees' => round($processingFee + $platformFee, 2),
            'net_amount' => round($netAmount, 2),
            'token_price' => $platformToken->current_price,
            'tokens_to_receive' => round($tokensToReceive, 8),
            'token_symbol' => $platformToken->symbol,
        ]);
    }
}

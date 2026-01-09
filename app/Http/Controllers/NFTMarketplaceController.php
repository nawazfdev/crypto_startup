<?php

namespace App\Http\Controllers;

use App\Models\NFT;
use App\Models\NFTListing;
use App\Models\NFTTransaction;
use App\Services\Web3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NFTMarketplaceController extends Controller
{
    protected $web3Service;

    public function __construct(Web3Service $web3Service)
    {
        $this->web3Service = $web3Service;
    }

    /**
     * Display the NFT marketplace
     */
    public function index()
    {
        $listings = NFTListing::where('status', 'active')
            ->with(['nft', 'seller'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('nft.marketplace', compact('listings'));
    }

    /**
     * Show form to create a new NFT
     */
    public function create()
    {
        return view('nft.create');
    }

    /**
     * Store a new NFT (mint)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'price' => 'required|numeric|min:0.0001',
            'wallet_address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Upload image
            $imagePath = $request->file('image')->store('nfts', 'public');
            $imageUrl = Storage::url($imagePath);

            // Create token URI (in production, upload to IPFS)
            $tokenURI = url('/storage/' . $imagePath);

            // Get listing price
            $listingPrice = $this->web3Service->getListingPrice();
            $totalPrice = $request->price + $listingPrice;

            // Create NFT on blockchain (this would be done via frontend with MetaMask)
            // For now, we'll create the database record
            $nft = NFT::create([
                'user_id' => Auth::id(),
                'token_id' => '0', // Will be updated after blockchain transaction
                'name' => $request->name,
                'description' => $request->description,
                'token_uri' => $tokenURI,
                'image_url' => $imageUrl,
                'status' => 'minted',
                'metadata' => [
                    'price' => $request->price,
                    'listing_price' => $listingPrice,
                ],
            ]);

            // Create listing
            $listing = NFTListing::create([
                'nft_id' => $nft->id,
                'seller_id' => Auth::id(),
                'token_id' => '0',
                'price' => $request->price,
                'listing_price' => $listingPrice,
                'status' => 'active',
            ]);

            return redirect()->route('nft.marketplace')
                ->with('success', 'NFT created successfully! Please complete the blockchain transaction.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create NFT: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show a specific NFT
     */
    public function show($id)
    {
        $nft = NFT::with(['user', 'activeListing', 'transactions'])->findOrFail($id);
        $listing = $nft->activeListing;

        return view('nft.show', compact('nft', 'listing'));
    }

    /**
     * Buy an NFT
     */
    public function buy(Request $request, $id)
    {
        $listing = NFTListing::where('id', $id)
            ->where('status', 'active')
            ->with('nft')
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'wallet_address' => 'required|string',
            'transaction_hash' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            // Verify transaction on blockchain
            $receipt = $this->web3Service->getTransactionReceipt($request->transaction_hash);

            if (!$receipt) {
                return back()->with('error', 'Transaction not found or not confirmed yet.');
            }

            // Update listing
            $listing->update([
                'status' => 'sold',
                'sold_at' => now(),
                'transaction_hash' => $request->transaction_hash,
            ]);

            // Update NFT
            $listing->nft->update([
                'status' => 'sold',
                'user_id' => Auth::id(), // New owner
            ]);

            // Create transaction record
            NFTTransaction::create([
                'nft_id' => $listing->nft_id,
                'listing_id' => $listing->id,
                'seller_id' => $listing->seller_id,
                'buyer_id' => Auth::id(),
                'token_id' => $listing->token_id,
                'type' => 'sale',
                'price' => $listing->price,
                'fee' => $listing->listing_price,
                'transaction_hash' => $request->transaction_hash,
                'from_address' => $request->wallet_address,
                'to_address' => config('web3.contract_address'),
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return redirect()->route('nft.show', $listing->nft_id)
                ->with('success', 'NFT purchased successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to complete purchase: ' . $e->getMessage());
        }
    }

    /**
     * Show user's NFTs
     */
    public function myNFTs()
    {
        $nfts = NFT::where('user_id', Auth::id())
            ->with(['activeListing', 'transactions'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('nft.my-nfts', compact('nfts'));
    }

    /**
     * Show user's listings
     */
    public function myListings()
    {
        $listings = NFTListing::where('seller_id', Auth::id())
            ->with(['nft'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('nft.my-listings', compact('listings'));
    }

    /**
     * Resell an NFT
     */
    public function resell(Request $request, $id)
    {
        $nft = NFT::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:0.0001',
            'wallet_address' => 'required|string',
            'transaction_hash' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $listingPrice = $this->web3Service->getListingPrice();

            // Create new listing
            $listing = NFTListing::create([
                'nft_id' => $nft->id,
                'seller_id' => Auth::id(),
                'token_id' => $nft->token_id,
                'price' => $request->price,
                'listing_price' => $listingPrice,
                'status' => 'active',
                'transaction_hash' => $request->transaction_hash,
            ]);

            // Update NFT status
            $nft->update(['status' => 'listed']);

            // Create transaction record
            NFTTransaction::create([
                'nft_id' => $nft->id,
                'listing_id' => $listing->id,
                'seller_id' => Auth::id(),
                'token_id' => $nft->token_id,
                'type' => 'resale',
                'price' => $request->price,
                'fee' => $listingPrice,
                'transaction_hash' => $request->transaction_hash,
                'from_address' => $request->wallet_address,
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return redirect()->route('nft.my-listings')
                ->with('success', 'NFT listed for sale successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to list NFT: ' . $e->getMessage());
        }
    }

    /**
     * API: Get contract ABI
     */
    public function getContractAbi()
    {
        $abiPath = base_path('contracts/artifacts/contracts/NFTMarketplace.sol/NFTMarketplace.json');
        
        if (file_exists($abiPath)) {
            $artifact = json_decode(file_get_contents($abiPath), true);
            return response()->json([
                'abi' => $artifact['abi'] ?? [],
                'contract_address' => config('web3.contract_address'),
            ]);
        }

        return response()->json(['error' => 'Contract ABI not found'], 404);
    }

    /**
     * API: Get listing price
     */
    public function getListingPrice()
    {
        $price = $this->web3Service->getListingPrice();
        return response()->json(['listing_price' => $price]);
    }
}


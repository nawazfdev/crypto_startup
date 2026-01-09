@extends('layouts.generic')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4">My Wallet</h1>
            <p class="lead">Manage your cryptocurrency tokens and transactions</p>
        </div>
    </div>
    
    <!-- Wallet Balance -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Total Balance</h5>
                </div>
                <div class="card-body">
                    <h2 class="mb-3">${{ number_format($totalBalance, 2) }}</h2>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Available for trading:</span>
                        <span>${{ number_format($availableBalance, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pending transactions:</span>
                        <span>${{ number_format($pendingBalance, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex mt-3">
                        <a href="{{ route('cryptocurrency.deposit') }}" class="btn btn-success flex-grow-1 mr-2">DEPOSIT</a>
                        <a href="{{ route('cryptocurrency.withdraw') }}" class="btn btn-outline-primary flex-grow-1">WITHDRAW</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="{{ route('cryptocurrency.marketplace') }}" class="btn btn-light btn-block py-3 action-button">
                                <i class="fas fa-store mb-2 d-block" style="font-size: 24px;"></i>
                                MARKETPLACE
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('cryptocurrency.explorer') }}" class="btn btn-light btn-block py-3 action-button">
                                <i class="fas fa-search mb-2 d-block" style="font-size: 24px;"></i>
                                EXPLORE
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('cryptocurrency.create') }}" class="btn btn-light btn-block py-3 action-button">
                                <i class="fas fa-plus-circle mb-2 d-block" style="font-size: 24px;"></i>
                                CREATE TOKEN
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#transaction-history" class="btn btn-light btn-block py-3 action-button">
                                <i class="fas fa-history mb-2 d-block" style="font-size: 24px;"></i>
                                HISTORY
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- My Tokens -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">My Tokens</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Token</th>
                                    <th>Balance</th>
                                    <th>Value</th>
                                    <th>Price</th>
                                    <th>24h Change</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $wallet)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($wallet->cryptocurrency->logo)
                                                <img src="{{ asset('storage/' . $wallet->cryptocurrency->logo) }}" alt="{{ $wallet->cryptocurrency->name }}" class="rounded-circle mr-2" width="32" height="32">
                                            @else
                                                <div class="rounded-circle bg-primary text-white mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <span>{{ substr($wallet->cryptocurrency->symbol, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div>{{ $wallet->cryptocurrency->name }}</div>
                                                <small class="text-muted">{{ $wallet->cryptocurrency->symbol }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($wallet->balance) }}</td>
                                    <td>${{ number_format($wallet->balance * $wallet->cryptocurrency->current_price, 2) }}</td>
                                    <td>${{ number_format($wallet->cryptocurrency->current_price, 8) }}</td>
                                    <td>
                                        @if($wallet->cryptocurrency->price_change_24h > 0)
                                            <span class="text-success">+{{ number_format($wallet->cryptocurrency->price_change_24h, 2) }}%</span>
                                        @elseif($wallet->cryptocurrency->price_change_24h < 0)
                                            <span class="text-danger">{{ number_format($wallet->cryptocurrency->price_change_24h, 2) }}%</span>
                                        @else
                                            <span>0.00%</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('cryptocurrency.show', $wallet->cryptocurrency->id) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                            <a href="{{ route('cryptocurrency.buy.form', $wallet->cryptocurrency->id) }}" class="btn btn-sm btn-primary">Buy</a>
                                            <a href="{{ route('cryptocurrency.sell', $wallet->cryptocurrency->id) }}" class="btn btn-sm btn-outline-secondary">Sell</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="mb-2">You don't own any tokens yet.</p>
                                        <a href="{{ route('cryptocurrency.marketplace') }}" class="btn btn-primary">Browse Marketplace</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="row" id="transaction-history">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transaction History</h5>
                    <div class="btn-group">
                        <a href="{{ route('cryptocurrency.transactions', ['type' => 'all']) }}" class="btn btn-sm btn-outline-secondary active">All</a>
                        <a href="{{ route('cryptocurrency.transactions', ['type' => 'buy']) }}" class="btn btn-sm btn-outline-secondary">Buy</a>
                        <a href="{{ route('cryptocurrency.transactions', ['type' => 'sell']) }}" class="btn btn-sm btn-outline-secondary">Sell</a>
                        <a href="{{ route('cryptocurrency.transactions', ['type' => 'transfer']) }}" class="btn btn-sm btn-outline-secondary">Transfer</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Token</th>
                                    <th>Amount</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($transaction->type == 'buy')
                                            <span class="badge badge-success">Buy</span>
                                        @elseif($transaction->type == 'sell')
                                            <span class="badge badge-danger">Sell</span>
                                        @elseif($transaction->type == 'transfer')
                                            <span class="badge badge-info">Transfer</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($transaction->type) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->cryptocurrency->symbol }}</td>
                                    <td>{{ number_format($transaction->amount) }}</td>
                                    <td>${{ number_format($transaction->price_per_token, 8) }}</td>
                                    <td>${{ number_format($transaction->total_amount, 2) }}</td>
                                    <td>
                                        @if($transaction->status == 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif($transaction->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($transaction->status == 'failed')
                                            <span class="badge badge-danger">Failed</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No transactions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .action-button {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        font-weight: 500;
    }
    
    .action-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background-color: #f8f9fa;
    }
    
    .btn-group .btn {
        min-width: 70px;
    }
</style>
@endpush
@endsection 
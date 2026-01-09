@extends('layouts.generic')

@section('content')
<div class="container py-4">
    <!-- Token Header - MOVED TO TOP -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="token-logo">
                                @if($cryptocurrency->logo && Storage::disk('public')->exists($cryptocurrency->logo))
                                    <img src="{{ asset('storage/' . $cryptocurrency->logo) }}" 
                                         alt="{{ $cryptocurrency->name }} Logo" 
                                         class="rounded-circle token-logo-img" 
                                         width="80" height="80">
                                @else
                                    <div class="token-logo-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; font-size: 2rem; font-weight: bold;">
                                        {{ substr($cryptocurrency->symbol, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <h1 class="h2 mb-1">{{ $cryptocurrency->name }} 
                                <span class="badge bg-secondary fs-6">{{ $cryptocurrency->symbol }}</span>
                            </h1>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="h4 mb-0 fw-bold">${{ number_format($cryptocurrency->current_price, 8) }}</span>
                                @php
                                    $change = $cryptocurrency->change_24h ?? 0;
                                @endphp
                                <span class="badge fs-6 {{ $change >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                                </span>
                                @if($cryptocurrency->is_verified)
                                    <span class="badge bg-primary fs-6">✓ Verified</span>
                                @endif
                            </div>
                            <div class="d-flex gap-3 flex-wrap align-items-center">
                                <a href="{{ route('cryptocurrency.buy.form', $cryptocurrency->id) }}" 
                                   class="btn btn-success px-4 py-2 fw-bold">
                                    <i class="fas fa-arrow-up me-2"></i>BUY
                                </a>
                                @if($wallet && $wallet->balance > 0)
                                    <a href="{{ route('cryptocurrency.sell.form', $cryptocurrency->id) }}" 
                                       class="btn btn-danger px-4 py-2 fw-bold">
                                        <i class="fas fa-arrow-down me-2"></i>SELL
                                    </a>
                                @endif
                                <!-- <button class="btn btn-outline-secondary px-3 py-2" onclick="toggleWatchlist()">
                                    <i class="fas fa-star me-2"></i>WATCHLIST
                                </button>
                                @if(Auth::id() == $cryptocurrency->creator_user_id)
                                    <a href="#" class="btn btn-outline-warning px-3 py-2">
                                        <i class="fas fa-edit me-2"></i>EDIT
                                    </a>
                                @endif -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your Wallet (if user has balance) - MOVED UP -->
    @if($wallet && $wallet->balance > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm wallet-gradient">
                <div class="card-header border-0 text-white wallet-header">
                    <h5 class="mb-0">🏦 Your Wallet</h5>
                </div>
                <div class="card-body text-white">
                    <div class="row g-3 text-center">
                        <div class="col-md-4">
                            <div class="p-3 wallet-stat-card rounded">
                                <h4 class="mb-1 fw-bold text-white">{{ number_format($wallet->balance, 8) }}</h4>
                                <small class="wallet-label">{{ $cryptocurrency->symbol }} Balance</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 wallet-stat-card rounded">
                                <h4 class="mb-1 fw-bold text-white">${{ number_format($wallet->balance * $cryptocurrency->current_price, 2) }}</h4>
                                <small class="wallet-label">USD Value</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 wallet-stat-card rounded">
                                <h4 class="mb-1 fw-bold text-white">{{ number_format(($wallet->balance / $cryptocurrency->total_supply) * 100, 2) }}%</h4>
                                <small class="wallet-label">Of Total Supply</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Market Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3 text-muted">Market Stats</h3>
            <div class="row g-3">
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h6 class="card-title text-muted small mb-2">Market Cap</h6>
                            <p class="card-text h5 mb-0 fw-bold">${{ number_format($cryptocurrency->market_cap, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h6 class="card-title text-muted small mb-2">24h Volume</h6>
                            <p class="card-text h5 mb-0 fw-bold">${{ number_format($cryptocurrency->volume_24h, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h6 class="card-title text-muted small mb-2">24h Transactions</h6>
                            <p class="card-text h5 mb-0 fw-bold">{{ $cryptocurrency->transactions()->whereDate('created_at', today())->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h6 class="card-title text-muted small mb-2">Total Supply</h6>
                            <p class="card-text h6 mb-0 fw-bold">{{ number_format($cryptocurrency->total_supply, 0) }}</p>
                            <small class="text-muted">{{ $cryptocurrency->symbol }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h6 class="card-title text-muted small mb-2">Available</h6>
                            <p class="card-text h6 mb-0 fw-bold">{{ number_format($cryptocurrency->available_supply, 0) }}</p>
                            <small class="text-muted">{{ $cryptocurrency->symbol }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h6 class="card-title text-muted small mb-2">Circulating</h6>
                            <p class="card-text h6 mb-0 fw-bold">{{ number_format($cryptocurrency->circulating_supply, 0) }}</p>
                            <small class="text-muted">{{ $cryptocurrency->symbol }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Details & Fee Structure -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 text-muted">Token Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Network:</span>
                                <div class="fw-bold">{{ ucfirst($cryptocurrency->blockchain_network) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Type:</span>
                                <div class="fw-bold">{{ ucfirst($cryptocurrency->token_type) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Initial Price:</span>
                                <div class="fw-bold">${{ number_format($cryptocurrency->initial_price, 8) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Created:</span>
                                <div class="fw-bold">{{ $cryptocurrency->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-item">
                                <span class="text-muted small">Features:</span>
                                <div class="mt-1">
                                    @if($cryptocurrency->transferable)
                                        <span class="badge bg-primary rounded-pill me-1">Transferable</span>
                                    @endif
                                    @if($cryptocurrency->enable_burning)
                                        <span class="badge bg-warning rounded-pill me-1">Burnable</span>
                                    @endif
                                    @if($cryptocurrency->enable_minting)
                                        <span class="badge bg-info rounded-pill me-1">Mintable</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 text-muted">Fee Structure</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Creator Fee:</span>
                                <div class="fw-bold">{{ number_format($cryptocurrency->creator_fee_percentage, 2) }}%</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Platform Fee:</span>
                                <div class="fw-bold">{{ number_format($cryptocurrency->platform_fee_percentage, 2) }}%</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Liquidity Pool:</span>
                                <div class="fw-bold">{{ number_format($cryptocurrency->liquidity_pool_percentage, 2) }}%</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <span class="text-muted small">Creator:</span>
                                <div class="fw-bold">{{ $cryptocurrency->creator->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                        @if($cryptocurrency->contract_address)
                        <div class="col-12">
                            <div class="detail-item">
                                <span class="text-muted small">Contract:</span>
                                <div class="fw-bold">
                                    <code class="bg-light p-1 rounded small">{{ substr($cryptocurrency->contract_address, 0, 8) }}...{{ substr($cryptocurrency->contract_address, -6) }}</code>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 text-muted">About {{ $cryptocurrency->name }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text mb-3">{{ $cryptocurrency->description }}</p>
                    
                    @if($cryptocurrency->website || $cryptocurrency->whitepaper)
                        <div class="d-flex gap-2 flex-wrap">
                            @if($cryptocurrency->website)
                                <a href="{{ $cryptocurrency->website }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-globe me-2"></i>Website
                                </a>
                            @endif
                            @if($cryptocurrency->whitepaper)
                                <a href="{{ $cryptocurrency->whitepaper }}" target="_blank" class="btn btn-outline-secondary">
                                    <i class="fas fa-file-pdf me-2"></i>Whitepaper
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-muted">Recent Transactions</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshTransactions()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 text-muted small">Type</th>
                                    <th class="border-0 text-muted small">Amount</th>
                                    <th class="border-0 text-muted small">Price</th>
                                    <th class="border-0 text-muted small">Total</th>
                                    <th class="border-0 text-muted small">Date</th>
                                </tr>
                            </thead>
                            <tbody id="transactions-table">
                                @forelse($cryptocurrency->transactions()->latest()->take(10)->get() as $transaction)
                                    <tr>
                                        <td>
                                            <span class="badge rounded-pill {{ $transaction->type == 'buy' ? 'bg-success' : ($transaction->type == 'sell' ? 'bg-danger' : 'bg-secondary') }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ number_format($transaction->amount, 8) }} {{ $cryptocurrency->symbol }}</td>
                                        <td>${{ number_format($transaction->price_per_token, 8) }}</td>
                                        <td class="fw-bold">${{ number_format($transaction->total_price, 2) }}</td>
                                        <td class="text-muted">{{ $transaction->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <div class="opacity-50">
                                                <i class="fas fa-chart-line fa-2x mb-3"></i>
                                                <h6>No transactions yet</h6>
                                                <small>Be the first to trade this token!</small>
                                            </div>
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
</div>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --wallet-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
}

.card {
    border: none !important;
    border-radius: 16px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important;
}

/* FIXED: Logo styling */
.token-logo-img {
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.token-logo-placeholder {
    border: 3px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* FIXED: Wallet section styling */
.wallet-gradient {
    background: var(--wallet-gradient) !important;
}

.wallet-header {
    background: transparent !important;
}

.wallet-stat-card {
    background: rgba(255, 255, 255, 0.2) !important;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.wallet-label {
    color: rgba(255, 255, 255, 0.8) !important;
    font-weight: 500;
}

.badge {
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn {
    border-radius: 12px !important;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
}

/* Button Alignment Fix */
.d-flex.gap-3 {
    gap: 1rem !important;
}

.d-flex.gap-3 .btn {
    min-width: 120px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

code {
    background-color: #f8f9fa;
    padding: 4px 8px;
    border-radius: 6px;
    font-family: 'Monaco', 'Consolas', monospace;
    font-size: 0.85em;
}

.detail-item {
    padding: 8px 0;
}

.detail-item .text-muted {
    display: block;
    margin-bottom: 4px;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-header {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.8rem;
}

.opacity-75 {
    opacity: 0.75;
}

.opacity-50 {
    opacity: 0.5;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .d-flex.gap-3 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .d-flex.gap-3 .btn {
        width: 100%;
        min-width: unset;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .h4 {
        font-size: 1.5rem;
    }
    
    .token-logo img,
    .token-logo div {
        width: 60px !important;
        height: 60px !important;
        font-size: 1.5rem !important;
    }
}

/* Smooth animations */
* {
    transition: all 0.3s ease;
}

/* Custom scrollbar for table */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-sync-alt.spinning {
    animation: spin 1s linear infinite;
}

/* Ensure text is visible on all backgrounds */
.text-white {
    color: #ffffff !important;
}

.wallet-stat-card h4 {
    color: #ffffff !important;
}
</style>

<script>
function toggleWatchlist() {
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    const isWatchlisted = btn.classList.contains('watchlisted');
    
    if (isWatchlisted) {
        btn.innerHTML = '<i class="fas fa-star me-2"></i>WATCHLIST';
        btn.classList.remove('watchlisted', 'btn-warning');
        btn.classList.add('btn-outline-secondary');
        showNotification('Removed from watchlist', 'info');
    } else {
        btn.innerHTML = '<i class="fas fa-star me-2"></i>WATCHING';
        btn.classList.add('watchlisted', 'btn-warning');
        btn.classList.remove('btn-outline-secondary');
        showNotification('Added to watchlist', 'success');
    }
    
    // Here you would typically make an AJAX call to update the backend
    // fetch('/api/watchlist/toggle', { method: 'POST', ... })
}

function refreshTransactions() {
    const refreshBtn = document.querySelector('[onclick="refreshTransactions()"] i');
    refreshBtn.classList.add('spinning');
    
    // Simulate refresh - replace with actual AJAX call
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Update transactions table
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTransactionsTable = doc.querySelector('#transactions-table');
        if (newTransactionsTable) {
            document.querySelector('#transactions-table').innerHTML = newTransactionsTable.innerHTML;
        }
        
        refreshBtn.classList.remove('spinning');
        showNotification('Transactions refreshed', 'success');
    })
    .catch(error => {
        refreshBtn.classList.remove('spinning');
        showNotification('Failed to refresh transactions', 'error');
    });
}

function showNotification(message, type) {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} notification-toast`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    `;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add notification animations
const style = document.createElement('style');
style.textContent = `
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOutRight {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}
`;
document.head.appendChild(style);

// Add some interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Animate numbers on page load
    const numbers = document.querySelectorAll('.h4, .h5, .h6');
    numbers.forEach((number, index) => {
        if (number.textContent.includes('$') || number.textContent.includes('%')) {
            number.style.opacity = '0';
            setTimeout(() => {
                number.style.opacity = '1';
            }, index * 100);
        }
    });
    
    // Add click effect to cards (but not wallet cards)
    const cards = document.querySelectorAll('.card:not(.wallet-gradient)');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn') && !e.target.closest('a')) {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    });
    
    // Auto-refresh data every 30 seconds
    setInterval(() => {
        console.log('Auto-refreshing data...');
        // Uncomment the line below for actual auto-refresh
        // refreshTransactions();
    }, 30000);
});
</script>
@endsection
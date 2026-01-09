@extends('layouts.generic')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4">Cryptocurrency Marketplace</h1>
            <p class="lead">Discover and invest in creator tokens</p>
        </div>
    </div>
    
    <!-- Trending Cryptocurrencies -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Trending Tokens</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="pl-4">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">24h %</th>
                                    <th scope="col">Market Cap</th>
                                    <th scope="col">Creator</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trending as $index => $crypto)
                                <tr>
                                    <td class="pl-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($crypto->logo)
                                                <img src="{{ asset('storage/' . $crypto->logo) }}" alt="{{ $crypto->name }}" class="rounded-circle mr-2" width="32" height="32">
                                            @else
                                                <div class="rounded-circle bg-primary text-white mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <span>{{ substr($crypto->symbol, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $crypto->name }}</div>
                                                <div class="text-muted small">{{ $crypto->symbol }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($crypto->current_price, 8) }}</td>
                                    <td class="{{ $crypto->price_change_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $crypto->price_change_percentage >= 0 ? '+' : '' }}{{ number_format($crypto->price_change_percentage, 2) }}%
                                    </td>
                                    <td>${{ number_format($crypto->market_cap, 2) }}</td>
                                    <td>
                                        <a href="{{ route('profile', $crypto->creator->username) }}" class="text-decoration-none">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $crypto->creator->avatar ?? asset('img/default-avatar.png') }}" alt="{{ $crypto->creator->name }}" class="rounded-circle mr-1" width="24" height="24">
                                                <span>{{ $crypto->creator->name }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('cryptocurrency.show', $crypto->id) }}" class="btn btn-sm btn-primary">View</a>
                                        <a href="{{ route('cryptocurrency.buy', $crypto->id) }}" class="btn btn-sm btn-success">Buy</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- All Cryptocurrencies -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">All Tokens</h3>
                    <div class="d-flex">
                        <form class="form-inline mr-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search tokens..." name="search" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Sort by
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sortDropdown">
                                <a class="dropdown-item" href="?sort=current_price&order=desc">Price (High to Low)</a>
                                <a class="dropdown-item" href="?sort=current_price&order=asc">Price (Low to High)</a>
                                <a class="dropdown-item" href="?sort=market_cap&order=desc">Market Cap (High to Low)</a>
                                <a class="dropdown-item" href="?sort=created_at&order=desc">Newest First</a>
                                <a class="dropdown-item" href="?sort=created_at&order=asc">Oldest First</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="pl-4">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">24h %</th>
                                    <th scope="col">Market Cap</th>
                                    <th scope="col">Creator</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cryptocurrencies as $index => $crypto)
                                <tr>
                                    <td class="pl-4">{{ ($cryptocurrencies->currentPage() - 1) * $cryptocurrencies->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($crypto->logo)
                                                <img src="{{ asset('storage/' . $crypto->logo) }}" alt="{{ $crypto->name }}" class="rounded-circle mr-2" width="32" height="32">
                                            @else
                                                <div class="rounded-circle bg-primary text-white mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <span>{{ substr($crypto->symbol, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $crypto->name }}</div>
                                                <div class="text-muted small">{{ $crypto->symbol }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($crypto->current_price, 8) }}</td>
                                    <td class="{{ $crypto->price_change_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $crypto->price_change_percentage >= 0 ? '+' : '' }}{{ number_format($crypto->price_change_percentage, 2) }}%
                                    </td>
                                    <td>${{ number_format($crypto->market_cap, 2) }}</td>
                                    <td>
                                        <a href="{{ route('profile', $crypto->creator->username) }}" class="text-decoration-none">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $crypto->creator->avatar ?? asset('img/default-avatar.png') }}" alt="{{ $crypto->creator->name }}" class="rounded-circle mr-1" width="24" height="24">
                                                <span>{{ $crypto->creator->name }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('cryptocurrency.show', $crypto->id) }}" class="btn btn-sm btn-primary">View</a>
                                        <a href="{{ route('cryptocurrency.buy', $crypto->id) }}" class="btn btn-sm btn-success">Buy</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $cryptocurrencies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Token CTA -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body text-center py-5">
                    <h2 class="mb-3">Create Your Own Token</h2>
                    <p class="lead mb-4">Launch your own cryptocurrency and let your fans invest in your success</p>
                    <a href="{{ route('cryptocurrency.create') }}" class="btn btn-light btn-lg">Get Started</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
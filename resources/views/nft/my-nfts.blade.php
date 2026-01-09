@extends('layouts.generic')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4">My NFTs</h1>
            <p class="lead text-muted">Manage your NFT collection</p>
        </div>
    </div>

    <div class="row">
        @forelse($nfts as $nft)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="nft-image-container">
                        <img src="{{ $nft->image_url ?? asset('img/default-nft.png') }}" 
                             class="card-img-top nft-image" 
                             alt="{{ $nft->name }}">
                        @if($nft->activeListing)
                            <div class="nft-status-badge badge-success">
                                <i class="fas fa-tag"></i> Listed
                            </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $nft->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($nft->description ?? 'No description', 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="badge badge-{{ $nft->status === 'sold' ? 'success' : 'primary' }}">
                                {{ ucfirst($nft->status) }}
                            </span>
                            <a href="{{ route('nft.show', $nft->id) }}" class="btn btn-sm btn-primary">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">You don't have any NFTs yet</h3>
                    <p class="text-muted">Create your first NFT to get started!</p>
                    <a href="{{ route('nft.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create NFT
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($nfts->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $nfts->links() }}
            </div>
        </div>
    @endif
</div>

<style>
    .nft-image-container {
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
        background: #f8f9fa;
    }

    .nft-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .nft-status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
</style>
@endsection


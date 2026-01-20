@extends('layouts.user-no-nav')

@section('page_title', __('My Custom Requests'))

@section('content')
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-bold">{{ __('My Custom Requests') }}</h1>
                <a href="{{ route('custom-requests.marketplace') }}" class="btn btn-outline-primary">
                    {{ __('Browse Marketplace') }}
                </a>
            </div>

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'all' ? 'active' : '' }}" href="{{ route('custom-requests.my-requests', ['type' => 'all']) }}">
                        {{ __('All') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'created' ? 'active' : '' }}" href="{{ route('custom-requests.my-requests', ['type' => 'created']) }}">
                        {{ __('Created by Me') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'received' ? 'active' : '' }}" href="{{ route('custom-requests.my-requests', ['type' => 'received']) }}">
                        {{ __('Received') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        @forelse($requests as $request)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="card-title">
                                    <a href="{{ route('custom-requests.show', $request->id) }}">{{ $request->title }}</a>
                                </h5>
                                <p class="text-muted mb-2">{{ Str::limit($request->description, 150) }}</p>
                                <div class="d-flex gap-3 text-muted small">
                                    <span>{{ __('Type') }}: {{ ucfirst($request->type) }}</span>
                                    <span>{{ __('Status') }}: {{ ucfirst($request->status) }}</span>
                                    @if($request->is_marketplace)
                                        <span>{{ __('Progress') }}: ${{ number_format($request->current_amount, 2) }} / ${{ number_format($request->goal_amount, 2) }}</span>
                                    @else
                                        <span>{{ __('Price') }}: ${{ number_format($request->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : ($request->status == 'completed' ? 'info' : 'secondary') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <p class="text-muted">{{ __('No custom requests found') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row mt-4">
        <div class="col-12">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection

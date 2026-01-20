@extends('layouts.user-no-nav')

@section('page_title', __('Custom Requests Marketplace'))

@section('content')
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-bold">{{ __('Custom Requests Marketplace') }}</h1>
                @auth
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" onclick="CustomRequest.showCreateModal()">
                            {{ __('Create Request') }}
                        </button>
                        <a href="{{ route('custom-requests.my-requests') }}" class="btn btn-outline-primary">
                            {{ __('My Requests') }}
                        </a>
                    </div>
                @endauth
            </div>
            <p class="text-muted mb-4">{{ __('Browse and contribute to custom requests from creators') }}</p>
        </div>
    </div>

    <div class="row">
        @forelse($requests as $request)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $request->title }}</h5>
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'secondary' }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        <p class="card-text text-muted small">{{ Str::limit($request->description, 100) }}</p>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">{{ __('Goal') }}:</small>
                                <strong>${{ number_format($request->goal_amount, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">{{ __('Raised') }}:</small>
                                <strong>${{ number_format($request->current_amount, 2) }}</strong>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $request->progress_percentage }}%"
                                     aria-valuenow="{{ $request->progress_percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ number_format($request->progress_percentage, 1) }}% {{ __('complete') }}</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                {{ __('By') }}: <a href="{{ route('profile', ['username' => $request->creator->username]) }}">{{ $request->creator->name }}</a>
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('custom-requests.show', $request->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                {{ __('View Details') }}
                            </a>
                            @auth
                                @if($request->status == 'accepted' && $request->creator_id != Auth::id())
                                    <button class="btn btn-sm btn-primary contribute-btn" data-request-id="{{ $request->id }}">
                                        {{ __('Contribute') }}
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <p class="text-muted">{{ __('No custom requests available yet') }}</p>
                    @auth
                        <button class="btn btn-primary" onclick="CustomRequest.showCreateModal()">
                            {{ __('Create Request') }}
                        </button>
                    @endauth
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

@auth
<!-- Contribution Modal -->
<div class="modal fade" id="contributeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Contribute to Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="contributeForm">
                <div class="modal-body">
                    <input type="hidden" id="request_id" name="request_id">
                    <div class="form-group">
                        <label for="amount">{{ __('Amount') }} ($)</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="message">{{ __('Message') }} ({{ __('Optional') }})</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Contribute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contributeBtns = document.querySelectorAll('.contribute-btn');
    const contributeModal = new bootstrap.Modal(document.getElementById('contributeModal'));
    const contributeForm = document.getElementById('contributeForm');

    contributeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            document.getElementById('request_id').value = requestId;
            contributeModal.show();
        });
    });

    contributeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const requestId = document.getElementById('request_id').value;
        const amount = document.getElementById('amount').value;
        const message = document.getElementById('message').value;

        fetch(`/custom-requests/${requestId}/contribute`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                amount: amount,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Contribution added successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>
@endauth
@endsection

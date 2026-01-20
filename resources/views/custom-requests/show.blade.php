@extends('layouts.user-no-nav')

@section('page_title', $customRequest->title)

@section('content')
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="card-title">{{ $customRequest->title }}</h1>
                        <span class="badge badge-{{ $customRequest->status == 'accepted' ? 'success' : ($customRequest->status == 'completed' ? 'info' : 'secondary') }}">
                            {{ ucfirst($customRequest->status) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted">
                            {{ __('By') }}: <a href="{{ route('profile', ['username' => $customRequest->creator->username]) }}">{{ $customRequest->creator->name }}</a>
                        </p>
                        @if($customRequest->requester)
                            <p class="text-muted">
                                {{ __('Requested by') }}: <a href="{{ route('profile', ['username' => $customRequest->requester->username]) }}">{{ $customRequest->requester->name }}</a>
                            </p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5>{{ __('Description') }}</h5>
                        <p>{{ $customRequest->description }}</p>
                    </div>

                    @if($customRequest->is_marketplace)
                        <div class="mb-4">
                            <h5>{{ __('Funding Progress') }}</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Goal') }}: <strong>${{ number_format($customRequest->goal_amount, 2) }}</strong></span>
                                <span>{{ __('Raised') }}: <strong>${{ number_format($customRequest->current_amount, 2) }}</strong></span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $customRequest->progress_percentage }}%"
                                     aria-valuenow="{{ $customRequest->progress_percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($customRequest->progress_percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <h5>{{ __('Price') }}</h5>
                            <p class="h4">${{ number_format($customRequest->price, 2) }}</p>
                        </div>
                    @endif

                    @auth
                        @if($customRequest->creator_id == Auth::id())
                            <div class="d-flex gap-2 mb-3">
                                @if($customRequest->status == 'pending')
                                    <form action="{{ route('custom-requests.accept', $customRequest->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">{{ __('Accept') }}</button>
                                    </form>
                                    <form action="{{ route('custom-requests.reject', $customRequest->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">{{ __('Reject') }}</button>
                                    </form>
                                @endif
                                @if($customRequest->status == 'accepted')
                                    <form action="{{ route('custom-requests.complete', $customRequest->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">{{ __('Mark as Completed') }}</button>
                                    </form>
                                @endif
                            </div>
                        @elseif($customRequest->status == 'accepted' && $customRequest->is_marketplace)
                            <button class="btn btn-primary btn-lg contribute-btn" data-request-id="{{ $customRequest->id }}">
                                {{ __('Contribute Now') }}
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            @if($customRequest->is_marketplace && $customRequest->contributions->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <h5>{{ __('Contributions') }}</h5>
                        <div class="list-group">
                            @foreach($customRequest->contributions->where('status', 'completed') as $contribution)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $contribution->contributor->name }}</strong>
                                            <span class="text-muted">contributed</span>
                                            <strong>${{ number_format($contribution->amount, 2) }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $contribution->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($contribution->message)
                                        <p class="mb-0 mt-2 text-muted">{{ $contribution->message }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ __('Request Details') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>{{ __('Type') }}:</strong> 
                            {{ ucfirst($customRequest->type) }}
                        </li>
                        <li class="mb-2">
                            <strong>{{ __('Status') }}:</strong> 
                            {{ ucfirst($customRequest->status) }}
                        </li>
                        <li class="mb-2">
                            <strong>{{ __('Created') }}:</strong> 
                            {{ $customRequest->created_at->format('M d, Y') }}
                        </li>
                        @if($customRequest->deadline)
                            <li class="mb-2">
                                <strong>{{ __('Deadline') }}:</strong> 
                                {{ $customRequest->deadline->format('M d, Y') }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
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
    const contributeBtn = document.querySelector('.contribute-btn');
    if (contributeBtn) {
        const contributeModal = new bootstrap.Modal(document.getElementById('contributeModal'));
        const contributeForm = document.getElementById('contributeForm');

        contributeBtn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            document.getElementById('request_id').value = requestId;
            contributeModal.show();
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
    }
});
</script>
@endauth
@endsection

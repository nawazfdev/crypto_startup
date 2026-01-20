@extends('layouts.user-no-nav')

@section('page_title', __('Custom Requests Marketplace'))

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="display-4 font-weight-bold text-gradient mb-2">{{ __('Custom Requests Marketplace') }}</h1>
                    <p class="text-muted lead mb-0">{{ __('Discover and support unique creator challenges') }}</p>
                </div>
                @auth
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-primary btn-lg px-4" onclick="CustomRequest.showCreateModal()">
                            <i class="fas fa-plus-circle mr-2"></i>{{ __('Create Request') }}
                        </button>
                        <a href="{{ route('custom-requests.my-requests') }}" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-list mr-2"></i>{{ __('My Requests') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Search and Filter Bar -->
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-left-0 search-input" placeholder="{{ __('Search requests...') }}" id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control custom-select" id="statusFilter">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="accepted">{{ __('Active') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control custom-select" id="sortBy">
                                <option value="newest">{{ __('Newest First') }}</option>
                                <option value="oldest">{{ __('Oldest First') }}</option>
                                <option value="most-funded">{{ __('Most Funded') }}</option>
                                <option value="closest-goal">{{ __('Closest to Goal') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-bullseye text-primary"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ $requests->total() }}</h3>
                    <p class="stats-label text-muted mb-0">{{ __('Active Requests') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-hand-holding-heart text-success"></i>
                    </div>
                    <h3 class="stats-number mb-1">${{ number_format($requests->sum('current_amount'), 0) }}</h3>
                    <p class="stats-label text-muted mb-0">{{ __('Total Raised') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-users text-info"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ $requests->sum('contributions_count') ?? 0 }}</h3>
                    <p class="stats-label text-muted mb-0">{{ __('Contributions') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-trophy text-warning"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ $requests->where('progress_percentage', '>=', 100)->count() }}</h3>
                    <p class="stats-label text-muted mb-0">{{ __('Completed') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Grid -->
    <div class="row" id="requestsContainer">
        @forelse($requests as $request)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 request-item"
                 data-status="{{ $request->status }}"
                 data-title="{{ strtolower($request->title) }}"
                 data-description="{{ strtolower($request->description) }}">
                <div class="card marketplace-card h-100 border-0 shadow-hover">
                    <!-- Card Header with Status -->
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="status-badge">
                                @if($request->status == 'accepted')
                                    <span class="badge badge-success badge-pill px-3 py-1">
                                        <i class="fas fa-play-circle mr-1"></i>{{ __('Active') }}
                                    </span>
                                @elseif($request->status == 'completed')
                                    <span class="badge badge-info badge-pill px-3 py-1">
                                        <i class="fas fa-check-circle mr-1"></i>{{ __('Completed') }}
                                    </span>
                                @elseif($request->status == 'cancelled')
                                    <span class="badge badge-secondary badge-pill px-3 py-1">
                                        <i class="fas fa-times-circle mr-1"></i>{{ __('Cancelled') }}
                                    </span>
                                @else
                                    <span class="badge badge-warning badge-pill px-3 py-1">
                                        <i class="fas fa-clock mr-1"></i>{{ __('Pending') }}
                                    </span>
                                @endif
                            </div>
                            <div class="card-menu">
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" type="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ route('custom-requests.show', $request->id) }}">
                                            <i class="fas fa-eye mr-2"></i>{{ __('View Details') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('profile', ['username' => $request->creator->username]) }}">
                                            <i class="fas fa-user mr-2"></i>{{ __('View Creator') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body pb-3">
                        <!-- Creator Info -->
                        <div class="creator-info mb-3">
                            <div class="d-flex align-items-center">
                                <div class="creator-avatar mr-3">
                                    @if($request->creator->avatar)
                                        <img src="{{ asset('storage/' . $request->creator->avatar) }}" alt="{{ $request->creator->name }}" class="rounded-circle">
                                    @else
                                        <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="creator-details">
                                    <h6 class="creator-name mb-0">
                                        <a href="{{ route('profile', ['username' => $request->creator->username]) }}" class="text-dark text-decoration-none">
                                            {{ $request->creator->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="far fa-clock mr-1"></i>{{ $request->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Title and Description -->
                        <h5 class="card-title mb-2">
                            <a href="{{ route('custom-requests.show', $request->id) }}" class="text-dark text-decoration-none title-link">
                                {{ Str::limit($request->title, 60) }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small mb-3 description">
                            {{ Str::limit($request->description, 120) }}
                        </p>

                        <!-- Progress Section -->
                        <div class="progress-section mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">{{ __('Funding Progress') }}</span>
                                <span class="text-muted small">{{ number_format($request->progress_percentage, 1) }}%</span>
                            </div>
                            <div class="progress progress-custom mb-2">
                                <div class="progress-bar progress-bar-custom"
                                     role="progressbar"
                                     style="width: {{ min(100, $request->progress_percentage) }}%"
                                     aria-valuenow="{{ $request->progress_percentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-success font-weight-bold">
                                    ${{ number_format($request->current_amount, 0) }}
                                    <span class="text-muted">{{ __('raised') }}</span>
                                </small>
                                <small class="text-muted">
                                    ${{ number_format($request->goal_amount, 0) }}
                                    <span class="text-muted">{{ __('goal') }}</span>
                                </small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('custom-requests.show', $request->id) }}"
                               class="btn btn-outline-primary btn-sm btn-block mb-2">
                                <i class="fas fa-eye mr-1"></i>{{ __('View Details') }}
                            </a>
                            @auth
                                @if($request->status == 'accepted' && $request->creator_id != Auth::id())
                                    <button class="btn btn-primary btn-sm btn-block contribute-btn"
                                            data-request-id="{{ $request->id }}"
                                            data-title="{{ $request->title }}">
                                        <i class="fas fa-hand-holding-heart mr-1"></i>{{ __('Contribute') }}
                                    </button>
                                @elseif($request->creator_id == Auth::id())
                                    <span class="text-muted small d-block text-center">
                                        <i class="fas fa-user-edit mr-1"></i>{{ __('Your Request') }}
                                    </span>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-search fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">{{ __('No custom requests found') }}</h4>
                        <p class="text-muted mb-4">{{ __('Be the first to create an amazing custom request!') }}</p>
                        @auth
                            <button class="btn btn-primary btn-lg px-4" onclick="CustomRequest.showCreateModal()">
                                <i class="fas fa-plus-circle mr-2"></i>{{ __('Create First Request') }}
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Login to Create') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <div class="pagination-wrapper">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@auth
<!-- Contribution Modal -->
<div class="modal fade" id="contributeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-hand-holding-heart text-primary mr-2"></i>{{ __('Contribute to Request') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="contributeForm">
                <div class="modal-body">
                    <div class="request-preview mb-4 p-3 bg-light rounded">
                        <h6 id="modal-request-title" class="font-weight-bold"></h6>
                        <p class="text-muted small mb-0">{{ __('Help make this request a reality!') }}</p>
                    </div>
                    <input type="hidden" id="request_id" name="request_id">
                    <div class="form-group">
                        <label for="amount" class="font-weight-bold">
                            <i class="fas fa-dollar-sign text-success mr-1"></i>{{ __('Contribution Amount') }} ($)
                        </label>
                        <input type="number" class="form-control form-control-lg" id="amount" name="amount" step="0.01" min="0.01" required placeholder="0.00">
                        <small class="form-text text-muted">{{ __('Enter the amount you\'d like to contribute') }}</small>
                    </div>
                    <div class="form-group">
                        <label for="message" class="font-weight-bold">
                            <i class="fas fa-comment text-info mr-1"></i>{{ __('Message') }} ({{ __('Optional') }})
                        </label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="{{ __('Leave an encouraging message for the creator...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>{{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-heart mr-1"></i>{{ __('Make Contribution') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

<style>
/* Text Gradient */
.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Stats Cards */
.stats-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 1.5rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
}

.stats-label {
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Marketplace Cards */
.marketplace-card {
    transition: all 0.3s ease;
    border-radius: 15px !important;
    overflow: hidden;
}

.shadow-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.creator-avatar img,
.avatar-placeholder {
    width: 45px;
    height: 45px;
}

.avatar-placeholder {
    font-size: 1.2rem;
}

.creator-name {
    font-size: 1rem;
    font-weight: 600;
}

.title-link:hover {
    color: #667eea !important;
    text-decoration: none;
}

.description {
    line-height: 1.5;
}

/* Progress Bar */
.progress-custom {
    height: 10px;
    border-radius: 10px;
    background-color: #e2e8f0;
    overflow: hidden;
}

.progress-bar-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    transition: width 0.6s ease;
}

/* Status Badges */
.status-badge .badge {
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
}

.badge-info {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
}

.badge-warning {
    background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
}

/* Card Menu */
.card-menu .dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-menu .dropdown-item {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
}

.card-menu .dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Action Buttons */
.action-buttons .btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
}

/* Search and Filter */
.search-input:focus,
.custom-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    border-color: #667eea;
}

/* Pagination */
.pagination-wrapper .page-link {
    border-radius: 8px !important;
    border: none;
    color: #667eea;
    font-weight: 500;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

/* Empty State */
.empty-state-icon {
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }

    .stats-card {
        margin-bottom: 1rem;
    }

    .creator-info {
        margin-bottom: 1rem;
    }

    .marketplace-card .card-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}

/* Loading Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.request-item {
    animation: fadeInUp 0.6s ease-out;
}

/* Hidden items for filtering */
.request-item.hidden {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced contribution modal functionality
    const contributeBtns = document.querySelectorAll('.contribute-btn');
    const contributeModal = new bootstrap.Modal(document.getElementById('contributeModal'));
    const contributeForm = document.getElementById('contributeForm');
    const modalTitle = document.getElementById('modal-request-title');

    contributeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            const requestTitle = this.getAttribute('data-title');

            document.getElementById('request_id').value = requestId;
            if (modalTitle) {
                modalTitle.textContent = requestTitle;
            }

            // Reset form
            contributeForm.reset();

            contributeModal.show();
        });
    });

    contributeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const requestId = document.getElementById('request_id').value;
        const amount = document.getElementById('amount').value;
        const message = document.getElementById('message').value;

        // Show loading state
        const submitBtn = contributeForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>{{ __("Processing...") }}';

        fetch(`/custom-requests/${requestId}/contribute`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
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
                // Show success toast
                if (typeof launchToast !== 'undefined') {
                    launchToast('success', '{{ __("Success") }}', data.message || '{{ __("Contribution added successfully!") }}');
                } else {
                    alert(data.message || '{{ __("Contribution added successfully!") }}');
                }

                contributeModal.hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                // Show error
                const errorMsg = data.message || '{{ __("Failed to add contribution") }}';
                if (typeof launchToast !== 'undefined') {
                    launchToast('danger', '{{ __("Error") }}', errorMsg);
                } else {
                    alert('{{ __("Error") }}: ' + errorMsg);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMsg = '{{ __("An error occurred. Please try again.") }}';
            if (typeof launchToast !== 'undefined') {
                launchToast('danger', '{{ __("Error") }}', errorMsg);
            } else {
                alert(errorMsg);
            }
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Search and Filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    const requestItems = document.querySelectorAll('.request-item');

    function filterRequests() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const sortValue = sortBy.value;

        requestItems.forEach(item => {
            const title = item.dataset.title || '';
            const description = item.dataset.description || '';
            const status = item.dataset.status || '';

            const matchesSearch = !searchTerm ||
                title.includes(searchTerm) ||
                description.includes(searchTerm);

            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesStatus) {
                item.style.display = '';
                item.classList.remove('hidden');
            } else {
                item.style.display = 'none';
                item.classList.add('hidden');
            }
        });

        // Sort visible items
        sortRequests(sortValue);
    }

    function sortRequests(sortType) {
        const container = document.getElementById('requestsContainer');
        const visibleItems = Array.from(requestItems).filter(item => !item.classList.contains('hidden'));

        visibleItems.sort((a, b) => {
            switch(sortType) {
                case 'oldest':
                    return new Date(a.querySelector('.text-muted').textContent) -
                           new Date(b.querySelector('.text-muted').textContent);
                case 'most-funded':
                    const aAmount = parseFloat(a.querySelector('.text-success').textContent.replace(/[^0-9.-]+/g, ''));
                    const bAmount = parseFloat(b.querySelector('.text-success').textContent.replace(/[^0-9.-]+/g, ''));
                    return bAmount - aAmount;
                case 'closest-goal':
                    const aProgress = parseFloat(a.querySelector('.progress-bar').style.width);
                    const bProgress = parseFloat(b.querySelector('.progress-bar').style.width);
                    return bProgress - aProgress;
                default: // newest
                    return new Date(b.querySelector('.text-muted').textContent) -
                           new Date(a.querySelector('.text-muted').textContent);
            }
        });

        // Reorder DOM elements
        visibleItems.forEach(item => {
            container.appendChild(item.parentElement);
        });
    }

    // Add event listeners with debounce for search
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterRequests, 300);
    });

    statusFilter.addEventListener('change', filterRequests);
    sortBy.addEventListener('change', filterRequests);

    // Initialize sorting on page load
    sortRequests('newest');
});
</script>
@endsection

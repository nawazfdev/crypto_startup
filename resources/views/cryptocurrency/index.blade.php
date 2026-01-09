@extends('layouts.generic')

@section('page_title', __('Cryptocurrency Market'))

@push('styles')
<style>
    :root {
        /* UPDATED: Match show view colors */
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary-light: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        --border-radius: 16px;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --bg-surface: #ffffff;
        --bg-secondary: #f8fafc;
        --purple-shadow: rgba(102, 126, 234, 0.3);
        --pink-shadow: rgba(118, 75, 162, 0.3);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--bg-secondary);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: var(--text-primary);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* UPDATED: Header with modern gradient */
    .crypto-header {
        background: var(--primary-gradient);
        color: white;
        padding: 40px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 24px 24px;
        margin-bottom: 20px;
    }

    .crypto-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.15)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="60" cy="20" r="1.2" fill="rgba(255,255,255,0.08)"/></svg>');
        pointer-events: none;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-title {
        font-size: 2.8rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background: linear-gradient(45deg, #ffffff, #f8fafc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-top: 12px;
        font-weight: 300;
    }

    /* Main Layout */
    .main-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 30px;
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }

    /* UPDATED: Sidebar with modern design */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .sidebar-card {
        background: var(--bg-surface);
        border-radius: var(--border-radius);
        padding: 28px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
    }

    .sidebar-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 24px 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title::before {
        content: '🔍';
        font-size: 1.2rem;
    }

    /* UPDATED: Search & Filters */
    .search-container {
        position: relative;
        margin-bottom: 24px;
    }

    .search-input {
        width: 100%;
        padding: 16px 20px 16px 50px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        background: var(--bg-surface);
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: scale(1.02);
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 18px;
    }

    .filter-group {
        margin-bottom: 20px;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .filter-select {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 16px;
        background: var(--bg-surface);
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .filter-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .apply-filters-btn {
        width: 100%;
        padding: 16px;
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px var(--purple-shadow);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .apply-filters-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px var(--pink-shadow);
    }

    /* UPDATED: Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .action-btn {
        padding: 16px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .action-btn-primary {
        background: var(--success-color);
        color: white;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .action-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        color: white;
        text-decoration: none;
    }

    .action-btn-secondary {
        background: var(--bg-surface);
        color: var(--text-primary);
        border-color: #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .action-btn-secondary:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        color: var(--text-primary);
        text-decoration: none;
        border-color: #667eea;
    }

    .login-prompt {
        text-align: center;
        padding: 24px;
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        border-radius: 16px;
        border: 2px solid rgba(102, 126, 234, 0.1);
    }

    .login-prompt::before {
        content: '🚀';
        font-size: 2rem;
        display: block;
        margin-bottom: 12px;
    }

    .login-prompt p {
        margin: 0 0 20px 0;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* UPDATED: Main Content */
    .main-content {
        background: var(--bg-surface);
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(102, 126, 234, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .main-content:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .content-header {
        padding: 28px 32px;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, #fafbfc 0%, #f4f6f8 100%);
    }

    .content-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .content-title::before {
        content: '📊';
        font-size: 1rem;
    }

    .content-body {
        padding: 20px;
    }

    /* UPDATED: Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 24px;
        opacity: 0.7;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .empty-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-primary);
    }

    .empty-description {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    /* UPDATED: Crypto Table - FIXED WINDOW TABLE */
    .crypto-table-container {
        overflow-x: auto;
        margin: -20px -20px 0 -20px;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        max-height: 600px; /* Fixed window height */
        overflow-y: auto; /* Vertical scroll */
        border: 2px solid #e5e7eb;
    }

    .crypto-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px; /* Reduced font size */
        min-width: 800px; /* Minimum width for horizontal scroll */
    }

    .crypto-table th {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 12px 16px; /* Reduced padding */
        text-align: left;
        font-weight: 700;
        color: var(--text-primary);
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem; /* Smaller header font */
        position: sticky; /* Fixed header */
        top: 0;
        z-index: 10;
    }

    .crypto-table td {
        padding: 16px; /* Reduced padding */
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
        font-size: 13px; /* Reduced font size */
    }

    .crypto-table tr:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #f0f4ff 100%);
        transform: none; /* Remove transform for fixed table */
        transition: background 0.2s ease;
    }

    .crypto-info {
        display: flex;
        align-items: center;
        gap: 10px; /* Reduced gap */
    }

    /* UPDATED: Logo sizes - smaller for compact table */
    .crypto-logo {
        width: 32px; /* Reduced size */
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f1f3f4;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .crypto-logo-placeholder {
        width: 32px; /* Reduced size */
        height: 32px;
        border-radius: 50%;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
        border: 2px solid #f1f3f4;
        font-size: 12px; /* Smaller font */
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .crypto-name {
        font-weight: 600; /* Reduced weight */
        font-size: 0.9rem; /* Smaller font */
        color: var(--text-primary);
        text-decoration: none;
        transition: color 0.3s ease;
        white-space: nowrap;
    }

    .crypto-name:hover {
        color: #667eea;
        text-decoration: none;
    }

    .crypto-symbol {
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        color: var(--text-primary);
        padding: 4px 8px; /* Reduced padding */
        border-radius: 6px;
        font-size: 11px; /* Smaller font */
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .crypto-price {
        font-weight: 700;
        font-size: 14px; /* Smaller price font */
        color: var(--text-primary);
        white-space: nowrap;
    }

    .price-change {
        font-weight: 600; /* Reduced weight */
        font-size: 12px; /* Smaller font */
        padding: 3px 6px; /* Reduced padding */
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        white-space: nowrap;
    }

    .price-change i {
        font-size: 10px; /* Smaller icons */
    }

    .price-change.positive {
        color: var(--success-color);
        background: rgba(40, 167, 69, 0.1);
    }

    .price-change.negative {
        color: var(--danger-color);
        background: rgba(220, 53, 69, 0.1);
    }

    .price-change.neutral {
        color: var(--text-secondary);
        background: rgba(107, 114, 128, 0.1);
    }

    .crypto-market-cap {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px; /* Smaller font */
        white-space: nowrap;
    }

    .details-btn {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 6px 12px; /* Reduced padding */
        border-radius: 8px;
        font-size: 12px; /* Smaller font */
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 2px 8px var(--purple-shadow);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .details-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px var(--pink-shadow);
        color: white;
        text-decoration: none;
    }

    /* Custom scrollbar for table */
    .crypto-table-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .crypto-table-container::-webkit-scrollbar-track {
        background: #f1f3f4;
        border-radius: 10px;
    }

    .crypto-table-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .crypto-table-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b4c8a 100%);
    }

    .crypto-table-container::-webkit-scrollbar-corner {
        background: #f1f3f4;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 32px;
        display: flex;
        justify-content: center;
    }

    /* UPDATED: Mobile Card View */
    .mobile-crypto-list {
        display: none;
    }

    .crypto-card {
        background: var(--bg-surface);
        border: 2px solid rgba(102, 126, 234, 0.1);
        border-radius: 20px;
        padding: 24px;
        margin: 16px 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .crypto-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        border-color: rgba(102, 126, 234, 0.3);
    }

    .crypto-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .crypto-card-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .crypto-card-logo {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f1f3f4;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .crypto-card-logo-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        border: 3px solid #f1f3f4;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .crypto-card-details h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .crypto-card-details p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .crypto-card-price {
        text-align: right;
    }

    .crypto-card-price .price {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .crypto-card-price .change {
        font-size: 15px;
        font-weight: 700;
        margin: 6px 0 0 0;
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-block;
    }

    .crypto-card-price .change.positive {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .crypto-card-price .change.negative {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    .crypto-card-price .change.neutral {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
    }

    .crypto-card-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f1f3f4;
    }

    .crypto-stat {
        text-align: center;
        padding: 12px;
        background: linear-gradient(135deg, #f8fafc 0%, #f0f4ff 100%);
        border-radius: 12px;
    }

    .crypto-stat-label {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 6px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .crypto-stat-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .crypto-card-actions {
        display: flex;
        gap: 16px;
        margin-top: 20px;
    }

    .crypto-action-btn {
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .crypto-action-btn.primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        box-shadow: 0 2px 8px var(--purple-shadow);
    }

    .crypto-action-btn.secondary {
        background: transparent;
        color: var(--text-primary);
        border: 2px solid #e5e7eb;
    }

    .crypto-action-btn:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }

    .crypto-action-btn.primary:hover {
        color: white;
        box-shadow: 0 4px 15px var(--pink-shadow);
    }

    .crypto-action-btn.secondary:hover {
        color: var(--text-primary);
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .crypto-header {
            padding: 30px 0;
            border-radius: 0 0 16px 16px;
            margin-bottom: 16px;
        }

        .header-title {
            font-size: 2.2rem;
        }

        .header-subtitle {
            font-size: 1rem;
        }

        .main-layout {
            grid-template-columns: 1fr;
            gap: 0;
            margin-top: 0;
        }

        .sidebar {
            order: 2;
            background: var(--bg-surface);
            border-radius: 0;
            margin: 0;
            padding: 0;
        }

        .sidebar-card {
            background: transparent;
            box-shadow: none;
            border: none;
            border-radius: 0;
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .main-content {
            order: 1;
            background: var(--bg-surface);
            border-radius: 0;
            box-shadow: none;
            border: none;
        }

        .content-header {
            display: none;
        }

        .content-body {
            padding: 0;
        }

        .crypto-table-container {
            display: none;
        }

        .mobile-crypto-list {
            display: block;
        }

        .empty-state {
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 4rem;
        }

        .empty-title {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 10px;
        }

        .crypto-header {
            padding: 24px 0;
        }

        .header-title {
            font-size: 1.8rem;
        }

        .sidebar-card {
            padding: 20px;
        }

        .content-body {
            padding: 16px;
        }

        .crypto-card {
            margin: 12px 16px;
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="crypto-header">
    <div class="container">
        <div class="header-content">
            <div class="d-none d-md-block">
                <h1 class="header-title">{{ __('Crypto Market') }}</h1>
                <p class="header-subtitle">{{ __('Trade, track, and manage your cryptocurrency portfolio') }}</p>
            </div>
            <div class="d-md-none">
                <h1 class="header-title">{{ __('Market') }}</h1>
                <p class="header-subtitle">{{ __('Your crypto trading hub') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="main-layout">
        <div class="sidebar">
            <div class="sidebar-card">
                <h3 class="card-title">{{ __('Market Filters') }}</h3>
                <form method="GET" action="{{ route('cryptocurrency.index') }}">
                    <div class="search-container">
                        <i class="search-icon">🔍</i>
                        <input type="text" 
                               class="search-input" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="{{ __('Search cryptocurrencies...') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label" for="sort">{{ __('Sort By') }}</label>
                        <select class="filter-select" id="sort" name="sort">
                            <option value="current_price" {{ $sort == 'current_price' ? 'selected' : '' }}>{{ __('Price') }}</option>
                            <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                            <option value="market_cap" {{ $sort == 'market_cap' ? 'selected' : '' }}>{{ __('Market Cap') }}</option>
                            <option value="created_at" {{ $sort == 'created_at' ? 'selected' : '' }}>{{ __('Recently Added') }}</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label" for="order">{{ __('Order') }}</label>
                        <select class="filter-select" id="order" name="order">
                            <option value="desc" {{ $order == 'desc' ? 'selected' : '' }}>{{ __('High to Low') }}</option>
                            <option value="asc" {{ $order == 'asc' ? 'selected' : '' }}>{{ __('Low to High') }}</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="apply-filters-btn">{{ __('Apply Filters') }}</button>
                </form>
            </div>
            
            <div class="sidebar-card">
                @auth
                    <h3 class="card-title">{{ __('Quick Actions') }}</h3>
                    <div class="action-buttons">
                        <a href="{{ route('cryptocurrency.create') }}" class="action-btn action-btn-primary">
                            <span>💰</span> {{ __('Create Your Token') }}
                        </a>
                        <a href="{{ route('cryptocurrency.wallet') }}" class="action-btn action-btn-secondary">
                            <span>👝</span> {{ __('My Wallet') }}
                        </a>
                    </div>
                @else
                    <div class="login-prompt">
                        <p>{{ __('Join the crypto revolution! Login to create your own token and manage your portfolio.') }}</p>
                        <a href="{{ route('login') }}" class="action-btn action-btn-primary">
                            {{ __('Get Started') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h2 class="content-title">{{ __('Available Tokens') }}</h2>
            </div>
            
            <div class="content-body">
                @if($cryptocurrencies->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">📈</div>
                        <h3 class="empty-title">{{ __('No tokens found') }}</h3>
                        <p class="empty-description">{{ __('Be the first to create a token and start trading!') }}</p>
                        @auth
                            <a href="{{ route('cryptocurrency.create') }}" class="action-btn action-btn-primary">
                                {{ __('Create First Token') }}
                            </a>
                        @endauth
                    </div>
                @else
                    <!-- Desktop Table View -->
                    <div class="crypto-table-container d-none d-md-block">
                        <table class="crypto-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Token') }}</th>
                                    <th>{{ __('Symbol') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('24h Change') }}</th>
                                    <th>{{ __('Market Cap') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cryptocurrencies as $crypto)
                                    <tr>
                                        <td>
                                            <div class="crypto-info">
                                                @if($crypto->logo && Storage::disk('public')->exists($crypto->logo))
                                                    <img src="{{ asset('storage/' . $crypto->logo) }}" 
                                                         alt="{{ $crypto->name }}" 
                                                         class="crypto-logo">
                                                @else
                                                    <div class="crypto-logo-placeholder">
                                                        {{ strtoupper(substr($crypto->symbol, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <a href="{{ route('cryptocurrency.show', $crypto->id) }}" class="crypto-name">
                                                    {{ $crypto->name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="crypto-symbol">{{ $crypto->symbol }}</span>
                                        </td>
                                        <td>
                                            <span class="crypto-price">${{ number_format($crypto->current_price, 8) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $change = $crypto->change_24h ?? $crypto->price_change_percentage ?? 0;
                                            @endphp
                                            @if($change > 0)
                                                <span class="price-change positive">
                                                    <i class="fas fa-arrow-up"></i> +{{ number_format($change, 2) }}%
                                                </span>
                                            @elseif($change < 0)
                                                <span class="price-change negative">
                                                    <i class="fas fa-arrow-down"></i> {{ number_format($change, 2) }}%
                                                </span>
                                            @else
                                                <span class="price-change neutral">
                                                    <i class="fas fa-minus"></i> 0.00%
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="crypto-market-cap">
                                                @if($crypto->market_cap >= 1000000000)
                                                    ${{ number_format($crypto->market_cap / 1000000000, 2) }}B
                                                @elseif($crypto->market_cap >= 1000000)
                                                    ${{ number_format($crypto->market_cap / 1000000, 2) }}M
                                                @elseif($crypto->market_cap >= 1000)
                                                    ${{ number_format($crypto->market_cap / 1000, 2) }}K
                                                @else
                                                    ${{ number_format($crypto->market_cap, 2) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('cryptocurrency.show', $crypto->id) }}" class="details-btn">
                                                {{ __('View Details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="mobile-crypto-list d-md-none">
                        @foreach($cryptocurrencies as $crypto)
                            <div class="crypto-card">
                                <div class="crypto-card-header">
                                    <div class="crypto-card-info">
                                        @if($crypto->logo && Storage::disk('public')->exists($crypto->logo))
                                            <img src="{{ asset('storage/' . $crypto->logo) }}" 
                                                 alt="{{ $crypto->name }}" 
                                                 class="crypto-card-logo">
                                        @else
                                            <div class="crypto-card-logo-placeholder">
                                                {{ strtoupper(substr($crypto->symbol, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="crypto-card-details">
                                            <h3>{{ $crypto->name }}</h3>
                                            <p>{{ strtoupper($crypto->symbol) }}</p>
                                        </div>
                                    </div>
                                    <div class="crypto-card-price">
                                        <p class="price">${{ number_format($crypto->current_price, 6) }}</p>
                                        @php
                                            $change = $crypto->change_24h ?? $crypto->price_change_percentage ?? 0;
                                        @endphp
                                        @if($change > 0)
                                            <p class="change positive">+{{ number_format($change, 2) }}%</p>
                                        @elseif($change < 0)
                                            <p class="change negative">{{ number_format($change, 2) }}%</p>
                                        @else
                                            <p class="change neutral">0.00%</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="crypto-card-stats">
                                    <div class="crypto-stat">
                                        <div class="crypto-stat-label">{{ __('Market Cap') }}</div>
                                        <div class="crypto-stat-value">
                                            @if($crypto->market_cap >= 1000000000)
                                                ${{ number_format($crypto->market_cap / 1000000000, 1) }}B
                                            @elseif($crypto->market_cap >= 1000000)
                                                ${{ number_format($crypto->market_cap / 1000000, 1) }}M
                                            @elseif($crypto->market_cap >= 1000)
                                                ${{ number_format($crypto->market_cap / 1000, 1) }}K
                                            @else
                                                ${{ number_format($crypto->market_cap, 0) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="crypto-stat">
                                        <div class="crypto-stat-label">{{ __('24h Volume') }}</div>
                                        <div class="crypto-stat-value">
                                            @if($crypto->volume_24h >= 1000000)
                                                ${{ number_format($crypto->volume_24h / 1000000, 1) }}M
                                            @elseif($crypto->volume_24h >= 1000)
                                                ${{ number_format($crypto->volume_24h / 1000, 1) }}K
                                            @else
                                                ${{ number_format($crypto->volume_24h, 0) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="crypto-card-actions">
                                    <a href="{{ route('cryptocurrency.show', $crypto->id) }}" class="crypto-action-btn primary">
                                        <i class="fas fa-coins me-1"></i> {{ __('Trade') }}
                                    </a>
                                    <a href="{{ route('cryptocurrency.show', $crypto->id) }}" class="crypto-action-btn secondary">
                                        <i class="fas fa-chart-line me-1"></i> {{ __('Details') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="pagination-container">
                        {{ $cryptocurrencies->appends(['search' => $search, 'sort' => $sort, 'order' => $order])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to buttons
    const buttons = document.querySelectorAll('.apply-filters-btn, .action-btn, .details-btn, .crypto-action-btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.tagName.toLowerCase() === 'button' && this.type === 'submit') {
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Applying...';
                this.disabled = true;
            }
        });
    });
    
    // Add search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit search after 1 second of no typing
                // Uncomment the line below for auto-search
                // this.closest('form').submit();
            }, 1000);
        });
    }
    
    // Add hover effects to crypto cards
    const cryptoCards = document.querySelectorAll('.crypto-card');
    cryptoCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add table row click functionality
    const tableRows = document.querySelectorAll('.crypto-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('a') && !e.target.closest('button')) {
                const link = this.querySelector('.crypto-name');
                if (link) {
                    window.location.href = link.href;
                }
            }
        });
        
        row.style.cursor = 'pointer';
    });
    
    // Add animation to numbers
    const numbers = document.querySelectorAll('.crypto-price, .crypto-market-cap, .crypto-stat-value, .price');
    numbers.forEach((number, index) => {
        number.style.opacity = '0';
        setTimeout(() => {
            number.style.opacity = '1';
            number.style.transition = 'opacity 0.3s ease';
        }, index * 50);
    });
    
    // Add real-time price update simulation (optional)
    function simulatePriceUpdates() {
        const priceElements = document.querySelectorAll('.crypto-price, .price');
        priceElements.forEach(element => {
            // Add subtle price animation every 30 seconds
            setInterval(() => {
                element.style.transform = 'scale(1.05)';
                element.style.color = '#667eea';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                    element.style.color = '';
                }, 200);
            }, 30000 + Math.random() * 10000);
        });
    }
    
    // Uncomment to enable price animations
    // simulatePriceUpdates();
});

// Add notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add notification styles
    const style = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#667eea'};
        color: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        max-width: 400px;
    `;
    
    notification.style.cssText = style;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 4000);
}

// Add notification animations to document head
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOutRight {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
}
`;
document.head.appendChild(notificationStyles);
</script>
@endsection
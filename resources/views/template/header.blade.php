{{-- Modern Professional Header --}}
<nav class="modern-navbar {{(Cookie::get('app_theme') == null ? (getSetting('site.default_user_theme') == 'dark' ? 'dark-theme' : 'light-theme') : (Cookie::get('app_theme') == 'dark' ? 'dark-theme' : 'light-theme'))}}">
    <div class="container-fluid">
        <div class="navbar-content">
            {{-- Logo Section --}}
            <div class="navbar-brand-section">
                <a href="{{ route('home') }}" class="brand-link">
                    <img src="{{asset( (Cookie::get('app_theme') == null ? (getSetting('site.default_user_theme') == 'dark' ? getSetting('site.dark_logo') : getSetting('site.light_logo')) : (Cookie::get('app_theme') == 'dark' ? getSetting('site.dark_logo') : getSetting('site.light_logo'))) )}}" class="brand-logo" alt="{{__("Site logo")}}">
                </a>
            </div>

            {{-- Main Navigation --}}
            <div class="navbar-navigation">
                @if(Auth::check())
                    <div class="nav-links">
                    @if(!getSetting('site.hide_create_post_menu'))
                            <a href="{{ route('posts.create') }}" class="nav-link create-link">
                                <div class="create-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <span>{{ __('Create') }}</span>
                            </a>
                    @endif
                        <a href="{{ route('feed') }}" class="nav-link feed-link">
                            <div class="nav-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="9 22 9 12 15 12 15 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span>{{ __('Feed') }}</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Search Bar --}}
            @if(Auth::check())
            <div class="search-section">
                <div class="search-container">
                    <div class="search-icon-wrapper">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <input type="text" 
                           class="search-input" 
                           placeholder="{{__('Search creators, posts, tokens...')}}"
                           id="global-search">
                </div>
            </div>
            @endif

            {{-- Right Actions --}}
            <div class="navbar-actions">
                @guest
                    <div class="auth-links">
                    @if(Route::currentRouteName() !== 'profile')
                            <a href="{{ route('login') }}" class="auth-link login-link">
                                {{ __('Login') }}
                            </a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="auth-link register-link">
                                    {{ __('Sign Up') }}
                                </a>
                            @endif
                        @endif
                    </div>
                @else
                    {{-- Quick Actions --}}
                    <div class="quick-actions">
                        {{-- Wallet Balance --}}
                        <a href="{{ route('cryptocurrency.wallet') }}" class="quick-action wallet-action" title="{{__('My Wallet')}}">
                            <div class="action-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 12V7H5a2 2 0 0 1-2-2 2 2 0 0 1 2-2h14v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18 12a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h2v-5h-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="wallet-balance">${{ number_format(Auth::user()->wallet->total_balance ?? Auth::user()->wallet->balance ?? 0, 2) }}</span>
                        </a>

                        {{-- Notifications --}}
                        <a href="{{route('my.notifications')}}" class="quick-action notification-action" title="{{__('Notifications')}}">
                            <div class="action-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            @if(NotificationsHelper::getUnreadNotifications()->total > 0)
                                <span class="notification-badge">{{ NotificationsHelper::getUnreadNotifications()->total }}</span>
                            @endif
                        </a>

                        {{-- Messages/Chat --}}
                        <a href="{{route('my.messenger.get')}}" class="quick-action message-action" title="{{__('Messages')}}">
                            <div class="action-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            @if(NotificationsHelper::getUnreadMessages() > 0)
                                <span class="notification-badge message-badge">{{ NotificationsHelper::getUnreadMessages() }}</span>
                            @endif
                        </a>
                    </div>

                    {{-- Tokens Dropdown --}}
                    <div class="dropdown tokens-dropdown">
                        <button class="dropdown-toggle tokens-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="tokens-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                    <path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="tokens-label">{{ __('Tokens') }}</span>
                            <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dropdown-menu tokens-menu">
                            <div class="dropdown-header">
                                <div class="dropdown-title">
                                    <div class="title-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                            <path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="title-text">
                                        <div class="main-title">{{ __('Cryptocurrency') }}</div>
                                        <div class="sub-title">{{ __('Manage your digital assets') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <a class="dropdown-item {{ in_array(Route::currentRouteName(), ['cryptocurrency.marketplace', 'cryptocurrency.buy', 'cryptocurrency.sell']) ? 'active' : '' }}" href="{{ route('cryptocurrency.marketplace') }}">
                                <div class="item-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <polyline points="9 22 9 12 15 12 15 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="item-content">
                                    <span class="item-title">{{ __('Marketplace') }}</span>
                                    <span class="item-description">{{ __('Buy & sell tokens') }}</span>
                                </div>
                                <div class="item-arrow">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                            
                            <a class="dropdown-item {{ in_array(Route::currentRouteName(), ['cryptocurrency.explorer', 'cryptocurrency.show']) ? 'active' : '' }}" href="{{ route('cryptocurrency.explorer') }}">
                                <div class="item-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                                        <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="item-content">
                                    <span class="item-title">{{ __('Explorer') }}</span>
                                    <span class="item-description">{{ __('Browse blockchain') }}</span>
                                </div>
                                <div class="item-arrow">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                            
                            <a class="dropdown-item {{ in_array(Route::currentRouteName(), ['cryptocurrency.wallet', 'cryptocurrency.transactions', 'cryptocurrency.deposit', 'cryptocurrency.withdraw']) ? 'active' : '' }}" href="{{ route('cryptocurrency.wallet') }}">
                                <div class="item-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 12V7H5a2 2 0 0 1-2-2 2 2 0 0 1 2-2h14v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M18 12a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h2v-5h-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="item-content">
                                    <span class="item-title">{{ __('My Wallet') }}</span>
                                    <span class="item-description">{{ __('View balance & history') }}</span>
                                </div>
                                <div class="item-arrow">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <a class="dropdown-item {{ Route::currentRouteName() == 'cryptocurrency.create' ? 'active' : '' }}" href="{{ route('cryptocurrency.create') }}">
                                <div class="item-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                        <path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="item-content">
                                    <span class="item-title">{{ __('Create Token') }}</span>
                                    <span class="item-description">{{ __('Launch your own token') }}</span>
                                </div>
                                <div class="item-arrow">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- User Profile Dropdown --}}
                    <div class="dropdown user-dropdown">
                        <button class="user-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{Auth::user()->avatar}}" class="user-avatar" alt="{{Auth::user()->name}}">
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                            </div>
                            <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dropdown-menu user-menu">
                            <div class="user-profile-header">
                                <img src="{{Auth::user()->avatar}}" class="dropdown-avatar" alt="{{Auth::user()->name}}">
                                <div class="user-profile-info">
                                    <div class="user-profile-name">{{ Auth::user()->name }}</div>
                                    <div class="user-profile-handle">{{ Auth::user()->username }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('profile',['username'=>Auth::user()->username])}}">
                                <div class="item-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span>{{__('My Profile')}}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('my.settings')}}">
                                <div class="item-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span>{{__('Settings')}}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('my.settings',['type'=>'subscriptions'])}}">
                                <div class="item-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span>{{__('Subscriptions')}}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('my.settings',['type'=>'payments'])}}">
                                <div class="item-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <line x1="1" y1="10" x2="23" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span>{{__('Payments')}}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item logout-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="item-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <polyline points="16 17 21 12 16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
/* Modern Header Styles */
.modern-navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 64px;
    border-bottom: 1px solid transparent;
}

.modern-navbar.light-theme {
    background: rgba(255, 255, 255, 0.9);
    border-bottom-color: rgba(0, 0, 0, 0.08);
}

.modern-navbar.dark-theme {
    background: rgba(15, 15, 15, 0.9);
    border-bottom-color: rgba(255, 255, 255, 0.08);
}

/* Container */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 24px;
}

.navbar-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
    gap: 32px;
}

/* Logo */
.navbar-brand-section .brand-logo {
    height: 40px;
    width: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.brand-link:hover .brand-logo {
    transform: scale(1.05);
}

/* Navigation */
.navbar-navigation {
    flex: 1;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 8px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.light-theme .nav-link {
    color: #374151;
}

.dark-theme .nav-link {
    color: #e5e7eb;
}

.nav-link:hover {
    background: rgba(0, 0, 0, 0.04);
}

.dark-theme .nav-link:hover {
    background: rgba(255, 255, 255, 0.04);
}

/* Create Link */
.nav-link.create-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    border: none;
    font-weight: 600;
}

.nav-link.create-link:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
}

.nav-icon, .create-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Search Section */
.search-section {
    flex: 1;
    max-width: 480px;
}

.search-container {
    position: relative;
}

.search-input {
    width: 100%;
    padding: 12px 20px 12px 44px;
    border-radius: 12px;
    border: 1px solid transparent;
    font-size: 14px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.light-theme .search-input {
    background: rgba(0, 0, 0, 0.04);
    color: #374151;
    border-color: rgba(0, 0, 0, 0.1);
}

.dark-theme .search-input {
    background: rgba(255, 255, 255, 0.04);
    color: #e5e7eb;
    border-color: rgba(255, 255, 255, 0.1);
}

.light-theme .search-input::placeholder {
    color: #9ca3af;
}

.dark-theme .search-input::placeholder {
    color: #6b7280;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-icon-wrapper {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.search-icon {
    width: 18px;
    height: 18px;
    color: #9ca3af;
}

.dark-theme .search-icon {
    color: #6b7280;
}

/* Right Actions */
.navbar-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

/* Auth Links */
.auth-links {
    display: flex;
    align-items: center;
    gap: 12px;
}

.auth-link {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.login-link {
    color: #667eea;
    border: 1px solid rgba(102, 126, 234, 0.3);
}

.login-link:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: rgba(102, 126, 234, 0.5);
}

.register-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.register-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
}

/* Quick Actions */
.quick-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    border: 1px solid transparent;
}

.light-theme .quick-action {
    color: #374151;
    background: rgba(0, 0, 0, 0.02);
}

.dark-theme .quick-action {
    color: #e5e7eb;
    background: rgba(255, 255, 255, 0.02);
}

.quick-action:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: rgba(102, 126, 234, 0.2);
}

.action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.wallet-balance {
    font-weight: 600;
    color: #10b981;
}

/* Notification Badges */
.notification-badge {
    position: absolute;
    top: 4px;
    right: 4px;
    background: #ef4444;
    color: white;
    font-size: 11px;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    border: 2px solid;
}

.light-theme .notification-badge {
    border-color: white;
}

.dark-theme .notification-badge {
    border-color: #0f0f0f;
}

.message-badge {
    background: #8b5cf6;
}

/* Dropdowns */
.dropdown {
    position: relative;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid transparent;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
}

.light-theme .dropdown-toggle {
    color: #374151;
}

.dark-theme .dropdown-toggle {
    color: #e5e7eb;
}

.dropdown-toggle:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: rgba(102, 126, 234, 0.2);
}

.tokens-toggle {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
    color: #f59e0b;
    border-color: rgba(245, 158, 11, 0.2);
}

.tokens-toggle:hover {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
}

.tokens-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropdown-arrow {
    transition: transform 0.3s ease;
}

.dropdown.show .dropdown-arrow {
    transform: rotate(180deg);
}

/* Dropdown Menus */
.dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    min-width: 280px;
    padding: 8px;
    border-radius: 16px;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid transparent;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
    pointer-events: none;
}

.light-theme .dropdown-menu {
    background: rgba(255, 255, 255, 0.95);
    border-color: rgba(0, 0, 0, 0.1);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

.dark-theme .dropdown-menu {
    background: rgba(30, 30, 30, 0.95);
    border-color: rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.dropdown.show .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}

/* Tokens Dropdown */
.dropdown-header {
    padding: 16px;
    margin: -8px -8px 8px -8px;
    border-radius: 12px 12px 0 0;
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
    border-bottom: 1px solid rgba(245, 158, 11, 0.1);
}

.dropdown-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.title-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.main-title {
    font-weight: 600;
    font-size: 14px;
    color: #f59e0b;
}

.sub-title {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 2px;
}

/* Dropdown Items */
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    margin: 4px 0;
    border: 1px solid transparent;
    position: relative;
    z-index: 1;
    cursor: pointer;
}

.light-theme .dropdown-item {
    color: #374151;
}

.dark-theme .dropdown-item {
    color: #e5e7eb;
}

.dropdown-item:hover, .dropdown-item.active {
    background: rgba(102, 126, 234, 0.1);
    border-color: rgba(102, 126, 234, 0.2);
}

.item-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.item-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.item-title {
    font-weight: 500;
    font-size: 14px;
}

.item-description {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 2px;
}

.item-arrow {
    color: #9ca3af;
}

/* Dropdown Dividers */
.dropdown-divider {
    height: 1px;
    margin: 8px 0;
}

.light-theme .dropdown-divider {
    background: rgba(0, 0, 0, 0.1);
}

.dark-theme .dropdown-divider {
    background: rgba(255, 255, 255, 0.1);
}

/* User Dropdown */
.user-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 4px 4px 12px;
    border-radius: 50px;
    border: 1px solid transparent;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-toggle:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: rgba(102, 126, 234, 0.2);
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(102, 126, 234, 0.3);
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.user-name {
    font-size: 14px;
    font-weight: 500;
}

.light-theme .user-name {
    color: #374151;
}

.dark-theme .user-name {
    color: #e5e7eb;
}

/* User Profile Header */
.user-profile-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    margin: -8px -8px 8px -8px;
    border-radius: 12px 12px 0 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
    position: relative;
    z-index: 0;
    pointer-events: auto;
}

.dropdown-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(102, 126, 234, 0.5);
}

.user-profile-info {
    flex: 1;
}

.user-profile-name {
    font-weight: 600;
    font-size: 16px;
}

.user-profile-handle {
    font-size: 14px;
    color: #9ca3af;
    margin-top: 2px;
}

/* Logout Item */
.logout-item {
    color: #ef4444;
}

.logout-item:hover {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.2);
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    padding: 8px;
    border: none;
    background: transparent;
    color: inherit;
    cursor: pointer;
    font-size: 20px;
}

/* Responsive */
@media (max-width: 1200px) {
    .search-section {
        max-width: 320px;
    }
}

@media (max-width: 992px) {
    .navbar-navigation {
        display: none;
    }
    
    .search-section {
        max-width: 240px;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 0 16px;
    }
    
    .navbar-content {
        gap: 16px;
    }
    
    .search-section {
        display: none;
    }
    
    .wallet-balance, .tokens-label, .user-info {
        display: none;
    }
    
    .quick-actions {
        gap: 8px;
    }
    
    .quick-action {
        padding: 8px;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
}

@media (max-width: 576px) {
    .auth-links {
        gap: 8px;
    }
    
    .auth-link {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .dropdown-menu {
        position: fixed;
        top: 64px;
        left: 16px;
        right: 16px;
        max-width: calc(100vw - 32px);
    }
}
</style>

<script>
// Enhanced dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    const dropdowns = document.querySelectorAll('.dropdown');
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        dropdowns.forEach(dropdown => {
            const isClickInside = dropdown.contains(event.target);
            const isDropdownToggle = event.target.closest('.dropdown-toggle, .user-toggle');
            
            if (!isClickInside && !isDropdownToggle) {
                dropdown.classList.remove('show');
            }
        });
    });

    // Toggle dropdowns
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle, .user-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.dropdown');
            const isCurrentlyOpen = dropdown.classList.contains('show');
            
            // Close all other dropdowns
            dropdowns.forEach(otherDropdown => {
                if (otherDropdown !== dropdown) {
                    otherDropdown.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            if (isCurrentlyOpen) {
                dropdown.classList.remove('show');
            } else {
                dropdown.classList.add('show');
            }
        });
    });

    // Handle dropdown item clicks
    document.addEventListener('click', function(event) {
        const dropdownItem = event.target.closest('.dropdown-item');
        if (dropdownItem && !dropdownItem.classList.contains('logout-item')) {
            const dropdown = dropdownItem.closest('.dropdown');
            if (dropdown) {
                dropdown.classList.remove('show');
            }
        }
    });

    // Close dropdowns with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Search functionality
    const searchInput = document.getElementById('global-search');
    
    if (searchInput) {
        // Enter key search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                window.location.href = '{{ route("search.get") }}?q=' + encodeURIComponent(this.value.trim());
            }
        });
    }

    // Handle header scroll effect
    let lastScroll = 0;
    const navbar = document.querySelector('.modern-navbar');
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > lastScroll && currentScroll > 64) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScroll = currentScroll;
    });
});
</script>
<form method="POST" action="{{ route('register') }}" id="register-form" style="margin-bottom: 24px;">
    @csrf

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.5); color: #fecaca; padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 16px;">
            <ul style="list-style: disc; list-style-position: inside; margin: 0; padding: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: #d1d5db; margin-bottom: 8px;">
            {{ __('Full Name') }}
        </label>
        <input 
            id="name" 
            type="text" 
            name="name" 
            value="{{ old('name') }}" 
            autocomplete="name" 
            autofocus
            required
            style="width: 100%; padding: 12px 16px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; color: #ffffff; font-size: 14px; transition: all 0.3s ease; box-sizing: border-box;"
            onfocus="this.style.outline='none'; this.style.borderColor='#830866'; this.style.boxShadow='0 0 0 3px rgba(131, 8, 102, 0.1)';"
            onblur="this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.boxShadow='none';"
            placeholder="Enter your full name"
        >
        @error('name')
            <p style="margin-top: 4px; font-size: 14px; color: #f87171;">{{ $message }}</p>
        @enderror
    </div>
    
    <div style="margin-bottom: 20px;">
        <label for="username" style="display: block; font-size: 14px; font-weight: 500; color: #d1d5db; margin-bottom: 8px;">
            {{ __('Username') }}
        </label>
        <input 
            id="username" 
            type="text" 
            name="username" 
            value="{{ old('username') }}" 
            required 
            autocomplete="username"
            style="width: 100%; padding: 12px 16px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; color: #ffffff; font-size: 14px; transition: all 0.3s ease; box-sizing: border-box;"
            onfocus="this.style.outline='none'; this.style.borderColor='#830866'; this.style.boxShadow='0 0 0 3px rgba(131, 8, 102, 0.1)';"
            onblur="this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.boxShadow='none';"
            placeholder="Choose a username"
        >
        @error('username')
            <p style="margin-top: 4px; font-size: 14px; color: #f87171;">{{ $message }}</p>
        @enderror
    </div>

    <div style="margin-bottom: 20px;">
        <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: #d1d5db; margin-bottom: 8px;">
            {{ __('Email Address') }}
        </label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autocomplete="email"
            style="width: 100%; padding: 12px 16px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; color: #ffffff; font-size: 14px; transition: all 0.3s ease; box-sizing: border-box;"
            onfocus="this.style.outline='none'; this.style.borderColor='#830866'; this.style.boxShadow='0 0 0 3px rgba(131, 8, 102, 0.1)';"
            onblur="this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.boxShadow='none';"
            placeholder="Enter your email"
        >
        @error('email')
            <p style="margin-top: 4px; font-size: 14px; color: #f87171;">{{ $message }}</p>
        @enderror
    </div>

    <div style="margin-bottom: 20px;">
        <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: #d1d5db; margin-bottom: 8px;">
            {{ __('Password') }}
        </label>
        <input 
            id="password" 
            type="password" 
            name="password" 
            required 
            autocomplete="new-password"
            style="width: 100%; padding: 12px 16px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; color: #ffffff; font-size: 14px; transition: all 0.3s ease; box-sizing: border-box;"
            onfocus="this.style.outline='none'; this.style.borderColor='#830866'; this.style.boxShadow='0 0 0 3px rgba(131, 8, 102, 0.1)';"
            onblur="this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.boxShadow='none';"
            placeholder="Create a password"
        >
        @error('password')
            <p style="margin-top: 4px; font-size: 14px; color: #f87171;">{{ $message }}</p>
        @enderror
    </div>

    <div style="margin-bottom: 20px;">
        <label for="password-confirm" style="display: block; font-size: 14px; font-weight: 500; color: #d1d5db; margin-bottom: 8px;">
            {{ __('Confirm Password') }}
        </label>
        <input 
            id="password-confirm" 
            type="password" 
            name="password_confirmation" 
            required 
            autocomplete="new-password"
            style="width: 100%; padding: 12px 16px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; color: #ffffff; font-size: 14px; transition: all 0.3s ease; box-sizing: border-box;"
            onfocus="this.style.outline='none'; this.style.borderColor='#830866'; this.style.boxShadow='0 0 0 3px rgba(131, 8, 102, 0.1)';"
            onblur="this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.boxShadow='none';"
            placeholder="Confirm your password"
        >
        @error('password_confirmation')
            <p style="margin-top: 4px; font-size: 14px; color: #f87171;">{{ $message }}</p>
        @enderror
    </div>

    <div style="display: flex; align-items: flex-start; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; height: 20px; margin-right: 12px; margin-top: 2px;">
            <input 
                id="tosAgree" 
                name="terms" 
                type="checkbox" 
                value="1" 
                required
                style="width: 16px; height: 16px; cursor: pointer;"
            >
        </div>
        <label for="tosAgree" style="font-size: 14px; color: #d1d5db; line-height: 1.5; cursor: pointer;">
            <span>{{ __('I agree to the') }} <a href="{{route('pages.get',['slug'=>GenericHelper::getTOSPage()->slug])}}" style="color: #830866; text-decoration: none;">{{ __('Terms of Use') }}</a> {{ __('and') }} <a href="{{route('pages.get',['slug'=>GenericHelper::getPrivacyPage()->slug])}}" style="color: #830866; text-decoration: none;">{{ __('Privacy Policy') }}</a>.</span>
        </label>
        @error('terms')
            <p style="margin-top: 4px; font-size: 14px; color: #f87171;">{{ $message }}</p>
        @enderror
    </div>

    @if(getSetting('security.recaptcha_enabled') && !Auth::check())
        <div style="display: flex; justify-content: center; margin-bottom: 24px;">
            {!! NoCaptcha::display(['data-theme' => (Cookie::get('app_theme') == null ? (getSetting('site.default_user_theme')) : Cookie::get('app_theme') )]) !!}
            @error('g-recaptcha-response')
                <p style="margin-top: 4px; font-size: 14px; color: #f87171;">{{__("Please check the captcha field.")}}</p>
            @enderror
        </div>
    @endif

    <button 
        type="submit" 
        style="width: 100%; background: linear-gradient(135deg, #830866 0%, #a10a7f 100%); color: #ffffff; font-weight: 600; padding: 12px 16px; border-radius: 12px; border: none; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 15px rgba(131, 8, 102, 0.3); margin-top: 24px;"
        onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 6px 20px rgba(131, 8, 102, 0.4)';"
        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(131, 8, 102, 0.3)';"
        onmousedown="this.style.transform='scale(0.98)';"
        onmouseup="this.style.transform='scale(1.02)';"
    >
        {{ __('Create Account') }}
    </button>
</form>

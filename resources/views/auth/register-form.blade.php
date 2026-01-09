<form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-5">
    @csrf

    @if($errors->any())
        <div class="bg-red-500/20 border border-red-500/50 text-red-200 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
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
            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="Enter your full name"
        >
        @error('name')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
            {{ __('Username') }}
        </label>
        <input 
            id="username" 
            type="text" 
            name="username" 
            value="{{ old('username') }}" 
            required 
            autocomplete="username"
            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="Choose a username"
        >
        @error('username')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
            {{ __('Email Address') }}
        </label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autocomplete="email"
            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="Enter your email"
        >
        @error('email')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
            {{ __('Password') }}
        </label>
        <input 
            id="password" 
            type="password" 
            name="password" 
            required 
            autocomplete="new-password"
            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="Create a password"
        >
        @error('password')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password-confirm" class="block text-sm font-medium text-gray-300 mb-2">
            {{ __('Confirm Password') }}
        </label>
        <input 
            id="password-confirm" 
            type="password" 
            name="password_confirmation" 
            required 
            autocomplete="new-password"
            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="Confirm your password"
        >
        @error('password_confirmation')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input 
                id="tosAgree" 
                name="terms" 
                type="checkbox" 
                value="1" 
                required
                class="h-4 w-4 text-pink-500 focus:ring-pink-500 border-gray-300 rounded"
            >
        </div>
        <label for="tosAgree" class="ml-3 text-sm text-gray-300">
            <span>{{ __('I agree to the') }} <a href="{{route('pages.get',['slug'=>GenericHelper::getTOSPage()->slug])}}" class="text-pink-500 hover:text-pink-400">{{ __('Terms of Use') }}</a> {{ __('and') }} <a href="{{route('pages.get',['slug'=>GenericHelper::getPrivacyPage()->slug])}}" class="text-pink-500 hover:text-pink-400">{{ __('Privacy Policy') }}</a>.</span>
        </label>
        @error('terms')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    @if(getSetting('security.recaptcha_enabled') && !Auth::check())
        <div class="flex justify-center">
            {!! NoCaptcha::display(['data-theme' => (Cookie::get('app_theme') == null ? (getSetting('site.default_user_theme')) : Cookie::get('app_theme') )]) !!}
            @error('g-recaptcha-response')
                <p class="mt-1 text-sm text-red-400">{{__("Please check the captcha field.")}}</p>
            @enderror
        </div>
    @endif

    <button 
        type="submit" 
        class="w-full bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-pink-500/30 mt-6"
    >
        {{ __('Create Account') }}
    </button>
</form>

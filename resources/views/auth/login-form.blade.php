<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf
    
    @if(session('message'))
        <div class="bg-pink-500/20 border border-pink-500/50 text-pink-200 px-4 py-3 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

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
        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
            {{ __('Email Address') }}
        </label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            autocomplete="email" 
            autofocus
            required
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
            autocomplete="current-password"
            required
            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="Enter your password"
        >
        @error('password')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input 
                id="remember" 
                name="remember" 
                type="checkbox" 
                class="h-4 w-4 text-pink-500 focus:ring-pink-500 border-gray-300 rounded"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-300">
                {{ __('Remember me') }}
            </label>
        </div>
        @if (Route::has('password.request'))
            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="text-pink-500 hover:text-pink-400 font-medium transition-colors">
                    {{ __('Forgot Password?') }}
                </a>
            </div>
        @endif
    </div>

    <button 
        type="submit" 
        class="w-full bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-pink-500/30"
    >
        {{ __('Sign In') }}
    </button>
</form>

@if(getSetting('social-login.facebook_client_id') || getSetting('social-login.twitter_client_id') || getSetting('social-login.google_client_id'))
    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/20"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white/10 text-gray-400">{{__("Or continue with")}}</span>
            </div>
        </div>

        <div class="mt-6 space-y-3">
            @if(getSetting('social-login.facebook_client_id'))
                <a href="{{url('',['socialAuth','facebook'])}}" rel="nofollow" class="w-full flex items-center justify-center px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white hover:bg-white/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                    <img src="{{asset('/img/logos/facebook-logo.svg')}}" class="h-5 w-5 mr-3"/>
                    <span class="font-medium">{{__("Sign in with")}} {{__("Facebook")}}</span>
                </a>
            @endif

            @if(getSetting('social-login.twitter_client_id'))
                <a href="{{url('',['socialAuth','twitter'])}}" rel="nofollow" class="w-full flex items-center justify-center px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white hover:bg-white/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                    <img src="{{asset('/img/logos/twitter-logo.svg')}}" class="h-5 w-5 mr-3"/>
                    <span class="font-medium">{{__("Sign in with")}} {{__("Twitter")}}</span>
                </a>
            @endif

            @if(getSetting('social-login.google_client_id'))
                <a href="{{url('',['socialAuth','google'])}}" rel="nofollow" class="w-full flex items-center justify-center px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white hover:bg-white/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                    <img src="{{asset('/img/logos/google-logo.svg')}}" class="h-5 w-5 mr-3"/>
                    <span class="font-medium">{{__("Sign in with")}} {{__("Google")}}</span>
                </a>
            @endif
        </div>
    </div>
@endif

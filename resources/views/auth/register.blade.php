@extends('layouts.no-nav')
@section('page_title', __('Register'))

@section('page_description', getSetting('site.description'))
@section('share_url', route('home'))
@section('share_title', getSetting('site.name') . ' - ' .  __('Register'))
@section('share_description', getSetting('site.description'))
@section('share_type', 'article')
@section('share_img', GenericHelper::getOGMetaImage())

@if(getSetting('security.recaptcha_enabled') && !Auth::check())
    @section('meta')
        {!! NoCaptcha::renderJs() !!}
    @stop
@endif

@section('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
@stop

@section('content')
<div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black flex items-center justify-center p-4 py-8">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <a href="{{route('home')}}" class="inline-block">
                <img class="h-12 mx-auto mb-4" src="{{asset( (Cookie::get('app_theme') == null ? (getSetting('site.default_user_theme') == 'dark' ? getSetting('site.dark_logo') : getSetting('site.light_logo')) : (Cookie::get('app_theme') == 'dark' ? getSetting('site.dark_logo') : getSetting('site.light_logo'))) )}}">
            </a>
            <h1 class="text-3xl font-bold text-white mb-2">Create Account</h1>
            <p class="text-gray-400">Join us and start creating amazing content</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20">
            @include('auth.register-form')
            @include('auth.social-login-box')
        </div>

        <!-- Footer Links -->
        <div class="text-center mt-6">
            <p class="text-gray-400 text-sm">
                Already have an account? 
                <a href="{{route('login')}}" class="text-pink-500 hover:text-pink-400 font-semibold transition-colors">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection

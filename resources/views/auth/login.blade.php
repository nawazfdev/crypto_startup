@extends('layouts.no-nav')
@section('page_title', __('Login'))

@section('page_description', getSetting('site.description'))
@section('share_url', route('home'))
@section('share_title', getSetting('site.name') . ' - ' .  __('Login'))
@section('share_description', getSetting('site.description'))
@section('share_type', 'article')
@section('share_img', GenericHelper::getOGMetaImage())

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
<div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <a href="{{route('home')}}" class="inline-block">
                <img class="h-12 mx-auto mb-4" src="{{asset( (Cookie::get('app_theme') == null ? (getSetting('site.default_user_theme') == 'dark' ? getSetting('site.dark_logo') : getSetting('site.light_logo')) : (Cookie::get('app_theme') == 'dark' ? getSetting('site.dark_logo') : getSetting('site.light_logo'))) )}}">
            </a>
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-gray-400">Sign in to continue to your account</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20">
            @include('auth.login-form')
            @include('auth.social-login-box')
        </div>

        <!-- Footer Links -->
        <div class="text-center mt-6">
            <p class="text-gray-400 text-sm">
                Don't have an account? 
                <a href="{{route('register')}}" class="text-pink-500 hover:text-pink-400 font-semibold transition-colors">Sign up</a>
            </p>
        </div>
    </div>
</div>
@endsection

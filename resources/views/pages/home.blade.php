@extends('layouts.generic')

@section('page_description', getSetting('site.description'))
@section('share_url', route('home'))
@section('share_title', getSetting('site.name') . ' - ' . getSetting('site.slogan'))
@section('share_description', getSetting('site.description'))
@section('share_type', 'article')
@section('share_img', GenericHelper::getOGMetaImage())

@section('styles')
    {!!
        Minify::stylesheet([
            '/libs/swiper/swiper-bundle.min.css',
            '/libs/photoswipe/dist/photoswipe.css',
            '/css/pages/checkout.css',
            '/libs/photoswipe/dist/default-skin/default-skin.css',
            '/css/pages/feed.css',
            '/css/posts/post.css',
            '/css/pages/search.css',
         ])->withFullUrl()
    !!}
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        /* Hide header on home page - TikTok style */
        header, .header, .navbar, nav.navbar, .site-header, footer, .footer {
            display: none !important;
        }
        
        body {
            overflow: hidden !important;
            background-color: #000 !important;
            user-select: none;
            -webkit-user-select: none;
            -webkit-touch-callout: none;
        }
        
        /* Ensure full height for reels */
        html, body {
            height: 100vh !important;
            height: 100dvh !important;
            overflow: hidden !important;
        }
        
        .reels-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            height: 100dvh;
            background: #000;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .video-feed {
            width: 100%;
            max-width: 692px;
            height: 100vh;
            height: 100dvh;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            margin: 0 auto;
        }
        
        .video-feed::-webkit-scrollbar {
            display: none;
        }
        
        .video-item {
            width: 100%;
            height: 100vh;
            height: 100dvh;
            position: relative;
            scroll-snap-align: start;
            scroll-snap-stop: always;
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 692px;
            margin: 0 auto;
        }
        
        .video-wrapper {
            width: 100%;
            height: 100%;
            max-width: 692px;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }
        
        .video-player {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            cursor: pointer;
            background: #000;
            max-width: 692px;
        }
        
        .video-overlay {
            position: absolute;
            bottom: 60px;
            left: 0;
            right: 0;
            padding: 20px 20px 20px 10px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: #fff;
            z-index: 10;
        }
        
        .video-info {
            max-width: 70%;
            text-align: left;
        }
        
        /* Right Action Buttons (TikTok style) */
        .video-actions-left {
            position: absolute;
            right: 20px;
            bottom: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            z-index: 15;
        }
        
        /* Right Navigation Controls */
        .video-navigation-right {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            z-index: 15;
        }
        
        .nav-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.1);
        }
        
        .nav-btn:active {
            transform: scale(0.95);
        }
        
        .user-info {
            display: inline-flex;
            align-items: center;
            margin-bottom: 18px;
            gap: 14px;
        }
        
        .user-info.clickable {
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 10px 14px 10px 10px;
            border-radius: 14px;
            margin: -10px -14px 8px -10px;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(8px);
        }
        
        .user-info.clickable:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.03);
        }
        
        .user-avatar-initials {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 3px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            transition: transform 0.2s ease;
        }
        
        .user-info.clickable:hover .user-avatar-initials {
            transform: scale(1.08);
        }
        
        .user-details h4 {
            margin: 0 0 4px 0;
            font-size: 17px;
            font-weight: 700;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
            letter-spacing: 0.2px;
        }
        
        .user-details p {
            margin: 0;
            font-size: 14px;
            opacity: 0.85;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        .video-caption {
            margin-top: 8px;
            text-align: left;
        }
        
        .video-title {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.3;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            text-align: left;
        }
        
        .video-description {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.4;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .video-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            padding-bottom: 0;
        }
        
        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        
        .action-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .action-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }
        
        .action-btn:active {
            transform: scale(0.95);
        }
        
        .like-btn.liked {
            background: rgba(255, 23, 68, 0.3);
            color: #ff1744;
            border-color: #ff1744;
        }
        
        .repost-btn.reposted {
            background: rgba(23, 191, 99, 0.3);
            color: #17bf63;
            border-color: #17bf63;
        }
        
        .upload-btn {
            background: rgba(0, 123, 255, 0.2);
            color: #007bff;
            border-color: #007bff;
        }
        
        .action-count {
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.7);
            min-height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 4px;
        }
        
        .progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            z-index: 15;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, #ff6b6b, #ff8a80);
            width: 0%;
            transition: width 0.1s linear;
        }
        
        /* Comments Sidebar */
        .comments-sidebar {
            position: fixed;
            top: 0;
            right: -25%;
            width: 25%;
            height: 100vh;
            background: #fff;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1002;
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
            min-width: 320px;
            opacity: 0;
            visibility: hidden;
            transform: translateX(100%);
            overflow: hidden;
        }
        
        .comments-sidebar.active {
            right: 0;
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }
        
        .comments-sidebar.active .comment-form {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Comments Overlay Background */
        .comments-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .comments-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .comments-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            position: relative;
        }
        
        .comments-header::before {
            content: '';
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            background: #ddd;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .comments-header h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }
        
        .close-comments {
            background: rgba(0, 0, 0, 0.05);
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #666;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-comments:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #333;
            transform: scale(1.05);
        }
        
        .comments-list {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 20px;
            min-height: 0;
            max-height: 100%;
        }
        
        .no-comments {
            text-align: center;
            color: #888;
            padding: 40px 20px;
        }
        
        .loading {
            text-align: center;
            color: #888;
            padding: 40px 20px;
        }
        
        .comment-item {
            display: flex;
            margin-bottom: 16px;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.02);
            transition: background-color 0.3s ease;
        }
        
        .comment-item:hover {
            background: rgba(0, 0, 0, 0.05);
        }
        
        .comment-avatar-initials {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 13px;
            color: white;
            flex-shrink: 0;
            border: 2px solid #e9ecef;
        }
        
        .comment-content {
            flex: 1;
        }
        
        .comment-content strong {
            color: #333;
            font-size: 14px;
            font-weight: 600;
        }
        
        .comment-content p {
            margin: 4px 0;
            color: #333;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .comment-time {
            color: #666;
            font-size: 12px;
        }
        
        .comment-form {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 12px;
            background: #fff;
            position: relative;
            bottom: 0;
            z-index: 10;
            flex-shrink: 0;
            margin-top: auto;
        }
        
        .comment-form input {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            background: #fff;
            display: block;
            visibility: visible;
        }
        
        .comment-form input:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
        }
        
        .comment-form button {
            background: linear-gradient(135deg, #ff6b6b, #ff8a80);
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 12px 24px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
            display: block;
            visibility: visible;
        }
        
        .comment-form button:hover:not(:disabled) {
            background: linear-gradient(135deg, #ff5252, #ff6b6b);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(255, 82, 82, 0.4);
        }
        
        .comment-form button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .no-videos {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #fff;
            text-align: center;
            padding: 40px;
        }
        
        .no-videos i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .no-videos h3 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        
        .no-videos p {
            opacity: 0.7;
            margin-bottom: 30px;
        }
        
        .upload-first-video-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #ff6b6b, #ff8a80);
            color: #fff;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
        }
        
        .upload-first-video-btn:hover {
            background: linear-gradient(135deg, #ff5252, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(255, 82, 82, 0.4);
            color: #fff;
        }
        
        .upload-first-video-btn i {
            font-size: 18px;
        }
        
        /* Desktop - Centered like TikTok */
        @media (min-width: 769px) {
            .video-feed {
                max-width: 692px;
            }
            
            .video-item {
                max-width: 692px;
            }
            
            .video-wrapper {
                max-width: 692px;
            }
            
            .video-player {
                max-width: 692px;
            }
        }
        
        /* Mobile Optimizations - Full Screen */
        @media (max-width: 768px) {
            .reels-container {
                justify-content: flex-start;
            }
            
            .video-feed {
                max-width: 100%;
                width: 100%;
            }
            
            .video-item {
                max-width: 100%;
            }
            
            .video-wrapper {
                max-width: 100%;
            }
            
            .video-player {
                max-width: 100%;
            }
            
            /* Comments as bottom sheet on mobile */
            .comments-sidebar {
                top: auto;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                height: 85vh;
                max-height: 85vh;
                min-width: unset;
                border-radius: 20px 20px 0 0;
                transform: translateY(100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            }
            
            .comments-sidebar.active {
                transform: translateY(0);
                right: auto;
            }
            
            .video-overlay {
                padding: 15px 15px 15px 8px;
                bottom: 55px;
            }
            
            .video-info {
                max-width: calc(100% - 70px);
                text-align: left;
            }
            
            .video-actions-left {
                bottom: 140px;
                right: 15px;
            }
            
            .user-avatar-initials {
                width: 48px;
                height: 48px;
                font-size: 16px;
            }
            
            .user-details h4 {
                font-size: 16px;
            }
            
            .user-details p {
                font-size: 13px;
            }
            
            .video-title {
                font-size: 15px;
            }
            
            .video-description {
                font-size: 13px;
            }
            
            .action-btn {
                width: 48px;
                height: 48px;
                font-size: 18px;
            }
            
            .video-actions {
                gap: 12px;
                padding-bottom: 0;
            }
            
            .video-actions-left {
                bottom: 80px;
                gap: 15px;
            }
            
            .video-navigation-right {
                right: 10px;
                gap: 15px;
            }
            
            .nav-btn {
                width: 44px;
                height: 44px;
                font-size: 20px;
            }
            
            .action-count {
                font-size: 11px;
            }
            
            /* Mobile comments header with drag handle */
            .comments-header::before {
                width: 40px;
                height: 4px;
            }
            
            .comments-list {
                max-height: calc(85vh - 200px);
                overflow-y: auto;
            }
            
            /* Mobile comment form - ensure it's always visible */
            .comment-form {
                padding: 16px 20px;
                padding-bottom: calc(24px + env(safe-area-inset-bottom, 16px));
                position: sticky !important;
                bottom: 0;
                background: #fff;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                margin-bottom: 0;
                margin-top: 0;
            }
            
            .comment-form input {
                padding: 12px 16px;
                font-size: 15px;
            }
            
            .comment-form button {
                padding: 12px 24px;
                font-size: 14px;
                white-space: nowrap;
            }
        }
        
        /* Tablet and Desktop - hide overlay, use sidebar */
        @media (min-width: 769px) {
            .comments-overlay {
                display: none;
            }
            
            /* Ensure sidebar and form are visible on desktop */
            .comments-sidebar {
                display: flex !important;
                flex-direction: column;
                overflow: hidden;
            }
            
            .comments-header {
                flex-shrink: 0;
                flex-grow: 0;
            }
            
            .comments-list {
                flex: 1;
                overflow-y: auto;
                min-height: 0;
                max-height: calc(100vh - 200px);
            }
            
            /* Ensure comment form is visible on desktop */
            .comment-form {
                display: flex !important;
                position: relative !important;
                bottom: auto !important;
                background: #fff;
                flex-shrink: 0 !important;
                flex-grow: 0 !important;
                padding: 20px;
                border-top: 1px solid #eee;
                visibility: visible !important;
                opacity: 1 !important;
                height: auto;
                min-height: 80px;
                margin-top: auto;
                gap: 12px;
            }
            
            .comment-form input {
                flex: 1;
                padding: 12px 18px;
                border: 2px solid #e9ecef;
                border-radius: 25px;
                font-size: 14px;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                background: #fff;
            }
            
            .comment-form button {
                padding: 12px 24px;
                background: linear-gradient(135deg, #ff6b6b, #ff8a80) !important;
                color: #fff !important;
                border: none;
                border-radius: 25px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 600;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
            }
            
            .comment-form button:hover {
                background: linear-gradient(135deg, #ff5252, #ff6b6b) !important;
                transform: translateY(-1px);
                box-shadow: 0 6px 16px rgba(255, 82, 82, 0.4);
            }
        }
        
        /* Tablet responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .comments-sidebar {
                width: 35%;
                right: -35%;
                min-width: 350px;
            }
        }
        
        /* Top Header Styles */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1002;
            padding: 15px 20px;
            background: linear-gradient(rgba(0, 0, 0, 0.6), transparent);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-menu-btn,
        .search-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header-menu-btn:hover,
        .search-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }
        
        /* Bottom Modal Styles */
        .header-modal,
        .search-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1003;
            background: rgba(0, 0, 0, 0.7);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .header-modal.active,
        .search-modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .header-modal-content,
        .search-modal-content {
            background: #fff;
            border-radius: 20px 20px 0 0;
            padding: 0;
            width: 100%;
            max-height: 70vh;
            overflow-y: auto;
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
            bottom: 0;
        }
        
        .header-modal.active .header-modal-content,
        .search-modal.active .search-modal-content {
            transform: translateY(0);
        }
        
        .header-modal-header,
        .search-modal-header {
            padding: 20px 20px 16px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .header-modal-header::before,
        .search-modal-header::before {
            content: '';
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
        }
        
        .header-modal-header h3,
        .search-modal-header h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }
        
        .header-modal-close,
        .search-modal-close {
            background: rgba(0, 0, 0, 0.05);
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #666;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header-modal-close:hover,
        .search-modal-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #333;
            transform: scale(1.05);
        }
        
        /* Header Menu Items */
        .header-menu-items {
            padding: 20px;
            background: #fff;
        }
        
        .header-menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            text-decoration: none;
            color: #333;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-bottom: 12px;
            font-size: 16px;
            font-weight: 500;
            border: 1px solid #f0f0f0;
        }
        
        .header-menu-item:hover {
            background: linear-gradient(135deg, #ff6b6b, #ff8a80);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }
        
        .header-menu-item i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }
        
        /* Search Modal Styles */
        .search-input-container {
            position: relative;
            padding: 20px;
            background: #fff;
        }
        
        .search-input-container input {
            width: 100%;
            padding: 16px 50px 16px 20px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .search-input-container input:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
            background: #fff;
        }
        
        .search-icon {
            position: absolute;
            right: 35px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 18px;
        }
        
        .search-results {
            max-height: 400px;
            overflow-y: auto;
            padding: 0 20px 20px;
            background: #fff;
        }
        
        .search-loading,
        .no-search-results,
        .search-placeholder {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-size: 14px;
        }
        
        .search-result-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 10px;
            border: 1px solid #f0f0f0;
        }
        
        .search-result-item:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .search-result-info {
            flex: 1;
        }
        
        .search-result-info h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .search-result-info p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }
        
        /* Bottom Navigation Bar */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px 5px 8px 5px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1001;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
        }
        
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
            padding: 6px 8px;
            border-radius: 12px;
            flex: 1;
            max-width: 80px;
        }
        
        .bottom-nav-item.active {
            color: #fff;
        }
        
        .bottom-nav-item:hover {
            color: #fff;
            transform: translateY(-2px);
        }
        
        .bottom-nav-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }
        
        .bottom-nav-text {
            font-size: 10px;
            font-weight: 500;
        }
        
        /* Mobile adjustments */
        @media (max-width: 768px) {
            .user-avatar-initials {
                width: 44px;
                height: 44px;
                font-size: 15px;
                border-width: 2px;
            }
            
            .user-details h4 {
                font-size: 15px;
            }
            
            .user-details p {
                font-size: 12px;
            }
            
            .user-info {
                gap: 12px;
            }
            
            .user-info.clickable {
                padding: 8px 12px 8px 8px;
                margin: -8px -12px 6px -8px;
            }
            
            .top-header {
                padding: 12px 15px;
            }
            
            .header-menu-btn,
            .search-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .bottom-nav {
                padding: 6px 5px 6px 5px;
            }
            
            .bottom-nav-icon {
                font-size: 18px;
                margin-bottom: 3px;
            }
            
            .bottom-nav-text {
                font-size: 9px;
            }
        }
    </style>
@stop

@section('content')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Top Left Menu Button -->
    <div class="top-header">
        <button id="header-menu-btn" class="header-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
        <button id="search-btn" class="search-btn">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <!-- Header Menu Modal -->
    <div class="header-modal" id="header-modal">
        <div class="header-modal-content">
            <div class="header-modal-header">
                <h3>Menu</h3>
                <button class="header-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="header-menu-items">
                <a href="/" class="header-menu-item">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                @auth
                    <a href="/{{ Auth::user()->username ?? 'profile' }}" class="header-menu-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('creator.dashboard') }}" class="header-menu-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Creator Dashboard</span>
                    </a>
                    <a href="/create" class="header-menu-item">
                        <i class="fas fa-video"></i>
                        <span>Create Video</span>
                    </a>
                    <a href="{{ route('cryptocurrency.wallet') }}" class="header-menu-item">
                        <i class="fas fa-wallet"></i>
                        <span>Wallet</span>
                    </a>
                    <a href="{{ route('cryptocurrency.marketplace') }}" class="header-menu-item">
                        <i class="fas fa-store"></i>
                        <span>Marketplace</span>
                    </a>
                    <a href="{{ route('my.settings') }}" class="header-menu-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('logout') }}" class="header-menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="header-menu-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="header-menu-item">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Search Modal -->
    <div class="search-modal" id="search-modal">
        <div class="search-modal-content">
            <div class="search-modal-header">
                <h3>Search Videos</h3>
                <button class="search-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="search-input-container">
                <input type="text" id="search-input" placeholder="Search for videos..." autocomplete="off">
                <i class="fas fa-search search-icon"></i>
            </div>
            <div class="search-results" id="search-results">
                <div class="search-placeholder">Type at least 3 characters to search...</div>
            </div>
        </div>
    </div>

    <div class="reels-container">
        <div class="video-feed">
            @if($videos && $videos->count() > 0)
                @foreach($videos as $video)
                    <div class="video-item" data-video-id="{{ $video->id }}">
                        <div class="video-wrapper">
                            <video 
                                src="{{ $video->video_url }}"
                                loop
                                muted
                                playsinline
                                preload="metadata"
                                class="video-player"
                                webkit-playsinline
                            ></video>
                            
                            <!-- Left Action Buttons -->
                            <div class="video-actions-left">
                                <div class="action-item">
                                    <button class="action-btn like-btn @if($video->is_liked) liked @endif" data-video-id="{{ $video->id }}">
                                        <i class="@if($video->is_liked) fas @else far @endif fa-heart"></i>
                                    </button>
                                    <span class="action-count">{{ $video->likes_count }}</span>
                                </div>
                                
                                <div class="action-item">
                                    <button class="action-btn comment-btn" data-video-id="{{ $video->id }}">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                    <span class="action-count">{{ $video->comments_count }}</span>
                                </div>
                                
                                <div class="action-item">
                                    <button class="action-btn share-btn" data-video-id="{{ $video->id }}">
                                        <i class="fas fa-share"></i>
                                    </button>
                                    <span class="action-count">{{ $video->shares_count }}</span>
                                </div>
                                
                                <div class="action-item">
                                    <button class="action-btn repost-btn @if($video->is_reposted) reposted @endif" data-video-id="{{ $video->id }}">
                                        <i class="fas fa-retweet"></i>
                                    </button>
                                    <span class="action-count">{{ $video->reposts_count }}</span>
                                </div>
                                
                                <div class="action-item">
                                    <button class="action-btn upload-btn" onclick="handleUploadClick()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Right Navigation Controls -->
                            <div class="video-navigation-right">
                                <button class="nav-btn nav-up-btn" onclick="scrollToPreviousVideo()">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                                <button class="nav-btn nav-down-btn" onclick="scrollToNextVideo()">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            
                            <!-- Bottom Video Info -->
                            <div class="video-overlay">
                                <div class="video-info">
                                    <div class="user-info clickable" data-user-id="{{ $video->user->id }}" data-user-username="{{ $video->user->username ?? strtolower(str_replace(' ', '', $video->user->name)) }}">
                                        <div class="user-avatar-initials" style="background: {{ ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'][rand(0,4)] }};">
                                            {{ strtoupper(substr($video->user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $video->user->name)[1] ?? '', 0, 1)) }}
                                        </div>
                                        <div class="user-details">
                                            <h4>{{ $video->user->name }}</h4>
                                            <p>{{ '@' . ($video->user->username ?? strtolower(str_replace(' ', '', $video->user->name))) }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="video-caption">
                                        <p class="video-title">{{ $video->title }}</p>
                                        @if($video->description)
                                            <p class="video-description">{{ $video->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="progress-bar">
                                <div class="progress"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-videos">
                    <i class="fas fa-video"></i>
                    <h3>No videos available</h3>
                    <p>Be the first to share a video!</p>
                    <a href="/videos/create" class="upload-first-video-btn">
                        <i class="fas fa-plus"></i>
                        Upload Your First Video
                    </a>
                </div>
            @endif
        </div>

        <!-- Comments Overlay -->
        <div class="comments-overlay" id="comments-overlay"></div>
        
        <!-- Comments Sidebar -->
        <div class="comments-sidebar" id="comments-sidebar">
            <div class="comments-header">
                <h3>Comments</h3>
                <button class="close-comments">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="comments-list" id="comments-list">
                <div class="no-comments">
                    <p>No comments yet. Be the first to comment!</p>
                </div>
            </div>
            
            <div class="comment-form">
                <input type="text" id="comment-input" placeholder="Add a comment..." maxlength="500">
                <button id="post-comment">Post</button>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <nav class="bottom-nav">
        <a href="/" class="bottom-nav-item active">
            <i class="fas fa-home bottom-nav-icon"></i>
            <span class="bottom-nav-text">Home</span>
        </a>

        @auth
            <a href="/create" class="bottom-nav-item">
                <i class="fas fa-video bottom-nav-icon"></i>
                <span class="bottom-nav-text">Create</span>
            </a>
            <a href="{{ route('cryptocurrency.wallet') }}" class="bottom-nav-item">
                <i class="fas fa-wallet bottom-nav-icon"></i>
                <span class="bottom-nav-text">Wallet</span>
            </a>
            <a href="{{ route('cryptocurrency.marketplace') }}" class="bottom-nav-item">
                <i class="fas fa-store bottom-nav-icon"></i>
                <span class="bottom-nav-text">Market</span>
            </a>
            <a href="{{ route('profile', ['username' => Auth::user()->username ?? Auth::user()->id]) }}" class="bottom-nav-item">
                <i class="fas fa-user bottom-nav-icon"></i>
                <span class="bottom-nav-text">Profile</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="bottom-nav-item">
                <i class="fas fa-sign-in-alt bottom-nav-icon"></i>
                <span class="bottom-nav-text">Login</span>
            </a>
            <a href="{{ route('register') }}" class="bottom-nav-item">
                <i class="fas fa-user-plus bottom-nav-icon"></i>
                <span class="bottom-nav-text">Register</span>
            </a>
            <a href="{{ route('cryptocurrency.marketplace') }}" class="bottom-nav-item">
                <i class="fas fa-store bottom-nav-icon"></i>
                <span class="bottom-nav-text">Market</span>
            </a>
        @endauth
    </nav>
@stop

@section('scripts')
    {!!
        Minify::javascript([
            '/js/PostsPaginator.js',
            '/js/CommentsPaginator.js',
            '/js/Post.js',
            '/js/SuggestionsSlider.js',
            '/js/pages/lists.js',
            '/js/pages/feed.js',
            '/js/pages/checkout.js',
            '/libs/swiper/swiper-bundle.js',
            '/js/plugins/media/photoswipe.js',
            '/libs/photoswipe/dist/photoswipe-ui-default.min.js',
            '/js/plugins/media/mediaswipe.js',
            '/js/plugins/media/mediaswipe-loader.js',
         ])->withFullUrl()
    !!}
    
    <!-- Reels JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoItems = document.querySelectorAll('.video-item');
            const videoPlayers = document.querySelectorAll('.video-player');
            let currentVideoIndex = 0;
            let isScrolling = false;
            
            // Initialize all videos
            videoPlayers.forEach((video, index) => {
                video.setAttribute('playsinline', '');
                video.setAttribute('webkit-playsinline', '');
                video.muted = true; // Start muted for autoplay
                video.load();
                
                // Click to play/pause
                video.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (this.paused) {
                        this.play();
                    } else {
                        this.pause();
                    }
                });
            });
            
            let viewedVideos = new Set();
            // Intersection Observer for autoplay
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const video = entry.target;
                    if (entry.isIntersecting && entry.intersectionRatio > 0.7) {
                        pauseAllVideosExcept(video);
                        video.currentTime = 0;
                        video.play().catch(e => console.log('Autoplay failed:', e));
                        
                        // Increment views when video starts playing
                        const videoId = video.closest('.video-item').getAttribute('data-video-id');
                        if (videoId) {
                            incrementVideoViews(videoId);
                        }

                        // Update progress bar
                        const progressBar = video.closest('.video-item').querySelector('.progress');
                        if (progressBar) {
                            startProgressBar(video, progressBar);
                        }
                    } else {
                        video.pause();
                    }
                });
            }, {
                threshold: [0.7],
                rootMargin: '-10% 0px'
            });
            
            // Observe all videos
            videoPlayers.forEach(video => observer.observe(video));
            
            function pauseAllVideosExcept(currentVideo) {
                videoPlayers.forEach(video => {
                    if (video !== currentVideo) {
                        video.pause();
                        video.currentTime = 0;
                    }
                });
            }
            
            function startProgressBar(video, progressBar) {
                const updateProgress = () => {
                    if (!video.paused && video.duration > 0) {
                        const progress = (video.currentTime / video.duration) * 100;
                        progressBar.style.width = progress + '%';
                        
                        if (progress < 100 && !video.paused) {
                            requestAnimationFrame(updateProgress);
                        }
                    }
                };
                requestAnimationFrame(updateProgress);
            }
            
            // Like button functionality
            document.querySelectorAll('.like-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    @guest
                        // If user is not logged in, redirect to login
                        if (confirm('Please login to like videos')) {
                            window.location.href = '{{ route("login") }}';
                        }
                        return;
                    @endguest
                    
                    const videoId = this.getAttribute('data-video-id');
                    const countSpan = this.closest('.action-item').querySelector('.action-count');
                    const icon = this.querySelector('i');
                    let count = parseInt(countSpan.textContent) || 0;
                    
                    const wasLiked = this.classList.contains('liked');
                    const button = this;
                    
                    // Optimistic UI update
                    if (!wasLiked) {
                        button.classList.add('liked');
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        countSpan.textContent = count + 1;
                    } else {
                        button.classList.remove('liked');
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        countSpan.textContent = Math.max(0, count - 1);
                    }
                    
                    // Make API call
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        return;
                    }
                    
                    fetch(`/videos/${videoId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('Like response status:', response.status);
                        if (response.status === 401) {
                            // Redirect to login if not authenticated
                            if (confirm('Please login to like videos')) {
                                window.location.href = '{{ route("login") }}';
                            }
                            throw new Error('Authentication required');
                        }
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Like response data:', data);
                        if (data.success) {
                            // Update with real data from server
                            countSpan.textContent = data.likes_count || countSpan.textContent;
                            if (data.is_liked) {
                                button.classList.add('liked');
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                            } else {
                                button.classList.remove('liked');
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                            }
                            
                            // Show notification if using fallback
                            if (data.note) {
                                console.warn('Using fallback data:', data.note);
                            }
                        } else {
                            // Revert UI on failure
                            if (wasLiked) {
                                button.classList.add('liked');
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                                countSpan.textContent = count;
                            } else {
                                button.classList.remove('liked');
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                                countSpan.textContent = count;
                            }
                            console.error('Like failed:', data.message || data.error || 'Unknown error');
                        }
                    })
                    .catch(error => {
                        console.error('Error liking video:', error);
                        // Don't revert UI for authentication errors
                        if (error.message === 'Authentication required') {
                            return;
                        }
                        // Revert UI on other errors
                        if (wasLiked) {
                            button.classList.add('liked');
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            countSpan.textContent = count;
                        } else {
                            button.classList.remove('liked');
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            countSpan.textContent = count;
                        }
                    });
                });
            });
            
            // Comments sidebar functionality
            const commentsSidebar = document.getElementById('comments-sidebar');
            const commentsOverlay = document.getElementById('comments-overlay');
            const closeCommentsBtn = document.querySelector('.close-comments');
            
            function openComments() {
                commentsSidebar.classList.add('active');
                if (commentsOverlay) {
                    commentsOverlay.classList.add('active');
                }
            }
            
            function closeComments() {
                commentsSidebar.classList.remove('active');
                if (commentsOverlay) {
                    commentsOverlay.classList.remove('active');
                }
            }
            
            document.querySelectorAll('.comment-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openComments();
                    const videoId = this.getAttribute('data-video-id');
                    loadComments(videoId);
                });
            });
            
            closeCommentsBtn.addEventListener('click', function() {
                closeComments();
            });
            
            // Close comments when clicking on overlay
            if (commentsOverlay) {
                commentsOverlay.addEventListener('click', function() {
                    closeComments();
                });
            }
            
            // Share button functionality
            document.querySelectorAll('.share-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const videoId = this.getAttribute('data-video-id');
                    const countSpan = this.closest('.action-item').querySelector('.action-count');
                    let count = parseInt(countSpan.textContent);
                    countSpan.textContent = count + 1;
                    
                    fetch(`/videos/${videoId}/share`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            countSpan.textContent = data.shares_count;
                        }
                    })
                    .catch(error => console.error('Error:', error));
                    
                    const videoUrl = `${window.location.origin}/videos/${videoId}`;
                    if (navigator.share) {
                        navigator.share({
                            title: 'Check out this video!',
                            url: videoUrl
                        });
                    } else {
                        navigator.clipboard.writeText(videoUrl);
                    }
                });
            });
            
            // Repost button functionality
            document.querySelectorAll('.repost-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const videoId = this.getAttribute('data-video-id');
                    const countSpan = this.closest('.action-item').querySelector('.action-count');
                    const wasReposted = this.classList.contains('reposted');
                    let count = parseInt(countSpan.textContent);
                    
                    // Toggle UI immediately
                    if (!wasReposted) {
                        this.classList.add('reposted');
                        this.style.color = '#17bf63';
                        countSpan.textContent = count + 1;
                    } else {
                        this.classList.remove('reposted');
                        this.style.color = '#fff';
                        countSpan.textContent = Math.max(0, count - 1);
                    }
                    
                    // Make API call
                    fetch(`/videos/${videoId}/repost`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            countSpan.textContent = data.reposts_count;
                            if (data.is_reposted) {
                                this.classList.add('reposted');
                                this.style.color = '#17bf63';
                            } else {
                                this.classList.remove('reposted');
                                this.style.color = '#fff';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert UI on error
                        if (wasReposted) {
                            this.classList.add('reposted');
                            this.style.color = '#17bf63';
                            countSpan.textContent = count;
                        } else {
                            this.classList.remove('reposted');
                            this.style.color = '#fff';
                            countSpan.textContent = count;
                        }
                    });
                });
            });
            
            // Comments functionality
            function loadComments(videoId) {
                const commentsList = document.getElementById('comments-list');
                if (!commentsList) return;
                
                commentsList.innerHTML = '<div class="loading">Loading comments...</div>';
                
                fetch(`/videos/${videoId}/comments`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Comments API Response:', data);
                    
                    // Check if data.success exists and is true
                    if (data.success !== false) {
                        // Get comments array - could be data.comments or data.data.comments
                        let comments = data.comments || data.data?.comments || [];
                        
                        // If comments is not an array, try to convert it
                        if (!Array.isArray(comments)) {
                            if (typeof comments === 'object' && comments !== null) {
                                comments = Object.values(comments);
                            } else {
                                comments = [];
                            }
                        }
                        
                        console.log('Parsed comments array:', comments);
                        console.log('Comments count:', comments.length);
                        
                        if (comments && comments.length > 0) {
                            const commentsHtml = comments.map(comment => {
                                // Handle different comment structures
                                const user = comment.user || {};
                                const userName = user.name || comment.user_name || 'User';
                                const userUsername = user.username || comment.user_username || 'user';
                                const commentContent = comment.content || '';
                                // Backend returns created_at as "2 hours ago" format, so use it directly
                                const createdAt = comment.created_at || 'Just now';
                                
                                return `
                                    <div class="comment-item">
                                        <div class="comment-avatar-initials" style="background: ${getRandomColor()};">${getUserInitials(userName)}</div>
                                        <div class="comment-content">
                                            <strong>${userName}</strong>
                                            <p>${commentContent}</p>
                                            <span class="comment-time">${createdAt}</span>
                                        </div>
                                    </div>
                                `;
                            }).join('');
                            commentsList.innerHTML = commentsHtml;
                        } else {
                            commentsList.innerHTML = '<div class="no-comments"><p>No comments yet. Be the first to comment!</p></div>';
                        }
                    } else {
                        // If success is false, show no comments
                        commentsList.innerHTML = '<div class="no-comments"><p>No comments yet. Be the first to comment!</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    commentsList.innerHTML = '<div class="no-comments"><p>No comments yet. Be the first to comment!</p></div>';
                });
            }
            
            // Post comment functionality
            const commentInput = document.getElementById('comment-input');
            const postCommentBtn = document.getElementById('post-comment');
            let currentVideoIdForComments = null;
            
            document.querySelectorAll('.comment-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    currentVideoIdForComments = this.getAttribute('data-video-id');
                });
            });
            
            if (commentInput && postCommentBtn) {
                postCommentBtn.addEventListener('click', function() {
                    const comment = commentInput.value.trim();
                    if (comment && currentVideoIdForComments) {
                        postCommentBtn.disabled = true;
                        postCommentBtn.textContent = 'Posting...';
                        
                        fetch(`/videos/${currentVideoIdForComments}/comments`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                content: comment
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loadComments(currentVideoIdForComments);
                                commentInput.value = '';
                                
                                const currentVideo = document.querySelector(`.video-item[data-video-id="${currentVideoIdForComments}"]`);
                                const commentCountSpan = currentVideo.querySelector('.comment-btn').closest('.action-item').querySelector('.action-count');
                                if (commentCountSpan) {
                                    const count = parseInt(commentCountSpan.textContent);
                                    commentCountSpan.textContent = count + 1;
                                }
                            }
                        })
                        .catch(error => console.error('Error posting comment:', error))
                        .finally(() => {
                            postCommentBtn.disabled = false;
                            postCommentBtn.textContent = 'Post';
                        });
                    }
                });
                
                commentInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        postCommentBtn.click();
                    }
                });
            }
            
            // Helper functions
            function getRandomColor() {
                const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'];
                return colors[Math.floor(Math.random() * colors.length)];
            }
            
            function getUserInitials(name) {
                const parts = name.split(' ');
                return parts.length >= 2 ? 
                    (parts[0][0] + parts[1][0]).toUpperCase() : 
                    name.substring(0, 2).toUpperCase();
            }
            
            function formatTime(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diff = now - date;
                
                const minutes = Math.floor(diff / (1000 * 60));
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                
                if (minutes < 60) {
                    return `${minutes}m ago`;
                } else if (hours < 24) {
                    return `${hours}h ago`;
                } else {
                    return `${days}d ago`;
                }
            }
            
            // Increment video views
            function incrementVideoViews(videoId) {
                fetch(`/videos/${videoId}/views`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Views incremented:', data.views_count);
                    }
                })
                .catch(error => console.error('Error incrementing views:', error));
            }
            
            // Navigation functions
            window.scrollToNextVideo = function() {
                const videoItems = document.querySelectorAll('.video-item');
                if (currentVideoIndex < videoItems.length - 1) {
                    currentVideoIndex++;
                    videoItems[currentVideoIndex].scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            };
            
            window.scrollToPreviousVideo = function() {
                const videoItems = document.querySelectorAll('.video-item');
                if (currentVideoIndex > 0) {
                    currentVideoIndex--;
                    videoItems[currentVideoIndex].scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            };
            
            // Update current video index on scroll
            const videoFeed = document.querySelector('.video-feed');
            if (videoFeed) {
                videoFeed.addEventListener('scroll', function() {
                    const videoItems = document.querySelectorAll('.video-item');
                    videoItems.forEach((item, index) => {
                        const rect = item.getBoundingClientRect();
                        if (rect.top >= 0 && rect.top < window.innerHeight / 2) {
                            currentVideoIndex = index;
                        }
                    });
                });
            }
            
            // Handle upload button click
            window.handleUploadClick = function() {
                @auth
                    window.location.href = '/create';
                @else
                    window.location.href = '{{ route("register") }}';
                @endauth
            };
            
            // Header Menu Modal
            const headerMenuBtn = document.getElementById('header-menu-btn');
            const headerModal = document.getElementById('header-modal');
            const headerModalClose = document.querySelector('.header-modal-close');
            
            if (headerMenuBtn && headerModal) {
                headerMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    headerModal.classList.add('active');
                });
            }
            
            if (headerModalClose) {
                headerModalClose.addEventListener('click', function() {
                    headerModal.classList.remove('active');
                });
            }
            
            // Close menu modal when clicking outside
            if (headerModal) {
                headerModal.addEventListener('click', function(e) {
                    if (e.target === headerModal) {
                        headerModal.classList.remove('active');
                    }
                });
            }
            
            // Search Modal
            const searchBtn = document.getElementById('search-btn');
            const searchModal = document.getElementById('search-modal');
            const searchModalClose = document.querySelector('.search-modal-close');
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');
            
            if (searchBtn && searchModal) {
                searchBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    searchModal.classList.add('active');
                    setTimeout(() => {
                        if (searchInput) searchInput.focus();
                    }, 300);
                });
            }
            
            if (searchModalClose) {
                searchModalClose.addEventListener('click', function() {
                    searchModal.classList.remove('active');
                });
            }
            
            // Close search modal when clicking outside
            if (searchModal) {
                searchModal.addEventListener('click', function(e) {
                    if (e.target === searchModal) {
                        searchModal.classList.remove('active');
                    }
                });
            }
            
            // Search functionality
            if (searchInput && searchResults) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    
                    if (query.length > 2) {
                        searchTimeout = setTimeout(() => {
                            performSearch(query);
                        }, 500);
                    } else {
                        searchResults.innerHTML = '<div class="search-placeholder">Type at least 3 characters to search...</div>';
                    }
                });
            }
            
            function performSearch(query) {
                if (!searchResults) return;
                
                searchResults.innerHTML = '<div class="search-loading">Searching...</div>';
                
                fetch(`/search/videos?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.videos && data.videos.length > 0) {
                        const resultsHtml = data.videos.map(video => `
                            <div class="search-result-item" onclick="goToVideo('${video.id}')">
                                <div class="search-result-info">
                                    <h4>${video.title}</h4>
                                    <p>By ${video.user ? video.user.name : 'Unknown'}</p>
                                </div>
                            </div>
                        `).join('');
                        searchResults.innerHTML = resultsHtml;
                    } else {
                        searchResults.innerHTML = '<div class="no-search-results">No videos found for "' + query + '"</div>';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = '<div class="no-search-results">Search failed. Please try again.</div>';
                });
            }
            
            window.goToVideo = function(videoId) {
                const videoElement = document.querySelector(`.video-item[data-video-id="${videoId}"]`);
                if (videoElement) {
                    videoElement.scrollIntoView({ behavior: 'smooth' });
                    if (searchModal) {
                        searchModal.classList.remove('active');
                    }
                }
            };
            
            // Profile click functionality
            document.querySelectorAll('.user-info.clickable').forEach(userInfo => {
                userInfo.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    
                    const username = this.getAttribute('data-user-username');
                    
                    @auth
                        // If logged in, go to user's profile
                        if (username) {
                            window.location.href = '/' + username;
                        }
                    @else
                        // If not logged in, show login prompt
                        if (confirm('Please login to view profiles')) {
                            window.location.href = '{{ route("login") }}';
                        }
                    @endauth
                });
            });
        });
    </script>
@stop

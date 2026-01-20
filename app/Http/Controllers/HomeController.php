<?php

namespace App\Http\Controllers;

use App\Providers\InstallerServiceProvider;
use App\Providers\MembersHelperServiceProvider;
use App\Models\Feed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use JavaScript;
use Session;

class HomeController extends Controller
{
    /**
     * Homepage > TikTok-style video feed
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {

        // if (!InstallerServiceProvider::checkIfInstalled()) {
        //     return Redirect::to(route('installer.install'));
        // }

        JavaScript::put(['skipDefaultScrollInits' => true]);

        // If there's a custom site index
        if (getSetting('site.homepage_redirect')) {
            return redirect()->to(getSetting('site.homepage_redirect'), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }
        else{
            // Show TikTok-style feed on homepage with ALL FREE videos (no login required)
            // Use EXACT same logic as FeedController
            try {
                $userId = Auth::id(); // Can be null for non-logged-in users
                
                // Try to get real videos from Feed model with detailed error handling
                try {
                    \Log::info('HomeController: Attempting to get videos from Feed model for user: ' . $userId);
                    
                    $videos = Feed::getAllVideos($userId);
                    
                    \Log::info('HomeController: Videos retrieved successfully. Count: ' . $videos->count());
                    
                    // Transform the data to match our view expectations - SAME AS FEED CONTROLLER
                    $videos = $videos->map(function($video) {
                        // Create user object if it doesn't exist
                        if (!isset($video->user)) {
                            $video->user = (object)[
                                'id' => $video->user_id,
                                'name' => $video->user_name ?? 'User',
                                'username' => $video->username ?? $video->user_username ?? strtolower(str_replace(' ', '', $video->user_name ?? 'user'))
                            ];
                        }
                        
                        // Ensure video_url exists (Feed model should set it, but ensure it's there)
                        if (!isset($video->video_url) && isset($video->video_path)) {
                            $video->video_url = asset('storage/' . $video->video_path);
                        }
                        
                        // Ensure all required fields exist
                        $video->likes_count = $video->likes_count ?? 0;
                        $video->comments_count = $video->comments_count ?? 0;
                        $video->shares_count = $video->shares_count ?? 0;
                        $video->reposts_count = $video->reposts_count ?? 0;
                        $video->is_liked = (bool)($video->is_liked ?? false);
                        $video->is_reposted = (bool)($video->is_reposted ?? false);
                        
                        return $video;
                    });
                    
                    // If no videos found, use dummy data
                    if ($videos->count() === 0) {
                        \Log::info('HomeController: No videos found in database, using dummy data');
                        $videos = $this->getDummyVideos();
                    }
                    
                } catch (\Exception $feedError) {
                    // Log the specific error and use dummy data
                    \Log::error('HomeController Feed model error: ' . $feedError->getMessage());
                    \Log::error('HomeController Feed model file: ' . $feedError->getFile() . ' Line: ' . $feedError->getLine());
                    
                    // Check if it's a database table error
                    if (str_contains($feedError->getMessage(), 'Table') && str_contains($feedError->getMessage(), "doesn't exist")) {
                        \Log::error('HomeController: Database table missing. Using dummy data. Error: ' . $feedError->getMessage());
                    }
                    
                    $videos = $this->getDummyVideos();
                }
                
            } catch (\Exception $e) {
                \Log::error('HomeController error: ' . $e->getMessage());
                \Log::error('HomeController file: ' . $e->getFile() . ' Line: ' . $e->getLine());
                
                // Use dummy data on error
                $videos = $this->getDummyVideos();
            }
            
            return view('pages.home', compact('videos'));
        }
    }
    
    /**
     * Get dummy videos for testing (same as FeedController)
     */
    private function getDummyVideos()
    {
        return collect([
            (object)[
                'id' => 1,
                'title' => 'Test Video 1',
                'description' => 'This is a test video description.',
                'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                'likes_count' => 25,
                'comments_count' => 5,
                'shares_count' => 3,
                'reposts_count' => 2,
                'is_liked' => false,
                'is_reposted' => false,
                'user' => (object)[
                    'id' => 1,
                    'name' => 'John Doe',
                    'username' => 'johndoe'
                ]
            ],
            (object)[
                'id' => 2,
                'title' => 'Test Video 2',
                'description' => 'Another test video for the feed.',
                'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                'likes_count' => 42,
                'comments_count' => 8,
                'shares_count' => 6,
                'reposts_count' => 4,
                'is_liked' => true,
                'is_reposted' => false,
                'user' => (object)[
                    'id' => 2,
                    'name' => 'Jane Smith',
                    'username' => 'janesmith'
                ]
            ],
            (object)[
                'id' => 3,
                'title' => 'Amazing Sunset',
                'description' => 'Beautiful sunset timelapse video with amazing colors.',
                'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                'likes_count' => 156,
                'comments_count' => 23,
                'shares_count' => 45,
                'reposts_count' => 12,
                'is_liked' => false,
                'is_reposted' => true,
                'user' => (object)[
                    'id' => 3,
                    'name' => 'Nature Lover',
                    'username' => 'naturelover'
                ]
            ]
        ]);
    }
}

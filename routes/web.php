<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoCommentController;
use App\Http\Controllers\StreamController;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FeedController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\CreatorTokenController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/videos-debug', function() {
    echo 'DEBUG ROUTE HIT!<br>';
    echo 'Method: ' . request()->method() . '<br>';
    echo 'Authenticated: ' . (auth()->check() ? 'YES' : 'NO') . '<br>';
    if (auth()->check()) {
        echo 'User: ' . auth()->user()->name . '<br>';
    }
    return 'Test route working!';
});
// Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin.user']], function () {
    
//     // Token management routes
//     Route::resource('tokens', App\Http\Controllers\Admin\CryptocurrencyController::class, [
//         'names' => [
//             'index' => 'voyager.tokens.index',
//             'create' => 'voyager.tokens.create',
//             'store' => 'voyager.tokens.store',
//             'show' => 'voyager.tokens.show',
//             'edit' => 'voyager.tokens.edit',
//             'update' => 'voyager.tokens.update',
//             'destroy' => 'voyager.tokens.destroy',
//         ]
//     ]);
    
//     // Additional token management actions
//     Route::get('tokens/{id}/toggle-freeze', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleFreeze'
//     ])->name('voyager.tokens.toggle-freeze');
    
//     Route::get('tokens/{id}/toggle-verification', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleVerification'
//     ])->name('voyager.tokens.toggle-verification');
    
//     Route::get('tokens/{id}/toggle-status', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleStatus'
//     ])->name('voyager.tokens.toggle-status');
    
//     Route::post('tokens/{id}/adjust-supply', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'adjustSupply'
//     ])->name('voyager.tokens.adjust-supply');
    
//     // Privacy page routes (if you want to keep them)
//     Route::get('public-pages/privacy/edit', [
//         App\Http\Controllers\Admin\PublicPagesController::class, 'editPrivacy'
//     ])->name('voyager.public-pages.edit-privacy');
    
//     Route::put('public-pages/privacy/update', [
//         App\Http\Controllers\Admin\PublicPagesController::class, 'updatePrivacy'
//     ])->name('voyager.public-pages.update-privacy');
    
//     // Load Voyager routes AFTER your custom routes
//     Voyager::routes();
    
//     // Your existing routes...
//     Route::get('/metrics/new/users/value', [App\Http\Controllers\MetricsController::class, 'newUsersValue'])->name('admin.metrics.new.users.value');
//     Route::get('/metrics/new/users/trend', [App\Http\Controllers\MetricsController::class, 'newUsersTrend'])->name('admin.metrics.new.users.trend');
//     Route::get('/metrics/new/users/partition', [App\Http\Controllers\MetricsController::class, 'newUsersPartition'])->name('admin.metrics.new.users.partition');
//     Route::get('/metrics/subscriptions/value', [App\Http\Controllers\MetricsController::class, 'subscriptionsValue'])->name('admin.metrics.subscriptions.value');
//     Route::get('/metrics/subscriptions/trend', [App\Http\Controllers\MetricsController::class, 'subscriptionsTrend'])->name('admin.metrics.subscriptions.trend');
//     Route::get('/metrics/subscriptions/partition', [App\Http\Controllers\MetricsController::class, 'subscriptionsPartition'])->name('admin.metrics.subscriptions.partition');

//     // Generic routes
//     Route::post('/theme/generate', [App\Http\Controllers\GenericController::class, 'generateCustomTheme'])->name('admin.theme.generate');
//     Route::post('/license/save', [App\Http\Controllers\GenericController::class, 'saveLicense'])->name('admin.license.save');

//     // User management
//     Route::get('/users/{id}/impersonate', [App\Http\Controllers\UserController::class, 'impersonate'])->name('admin.impersonate');
//     Route::get('/leave-impersonation', [App\Http\Controllers\UserController::class, 'leaveImpersonation'])->name('admin.leaveImpersonation');
//     Route::get('/clear-app-cache', [App\Http\Controllers\GenericController::class, 'clearAppCache'])->name('admin.clear.cache');

//     // Withdrawals
//     Route::post('/withdrawals/{withdrawalId}/approve', [App\Http\Controllers\WithdrawalsController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');
//     Route::post('/withdrawals/{withdrawalId}/reject', [App\Http\Controllers\WithdrawalsController::class, 'rejectWithdrawal'])->name('admin.withdrawals.reject');
// });

// Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin.user']], function () {
    
//     // Wallet management routes
//     Route::get('wallets', [
//         App\Http\Controllers\Admin\WalletController::class, 'index'
//     ])->name('voyager.wallets.index');
    
//     Route::get('wallets/{id}', [
//         App\Http\Controllers\Admin\WalletController::class, 'show'
//     ])->name('voyager.wallets.show');
    
//     Route::get('wallets/{id}/toggle-status', [
//         App\Http\Controllers\Admin\WalletController::class, 'toggleStatus'
//     ])->name('voyager.wallets.toggle-status');
    
//     Route::get('wallets/{id}/details', [
//         App\Http\Controllers\Admin\WalletController::class, 'getWalletDetails'
//     ])->name('voyager.wallets.details');
    
//     Route::get('wallets/export/csv', [
//         App\Http\Controllers\Admin\WalletController::class, 'export'
//     ])->name('voyager.wallets.export');
    
//     // Your existing token routes...
//     Route::resource('tokens', App\Http\Controllers\Admin\CryptocurrencyController::class, [
//         'names' => [
//             'index' => 'voyager.tokens.index',
//             'create' => 'voyager.tokens.create',
//             'store' => 'voyager.tokens.store',
//             'show' => 'voyager.tokens.show',
//             'edit' => 'voyager.tokens.edit',
//             'update' => 'voyager.tokens.update',
//             'destroy' => 'voyager.tokens.destroy',
//         ]
//     ]);
    
//     Route::get('tokens/{id}/toggle-freeze', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleFreeze'
//     ])->name('voyager.tokens.toggle-freeze');
    
//     Route::get('tokens/{id}/toggle-verification', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleVerification'
//     ])->name('voyager.tokens.toggle-verification');
    
//     Route::get('tokens/{id}/toggle-status', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleStatus'
//     ])->name('voyager.tokens.toggle-status');
    
//     Route::post('tokens/{id}/adjust-supply', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'adjustSupply'
//     ])->name('voyager.tokens.adjust-supply');
    
//     // Privacy page routes
//     Route::get('public-pages/privacy/edit', [
//         App\Http\Controllers\Admin\PublicPagesController::class, 'editPrivacy'
//     ])->name('voyager.public-pages.edit-privacy');
    
//     Route::put('public-pages/privacy/update', [
//         App\Http\Controllers\Admin\PublicPagesController::class, 'updatePrivacy'
//     ])->name('voyager.public-pages.update-privacy');
    
//     // Load Voyager routes AFTER your custom routes
//     Voyager::routes();
    
//     // Your existing routes...
//     Route::get('/metrics/new/users/value', [App\Http\Controllers\MetricsController::class, 'newUsersValue'])->name('admin.metrics.new.users.value');
//     Route::get('/metrics/new/users/trend', [App\Http\Controllers\MetricsController::class, 'newUsersTrend'])->name('admin.metrics.new.users.trend');
//     Route::get('/metrics/new/users/partition', [App\Http\Controllers\MetricsController::class, 'newUsersPartition'])->name('admin.metrics.new.users.partition');
//     Route::get('/metrics/subscriptions/value', [App\Http\Controllers\MetricsController::class, 'subscriptionsValue'])->name('admin.metrics.subscriptions.value');
//     Route::get('/metrics/subscriptions/trend', [App\Http\Controllers\MetricsController::class, 'subscriptionsTrend'])->name('admin.metrics.subscriptions.trend');
//     Route::get('/metrics/subscriptions/partition', [App\Http\Controllers\MetricsController::class, 'subscriptionsPartition'])->name('admin.metrics.subscriptions.partition');

//     Route::post('/theme/generate', [App\Http\Controllers\GenericController::class, 'generateCustomTheme'])->name('admin.theme.generate');
//     Route::post('/license/save', [App\Http\Controllers\GenericController::class, 'saveLicense'])->name('admin.license.save');

//     Route::get('/users/{id}/impersonate', [App\Http\Controllers\UserController::class, 'impersonate'])->name('admin.impersonate');
//     Route::get('/leave-impersonation', [App\Http\Controllers\UserController::class, 'leaveImpersonation'])->name('admin.leaveImpersonation');
//     Route::get('/clear-app-cache', [App\Http\Controllers\GenericController::class, 'clearAppCache'])->name('admin.clear.cache');

//     Route::post('/withdrawals/{withdrawalId}/approve', [App\Http\Controllers\WithdrawalsController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');
//     Route::post('/withdrawals/{withdrawalId}/reject', [App\Http\Controllers\WithdrawalsController::class, 'rejectWithdrawal'])->name('admin.withdrawals.reject');
// });

// Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin.user']], function () {
    
//     // Revenue management routes
//     Route::get('revenue', [
//         App\Http\Controllers\Admin\RevenueController::class, 'index'
//     ])->name('voyager.revenue.index');
    
//     Route::get('revenue/{id}', [
//         App\Http\Controllers\Admin\RevenueController::class, 'show'
//     ])->name('voyager.revenue.show');
    
//     Route::get('revenue/{id}/mark-distributed', [
//         App\Http\Controllers\Admin\RevenueController::class, 'markDistributed'
//     ])->name('voyager.revenue.mark-distributed');
    
//     Route::get('revenue/{id}/mark-pending', [
//         App\Http\Controllers\Admin\RevenueController::class, 'markPending'
//     ])->name('voyager.revenue.mark-pending');
    
//     Route::get('revenue/{id}/details', [
//         App\Http\Controllers\Admin\RevenueController::class, 'getRevenueDetails'
//     ])->name('voyager.revenue.details');
    
//     Route::get('revenue/export/csv', [
//         App\Http\Controllers\Admin\RevenueController::class, 'export'
//     ])->name('voyager.revenue.export');
    
//     Route::get('revenue/dashboard/stats', [
//         App\Http\Controllers\Admin\RevenueController::class, 'getDashboardStats'
//     ])->name('voyager.revenue.dashboard-stats');
    
//     // Your existing wallet routes...
//     Route::get('wallets', [
//         App\Http\Controllers\Admin\WalletController::class, 'index'
//     ])->name('voyager.wallets.index');
    
//     Route::get('wallets/{id}', [
//         App\Http\Controllers\Admin\WalletController::class, 'show'
//     ])->name('voyager.wallets.show');
    
//     Route::get('wallets/{id}/toggle-status', [
//         App\Http\Controllers\Admin\WalletController::class, 'toggleStatus'
//     ])->name('voyager.wallets.toggle-status');
    
//     Route::get('wallets/{id}/details', [
//         App\Http\Controllers\Admin\WalletController::class, 'getWalletDetails'
//     ])->name('voyager.wallets.details');
    
//     Route::get('wallets/export/csv', [
//         App\Http\Controllers\Admin\WalletController::class, 'export'
//     ])->name('voyager.wallets.export');
    
//     // Your existing token routes...
//     Route::resource('tokens', App\Http\Controllers\Admin\CryptocurrencyController::class, [
//         'names' => [
//             'index' => 'voyager.tokens.index',
//             'create' => 'voyager.tokens.create',
//             'store' => 'voyager.tokens.store',
//             'show' => 'voyager.tokens.show',
//             'edit' => 'voyager.tokens.edit',
//             'update' => 'voyager.tokens.update',
//             'destroy' => 'voyager.tokens.destroy',
//         ]
//     ]);
    
//     Route::get('tokens/{id}/toggle-freeze', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleFreeze'
//     ])->name('voyager.tokens.toggle-freeze');
    
//     Route::get('tokens/{id}/toggle-verification', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleVerification'
//     ])->name('voyager.tokens.toggle-verification');
    
//     Route::get('tokens/{id}/toggle-status', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleStatus'
//     ])->name('voyager.tokens.toggle-status');
    
//     Route::post('tokens/{id}/adjust-supply', [
//         App\Http\Controllers\Admin\CryptocurrencyController::class, 'adjustSupply'
//     ])->name('voyager.tokens.adjust-supply');
    
//     // Privacy page routes
//     Route::get('public-pages/privacy/edit', [
//         App\Http\Controllers\Admin\PublicPagesController::class, 'editPrivacy'
//     ])->name('voyager.public-pages.edit-privacy');
    
//     Route::put('public-pages/privacy/update', [
//         App\Http\Controllers\Admin\PublicPagesController::class, 'updatePrivacy'
//     ])->name('voyager.public-pages.update-privacy');
    
//     // Load Voyager routes AFTER your custom routes
//     Voyager::routes();
    
//     // Your existing routes...
//     Route::get('/metrics/new/users/value', [App\Http\Controllers\MetricsController::class, 'newUsersValue'])->name('admin.metrics.new.users.value');
//     Route::get('/metrics/new/users/trend', [App\Http\Controllers\MetricsController::class, 'newUsersTrend'])->name('admin.metrics.new.users.trend');
//     Route::get('/metrics/new/users/partition', [App\Http\Controllers\MetricsController::class, 'newUsersPartition'])->name('admin.metrics.new.users.partition');
//     Route::get('/metrics/subscriptions/value', [App\Http\Controllers\MetricsController::class, 'subscriptionsValue'])->name('admin.metrics.subscriptions.value');
//     Route::get('/metrics/subscriptions/trend', [App\Http\Controllers\MetricsController::class, 'subscriptionsTrend'])->name('admin.metrics.subscriptions.trend');
//     Route::get('/metrics/subscriptions/partition', [App\Http\Controllers\MetricsController::class, 'subscriptionsPartition'])->name('admin.metrics.subscriptions.partition');

//     Route::post('/theme/generate', [App\Http\Controllers\GenericController::class, 'generateCustomTheme'])->name('admin.theme.generate');
//     Route::post('/license/save', [App\Http\Controllers\GenericController::class, 'saveLicense'])->name('admin.license.save');

//     Route::get('/users/{id}/impersonate', [App\Http\Controllers\UserController::class, 'impersonate'])->name('admin.impersonate');
//     Route::get('/leave-impersonation', [App\Http\Controllers\UserController::class, 'leaveImpersonation'])->name('admin.leaveImpersonation');
//     Route::get('/clear-app-cache', [App\Http\Controllers\GenericController::class, 'clearAppCache'])->name('admin.clear.cache');

//     Route::post('/withdrawals/{withdrawalId}/approve', [App\Http\Controllers\WithdrawalsController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');
//     Route::post('/withdrawals/{withdrawalId}/reject', [App\Http\Controllers\WithdrawalsController::class, 'rejectWithdrawal'])->name('admin.withdrawals.reject');
// });

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin.user']], function () {
    
    // Dashboard routes (should be first to override default Voyager dashboard)
    Route::get('/', [
        App\Http\Controllers\Admin\DashboardController::class, 'index'
    ])->name('voyager.dashboard');
    
    Route::get('dashboard', [
        App\Http\Controllers\Admin\DashboardController::class, 'index'
    ])->name('voyager.dashboard.index');
    
    Route::get('dashboard/realtime-stats', [
        App\Http\Controllers\Admin\DashboardController::class, 'getRealtimeStats'
    ])->name('voyager.dashboard.realtime-stats');
    
    Route::get('dashboard/chart-data', [
        App\Http\Controllers\Admin\DashboardController::class, 'getChartData'
    ])->name('voyager.dashboard.chart-data');
    
    Route::get('dashboard/top-performers', [
        App\Http\Controllers\Admin\DashboardController::class, 'getTopPerformers'
    ])->name('voyager.dashboard.top-performers');
    
    Route::get('dashboard/system-health', [
        App\Http\Controllers\Admin\DashboardController::class, 'getSystemHealth'
    ])->name('voyager.dashboard.system-health');
    
    Route::get('dashboard/export', [
        App\Http\Controllers\Admin\DashboardController::class, 'exportSummary'
    ])->name('voyager.dashboard.export');
    
    // Revenue management routes
    Route::get('revenue', [
        App\Http\Controllers\Admin\RevenueController::class, 'index'
    ])->name('voyager.revenue.index');
    
    Route::get('revenue/{id}', [
        App\Http\Controllers\Admin\RevenueController::class, 'show'
    ])->name('voyager.revenue.show');
    
    Route::get('revenue/{id}/mark-distributed', [
        App\Http\Controllers\Admin\RevenueController::class, 'markDistributed'
    ])->name('voyager.revenue.mark-distributed');
    
    Route::get('revenue/{id}/mark-pending', [
        App\Http\Controllers\Admin\RevenueController::class, 'markPending'
    ])->name('voyager.revenue.mark-pending');
    
    Route::get('revenue/{id}/details', [
        App\Http\Controllers\Admin\RevenueController::class, 'getRevenueDetails'
    ])->name('voyager.revenue.details');
    
    Route::get('revenue/export/csv', [
        App\Http\Controllers\Admin\RevenueController::class, 'export'
    ])->name('voyager.revenue.export');
    
    // Wallet management routes
    Route::get('wallets', [
        App\Http\Controllers\Admin\WalletController::class, 'index'
    ])->name('voyager.wallets.index');
    
    Route::get('wallets/{id}', [
        App\Http\Controllers\Admin\WalletController::class, 'show'
    ])->name('voyager.wallets.show');
    
    Route::get('wallets/{id}/toggle-status', [
        App\Http\Controllers\Admin\WalletController::class, 'toggleStatus'
    ])->name('voyager.wallets.toggle-status');
    
    Route::get('wallets/{id}/details', [
        App\Http\Controllers\Admin\WalletController::class, 'getWalletDetails'
    ])->name('voyager.wallets.details');
    
    Route::get('wallets/export/csv', [
        App\Http\Controllers\Admin\WalletController::class, 'export'
    ])->name('voyager.wallets.export');
    
    // Token management routes
    Route::resource('tokens', App\Http\Controllers\Admin\CryptocurrencyController::class, [
        'names' => [
            'index' => 'voyager.tokens.index',
            'create' => 'voyager.tokens.create',
            'store' => 'voyager.tokens.store',
            'show' => 'voyager.tokens.show',
            'edit' => 'voyager.tokens.edit',
            'update' => 'voyager.tokens.update',
            'destroy' => 'voyager.tokens.destroy',
        ]
    ]);
    
    Route::get('tokens/{id}/toggle-freeze', [
        App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleFreeze'
    ])->name('voyager.tokens.toggle-freeze');
    
    Route::get('tokens/{id}/toggle-verification', [
        App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleVerification'
    ])->name('voyager.tokens.toggle-verification');
    
    Route::get('tokens/{id}/toggle-status', [
        App\Http\Controllers\Admin\CryptocurrencyController::class, 'toggleStatus'
    ])->name('voyager.tokens.toggle-status');
    
    Route::post('tokens/{id}/adjust-supply', [
        App\Http\Controllers\Admin\CryptocurrencyController::class, 'adjustSupply'
    ])->name('voyager.tokens.adjust-supply');
    
    // Privacy page routes
    Route::get('public-pages/privacy/edit', [
        App\Http\Controllers\Admin\PublicPagesController::class, 'editPrivacy'
    ])->name('voyager.public-pages.edit-privacy');
    
    Route::put('public-pages/privacy/update', [
        App\Http\Controllers\Admin\PublicPagesController::class, 'updatePrivacy'
    ])->name('voyager.public-pages.update-privacy');
    
    // Load Voyager routes AFTER your custom routes
    // User verification routes (must be before Voyager::routes())
    Route::get('user-verifies/{id}/verify', [
        App\Http\Controllers\Voyager\UserVerifiesController::class, 'verify'
    ])->name('voyager.user-verifies.verify');
    
    Route::put('user-verifies/{id}/update-status', [
        App\Http\Controllers\Voyager\UserVerifiesController::class, 'updateStatus'
    ])->name('voyager.user-verifies.update-status');

    Voyager::routes();

    // Your existing routes...
    Route::get('/metrics/new/users/value', [App\Http\Controllers\MetricsController::class, 'newUsersValue'])->name('admin.metrics.new.users.value');
    Route::get('/metrics/new/users/trend', [App\Http\Controllers\MetricsController::class, 'newUsersTrend'])->name('admin.metrics.new.users.trend');
    Route::get('/metrics/new/users/partition', [App\Http\Controllers\MetricsController::class, 'newUsersPartition'])->name('admin.metrics.new.users.partition');
    Route::get('/metrics/subscriptions/value', [App\Http\Controllers\MetricsController::class, 'subscriptionsValue'])->name('admin.metrics.subscriptions.value');
    Route::get('/metrics/subscriptions/trend', [App\Http\Controllers\MetricsController::class, 'subscriptionsTrend'])->name('admin.metrics.subscriptions.trend');
    Route::get('/metrics/subscriptions/partition', [App\Http\Controllers\MetricsController::class, 'subscriptionsPartition'])->name('admin.metrics.subscriptions.partition');

    Route::post('/theme/generate', [App\Http\Controllers\GenericController::class, 'generateCustomTheme'])->name('admin.theme.generate');
    Route::post('/license/save', [App\Http\Controllers\GenericController::class, 'saveLicense'])->name('admin.license.save');

    Route::get('/users/{id}/impersonate', [App\Http\Controllers\UserController::class, 'impersonate'])->name('admin.impersonate');
    Route::get('/leave-impersonation', [App\Http\Controllers\UserController::class, 'leaveImpersonation'])->name('admin.leaveImpersonation');
    Route::get('/clear-app-cache', [App\Http\Controllers\GenericController::class, 'clearAppCache'])->name('admin.clear.cache');

    Route::post('/withdrawals/{withdrawalId}/approve', [App\Http\Controllers\WithdrawalsController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');
    Route::post('/withdrawals/{withdrawalId}/reject', [App\Http\Controllers\WithdrawalsController::class, 'rejectWithdrawal'])->name('admin.withdrawals.reject');
});


// Route::prefix('admin')->name('admin.')->group(function () {
    
//     // Guest routes (not logged in)
//     Route::middleware('guest')->group(function () {
//         Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
//         Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
//     });
    
//     // Authenticated admin routes - using regular auth with admin check
//     Route::middleware(['auth', 'admin'])->group(function () {
        
//         // Logout
//         Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
//         // Dashboard
//         Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
//         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        
//         // Tax Revenue Management
//         Route::prefix('revenue')->name('revenue.')->group(function () {
//             Route::get('/', [RevenueController::class, 'index'])->name('index');
//             Route::get('/tax', [RevenueController::class, 'taxRevenue'])->name('tax');
//             Route::get('/export', [RevenueController::class, 'export'])->name('export');
//         });
        
//         // Token Management
//         Route::prefix('tokens')->name('tokens.')->group(function () {
//             Route::get('/', [TokenController::class, 'index'])->name('index');
//             Route::get('/earnings', [TokenController::class, 'earnings'])->name('earnings');
//             Route::get('/platform-coin', [TokenController::class, 'platformCoin'])->name('platform-coin');
//             Route::get('/{id}', [TokenController::class, 'show'])->name('show');
//         });
        
//         // Wallet Management
//         Route::prefix('wallets')->name('wallets.')->group(function () {
//             Route::get('/', [WalletController::class, 'index'])->name('index');
//             Route::get('/activity', [WalletController::class, 'activity'])->name('activity');
//             Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
//             Route::get('/user/{userId}', [WalletController::class, 'userWallet'])->name('user');
//             Route::post('/correct', [WalletController::class, 'correct'])->name('correct');
//         });
        
//         // Creator Token Management
//         Route::prefix('creator-tokens')->name('creator-tokens.')->group(function () {
//             Route::get('/', [CreatorTokenController::class, 'index'])->name('index');
//             Route::get('/pending', [CreatorTokenController::class, 'pending'])->name('pending');
//             Route::get('/{id}', [CreatorTokenController::class, 'show'])->name('show');
//             Route::post('/{id}/approve', [CreatorTokenController::class, 'approve'])->name('approve');
//             Route::post('/{id}/deny', [CreatorTokenController::class, 'deny'])->name('deny');
//             Route::post('/{id}/freeze', [CreatorTokenController::class, 'freeze'])->name('freeze');
//             Route::post('/{id}/unfreeze', [CreatorTokenController::class, 'unfreeze'])->name('unfreeze');
//             Route::post('/{id}/adjust-supply', [CreatorTokenController::class, 'adjustSupply'])->name('adjust-supply');
//         });
        
//         // Dispute Resolution
//         Route::prefix('disputes')->name('disputes.')->group(function () {
//             Route::get('/', [WalletController::class, 'disputes'])->name('index');
//             Route::get('/{id}', [WalletController::class, 'showDispute'])->name('show');
//             Route::post('/{id}/resolve', [WalletController::class, 'resolveDispute'])->name('resolve');
//         });
        
//         // Audit Logs
//         Route::prefix('audit')->name('audit.')->group(function () {
//             Route::get('/', [AuditController::class, 'index'])->name('index');
//             Route::get('/financial', [AuditController::class, 'financial'])->name('financial');
//             Route::get('/tokens', [AuditController::class, 'tokens'])->name('tokens');
//             Route::get('/export', [AuditController::class, 'export'])->name('export');
//         });
        
//         // User Impersonation - using your existing controller
//         Route::get('/impersonate/{id}', [UserController::class, 'impersonate'])->name('impersonate');
//         Route::get('/leave-impersonation', [UserController::class, 'leaveImpersonation'])->name('leave-impersonation');
//     });
// });

// Home & contact page
Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);

Route::get('/contact', ['uses' => 'GenericController@contact', 'as'   => 'contact']);
Route::post('/contact/send', ['uses' => 'GenericController@sendContactMessage', 'as'   => 'contact.send']);

// Language switcher route
Route::get('language/{locale}', ['uses' => 'GenericController@setLanguage', 'as'   => 'language']);

/* Auth Routes + Verify password */
Auth::routes(['verify'=>true]);
Route::get('email/verify', ['uses' => 'GenericController@userVerifyEmail', 'as' => 'verification.notice']);
Route::post('resendVerification', ['uses' => 'GenericController@resendConfirmationEmail', 'as'   => 'verfication.resend']);
// Social Auth login / register
Route::get('socialAuth/{provider}', ['uses' => 'Auth\LoginController@redirectToProvider', 'as' => 'social.login.start']);
Route::get('socialAuth/{provider}/callback', ['uses' => 'Auth\LoginController@handleProviderCallback', 'as' => 'social.login.callback']);

// Debug routes for testing
Route::get('/debug-auth', function() {
    return [
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'verified' => auth()->user() ? auth()->user()->hasVerifiedEmail() : false,
    ];
})->middleware(['auth', 'verified']);

Route::get('/test-video-route', function() {
    return 'Video route is working!';
});

Route::post('/test-video-post', function() {
    return 'Video POST route is working!';
});

Route::get('/test-get', function() {
    return 'GET test works!';
});

Route::post('/test-post', function() {
    return 'POST test works!';
});

// REMOVED THE PROBLEMATIC ANY ROUTE:
// Route::any('/videos', function() {
//     return 'ANY request to /videos works! Method: ' . request()->method();
// });

// VIDEO ROUTES - FIXED AND PLACED CORRECTLY
Route::get('/feed', [FeedController::class, 'index'])->name('feed');
Route::post('/videos/{videoId}/views', [FeedController::class, 'incrementViews']);
// User profile route - using ONLY Feed controller  
Route::get('/profile/{username}', [FeedController::class, 'userProfile'])->name('user.profile');

Route::group(['prefix' => 'videos', 'as' => 'videos.'], function () {
    Route::get('/reels', [FeedController::class, 'index'])->name('reels'); // CHANGED to FeedController
    Route::get('/', [VideoController::class, 'index'])->name('index'); // Keep for video listing
    
    Route::middleware(['auth', 'verified'])->group(function () {
        
        // Video CRUD routes - Keep VideoController for these
        Route::get('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/', [VideoController::class, 'store'])->name('store');
        Route::get('/my', [VideoController::class, 'myVideos'])->name('my');
        Route::get('/{video}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::put('/{video}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{video}', [VideoController::class, 'destroy'])->name('destroy');
        
        // Video interaction routes - ALL using ONLY FeedController
        Route::post('/{video}/like', [FeedController::class, 'toggleLike'])->name('like');
        Route::post('/{video}/view', [FeedController::class, 'incrementViews'])->name('view');
        Route::post('/{video}/share', [FeedController::class, 'shareVideo'])->name('share');
        Route::post('/{video}/repost', [FeedController::class, 'toggleRepost'])->name('repost');
        
        // Comment routes - ALL using ONLY FeedController
        Route::get('/{video}/comments', [FeedController::class, 'getComments'])->name('comments.index');
        Route::post('/{video}/comment', [FeedController::class, 'addComment'])->name('comment');
        Route::post('/{video}/comments', [FeedController::class, 'addComment'])->name('comments.store'); // Same method
        Route::post('/videos/{videoId}/comments', [FeedController::class, 'addComment']);

        
    });
    
    // Video show route - Keep VideoController for individual video pages
    Route::get('/{video}', [VideoController::class, 'show'])->name('show');
});

// API Routes for AJAX calls - ALL using ONLY FeedController
Route::prefix('api')->middleware('web')->group(function () {
    // Public API routes
    Route::get('videos/{video}/stats', [FeedController::class, 'getVideoStats'])->name('api.video.stats');
    Route::post('videos/{video}/view', [FeedController::class, 'incrementViews'])->name('api.video.view');
    Route::get('videos/search', [FeedController::class, 'search'])->name('api.video.search');
    Route::get('videos/trending', [FeedController::class, 'trending'])->name('api.video.trending');
    
    // Authenticated API routes
    Route::middleware('auth')->group(function () {
        Route::post('videos/{video}/like', [FeedController::class, 'toggleLike'])->name('api.video.like');
        Route::post('videos/{video}/comment', [FeedController::class, 'addComment'])->name('api.video.comment');
        Route::get('videos/{video}/comments', [FeedController::class, 'getComments'])->name('api.video.comments');
        Route::post('videos/{video}/share', [FeedController::class, 'shareVideo'])->name('api.video.share');
        Route::post('videos/{video}/repost', [FeedController::class, 'toggleRepost'])->name('api.video.repost');
    });
});
//----------------------------------------------
// Route::get('/feed', ['uses' => 'FeedController@index', 'as'   => 'feed']);
// Route::group(['prefix' => 'videos', 'as' => 'videos.'], function () {
//     Route::get('/reels', [VideoController::class, 'reels'])->name('reels');
//     Route::get('/', [VideoController::class, 'index'])->name('index');
//     Route::middleware(['auth', 'verified'])->group(function () {
        
//         // Video CRUD routes
//         Route::get('/create', [VideoController::class, 'create'])->name('create');
//         Route::post('/', [VideoController::class, 'store'])->name('store'); // MOVED INSIDE AUTH
        
//         Route::get('/my', [VideoController::class, 'myVideos'])->name('my');
        
//         // Individual video routes
//         Route::post('/videos', [VideoController::class, 'store'])->name('store');
//         Route::get('/{video}/edit', [VideoController::class, 'edit'])->name('edit');
//         Route::put('/{video}', [VideoController::class, 'update'])->name('update');
//         Route::delete('/{video}', [VideoController::class, 'destroy'])->name('destroy');
        
//         // Video interaction routes
//         Route::post('/{video}/like', [VideoController::class, 'toggleLike'])->name('like');
//         Route::post('/{video}/view', [VideoController::class, 'incrementView'])->name('view');
//         Route::post('/{video}/share', [VideoController::class, 'share'])->name('share');
        
//         // Comment routes
//         Route::get('/{video}/comments', [VideoCommentController::class, 'index'])->name('comments.index');
//         Route::post('/{video}/comment', [VideoCommentController::class, 'storeAjax'])->name('comment');
//         Route::post('/{video}/comments', [VideoCommentController::class, 'store'])->name('comments.store');
//         Route::delete('/comments/{comment}', [VideoCommentController::class, 'destroy'])->name('comments.destroy');
//     });
    
//     // Video show route - MUST be last
//     Route::get('/{video}', [VideoController::class, 'show'])->name('show');
// });

/*
 * (User) Protected routes
 */
Route::group(['middleware' => ['auth', 'verified', '2fa']], function () {
    // Settings panel routes
    Route::group(['prefix' => 'my', 'as' => 'my.'], function () {

        /*
         * (My) Settings
         */
        // Deposit - Payments
        Route::post('/settings/deposit/generateStripeSession', [
            'uses' => 'PaymentsController@generateStripeSession',
            'as'   => 'settings.deposit.generateStripeSession',
        ]);
        Route::post('/settings/flags/save', ['uses' => 'SettingsController@updateFlagSettings', 'as'   => 'settings.flags.save']);
        Route::post('/settings/profile/save', ['uses' => 'SettingsController@saveProfile', 'as'   => 'settings.profile.save']);
        Route::post('/settings/rates/save', ['uses' => 'SettingsController@saveRates', 'as'   => 'settings.rates.save']);
        Route::post('/settings/profile/upload/{uploadType}', ['uses' => 'SettingsController@uploadProfileAsset', 'as'   => 'settings.profile.upload']);
        Route::post('/settings/profile/remove/{assetType}', ['uses' => 'SettingsController@removeProfileAsset', 'as'   => 'settings.profile.remove']);
        Route::post('/settings/save', ['uses' => 'SettingsController@updateUserSettings', 'as'   => 'settings.save']);
        Route::post('/settings/verify/upload', ['uses' => 'SettingsController@verifyUpload', 'as'   => 'settings.verify.upload']);
        Route::post('/settings/verify/upload/delete', ['uses' => 'SettingsController@deleteVerifyAsset', 'as'   => 'settings.verify.delete']);
        Route::post('/settings/verify/save', ['uses' => 'SettingsController@saveVerifyRequest', 'as'   => 'settings.verify.save']);
        Route::get('/settings/privacy/countries', ['uses' => 'SettingsController@getCountries', 'as'   => 'settings.verify.countries']);

        // Profile save
        Route::get('/settings/{type?}', ['uses' => 'SettingsController@index', 'as'   => 'settings']);
        Route::post('/settings/account/save', ['uses' => 'SettingsController@saveAccount', 'as'   => 'settings.account.save']);

        /*
         * (My) Notifications
         */
        Route::get('/notifications/{type?}', ['uses' => 'NotificationsController@index', 'as'   => 'notifications']);

        /*
         * (My) Messenger
         */
        Route::group(['prefix' => 'messenger', 'as' => 'messenger.'], function () {
            Route::get('/', ['uses' => 'MessengerController@index', 'as' => 'get']);
            Route::get('/fetchContacts', ['uses' => 'MessengerController@fetchContacts', 'as' => 'fetch']);
            Route::get('/fetchMessages/{userID}', 'MessengerController@fetchMessages', ['as' => 'fetch.user']);
            Route::post('/sendMessage', 'MessengerController@sendMessage', ['as' => 'send']);
            Route::delete('/delete/{commentID}', 'MessengerController@deleteMessage', ['as' => 'delete']);
            Route::post('/authorizeUser', 'MessengerController@authorizeUser', ['as' => 'authorize']);
            Route::post('/markSeen', 'MessengerController@markSeen', ['as' => 'mark']);
        });
        /*
         * (My) Bookmarks
         */
        Route::any('/bookmarks/{type?}', ['uses' => 'BookmarksController@index', 'as'   => 'bookmarks']);

        /*
         * (My) Lists
         */
        Route::group(['prefix' => '', 'as' => 'lists.'], function () {
            Route::get('/lists', ['uses' => 'ListsController@index', 'as'   => 'all']);
            Route::post('/lists/save', ['uses' => 'ListsController@saveList', 'as'   => 'save']);
            Route::get('/lists/{list_id}', ['uses' => 'ListsController@showList', 'as'   => 'show']);
            Route::delete('/lists/delete', ['uses' => 'ListsController@deleteList', 'as'   => 'delete']);
            Route::post('/lists/members/save', ['uses' => 'ListsController@addListMember', 'as'   => 'members.save']);
            Route::delete('/lists/members/delete', ['uses' => 'ListsController@deleteListMember', 'as'   => 'members.delete']);
            Route::post('/lists/members/clear', ['uses' => 'ListsController@clearList', 'as'   => 'members.clear']);
            Route::post('/lists/manage/follows', ['uses' => 'ListsController@manageUserFollows', 'as'   => 'manage.follows']);
        });

    });

    // Streaming routes (moved outside of my prefix)
    Route::group(['prefix' => 'streams', 'as' => 'streams.'], function () {
        Route::get('/', [StreamController::class, 'index'])->name('index');
        Route::get('/create', [StreamController::class, 'create'])->name('create');
        Route::post('/store', [StreamController::class, 'store'])->name('store');
        Route::get('/{stream}', [StreamController::class, 'show'])->name('show');
        Route::get('/{stream}/broadcast', [StreamController::class, 'broadcast'])->name('broadcast');
        Route::get('/{stream}/watch', [StreamController::class, 'watch'])->name('watch');
        Route::post('/{stream}/end', [StreamController::class, 'end'])->name('end');
    });

    Route::post('authorizeStreamPresence', ['uses' => 'StreamsController@authorizeUser', 'as'  => 'public.stream.authorizeUser']);
    Route::post('stream/comments/add', ['uses' => 'StreamsController@addComment', 'as'  => 'public.stream.comment.add']);
    Route::delete('stream/comments/delete', ['uses' => 'StreamsController@deleteComment', 'as'  => 'public.stream.comment.delete']);
    Route::get('stream/archive/{streamID}/{slug}', ['uses' => 'StreamsController@getVod', 'as'  => 'public.vod.get']);
    Route::get('stream/{streamID}/{slug}', ['uses' => 'StreamsController@getStream', 'as'  => 'public.stream.get']);

    Route::post('/report/content', ['uses' => 'ListsController@postReport', 'as'   => 'report.content']);

    Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
        Route::post('/initiate', ['uses' => 'PaymentsController@initiatePayment', 'as'   => 'initiatePayment']);
        Route::post('/initiate/validate', ['uses' => 'PaymentsController@paymentInitiateValidator', 'as'   => 'initiatePaymentValidator']);
        Route::get('/paypal/status', ['uses' => 'PaymentsController@executePaypalPayment', 'as'   => 'executePaypalPayment']);
        Route::get('/stripe/status', ['uses' => 'PaymentsController@getStripePaymentStatus', 'as'   => 'checkStripePaymentStatus']);
        Route::get('/coinbase/status', ['uses' => 'PaymentsController@checkAndUpdateCoinbaseTransaction', 'as'   => 'checkCoinBasePaymentStatus']);
        Route::get('/nowpayments/status', ['uses' => 'PaymentsController@checkAndUpdateNowPaymentsTransaction', 'as'   => 'checkNowPaymentStatus']);
        Route::get('/ccbill/status', ['uses' => 'PaymentsController@processCCBillTransaction', 'as'   => 'checkCCBillPaymentStatus']);
        Route::get('/paystack/status', ['uses' => 'PaymentsController@verifyPaystackTransaction', 'as'   => 'checkPaystackPaymentStatus']);
        Route::get('/mercado/status', ['uses' => 'PaymentsController@verifyMercadoTransaction', 'as'   => 'checkMercadoPaymentStatus']);
    });

    // Cryptocurrency routes
Route::prefix('cryptocurrency')->name('cryptocurrency.')->middleware(['auth', 'verified', '2fa'])->group(function () {
    // Main routes
    Route::get('/', [App\Http\Controllers\CryptocurrencyController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CryptocurrencyController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\CryptocurrencyController::class, 'store'])->name('store');
    
    // Marketplace and exploration
    Route::get('/marketplace', [App\Http\Controllers\CryptocurrencyController::class, 'marketplace'])->name('marketplace');
    Route::get('/explorer', [App\Http\Controllers\CryptocurrencyController::class, 'explorer'])->name('explorer');
});

// NFT Marketplace Routes
Route::prefix('nft')->name('nft.')->middleware(['auth', 'verified', '2fa'])->group(function () {
    // Main marketplace
    Route::get('/marketplace', [App\Http\Controllers\NFTMarketplaceController::class, 'index'])->name('marketplace');
    
    // Create NFT
    Route::get('/create', [App\Http\Controllers\NFTMarketplaceController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\NFTMarketplaceController::class, 'store'])->name('store');

    // View NFT
    Route::get('/{id}', [App\Http\Controllers\NFTMarketplaceController::class, 'show'])->name('show');
    
    // Buy NFT
    Route::post('/buy/{id}', [App\Http\Controllers\NFTMarketplaceController::class, 'buy'])->name('buy');
    
    // User NFTs
    Route::get('/my/nfts', [App\Http\Controllers\NFTMarketplaceController::class, 'myNFTs'])->name('my-nfts');
    Route::get('/my/listings', [App\Http\Controllers\NFTMarketplaceController::class, 'myListings'])->name('my-listings');
    
    // Resell NFT
    Route::get('/resell/{id}', [App\Http\Controllers\NFTMarketplaceController::class, 'resell'])->name('resell');
    Route::post('/resell/{id}', [App\Http\Controllers\NFTMarketplaceController::class, 'resell'])->name('resell.post');
    
    // API Routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/contract-abi', [App\Http\Controllers\NFTMarketplaceController::class, 'getContractAbi'])->name('contract-abi');
        Route::get('/listing-price', [App\Http\Controllers\NFTMarketplaceController::class, 'getListingPrice'])->name('listing-price');
    });
});

// Continue cryptocurrency routes
Route::prefix('cryptocurrency')->name('cryptocurrency.')->middleware(['auth', 'verified', '2fa'])->group(function () {
    // Wallet management
    Route::get('/wallet', [App\Http\Controllers\CryptocurrencyController::class, 'wallet'])->name('wallet');
    Route::get('/transactions/{type?}', [App\Http\Controllers\CryptocurrencyController::class, 'transactions'])->name('transactions');
    
    // Deposit and withdraw
    Route::get('/deposit', [App\Http\Controllers\CryptocurrencyController::class, 'deposit'])->name('deposit');
    Route::post('/deposit', [App\Http\Controllers\CryptocurrencyController::class, 'processDeposit'])->name('deposit.process');
    Route::get('/withdraw', [App\Http\Controllers\CryptocurrencyController::class, 'withdraw'])->name('withdraw');
    Route::post('/withdraw', [App\Http\Controllers\CryptocurrencyController::class, 'processWithdraw'])->name('withdraw.process');
    
    // Token-specific routes (MUST come LAST to avoid route conflicts)
    Route::get('/{id}/buy', [App\Http\Controllers\CryptocurrencyController::class, 'buyForm'])->name('buy.form');
    Route::post('/{id}/buy', [App\Http\Controllers\CryptocurrencyController::class, 'buy'])->name('buy');
    Route::get('/{id}/sell', [App\Http\Controllers\CryptocurrencyController::class, 'sellForm'])->name('sell.form');
    Route::post('/{id}/sell', [App\Http\Controllers\CryptocurrencyController::class, 'processSell'])->name('sell');
    Route::get('/{id}', [App\Http\Controllers\CryptocurrencyController::class, 'show'])->name('show');
});

    // Feed routes
    Route::get('/feed', ['uses' => 'FeedController@index', 'as'   => 'feed']);
    Route::get('/feed/posts', ['uses' => 'FeedController@getFeedPosts', 'as'   => 'feed.posts']);

    // File uploader routes
    Route::group(['prefix' => 'attachment', 'as' => 'attachment.'], function () {
        Route::post('/upload/{type}', ['uses' => 'AttachmentController@upload', 'as'   => 'upload']);
        Route::post('/uploadChunked/{type}', ['uses' => 'AttachmentController@uploadChunk', 'as'   => 'upload.chunked']);
        Route::post('/remove', ['uses' => 'AttachmentController@removeAttachment', 'as'   => 'remove']);
        Route::post('/test-upload', ['uses' => 'AttachmentController@testUpload', 'as' => 'test-upload']);
        Route::get('/upload-error', ['uses' => 'AttachmentController@uploadError', 'as' => 'upload-error']);
        Route::get('/test', function() { return view('test-upload'); })->name('test');
    });

    // Posts routes
    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
        Route::post('/save', ['uses' => 'PostsController@savePost', 'as'   => 'save']);
        Route::get('/create', ['uses' => 'PostsController@create', 'as'   => 'create']);
        Route::get('/edit/{post_id}', ['uses' => 'PostsController@edit', 'as'   => 'edit']);
        Route::get('/{post_id}/{username}', ['uses' => 'PostsController@getPost', 'as'   => 'get']);
        Route::get('/comments', ['uses' => 'PostsController@getPostComments', 'as'   => 'get.comments']);
        Route::post('/comments/add', ['uses' => 'PostsController@addNewComment', 'as'   => 'add.comments']);
        Route::post('/comments/edit', ['uses' => 'PostsController@editComment', 'as'   => 'edit.comments']);
        Route::delete('/comments/delete', ['uses' => 'PostsController@deleteComment', 'as'   => 'delete.comments']);

        Route::post('/reaction', ['uses' => 'PostsController@updateReaction', 'as'   => 'react']);
        Route::post('/bookmark', ['uses' => 'PostsController@updatePostBookmark', 'as'   => 'bookmark']);
        Route::post('/pin', ['uses' => 'PostsController@updatePostPin', 'as'   => 'pin']);
        Route::delete('/delete', ['uses' => 'PostsController@deletePost', 'as'   => 'delete']);
    });

    // Subscriptions routes
    Route::group(['prefix' => 'subscriptions', 'as' => 'subscriptions.'], function () {
        Route::get('/{subscriptionId}/cancel/{redirectTo}', ['uses' => 'SubscriptionsController@cancelSubscription', 'as'   => 'cancel']);
    });

    // Withdrawals routes
    Route::group(['prefix' => 'withdrawals', 'as' => 'withdrawals.'], function () {
        Route::post('/request', ['uses' => 'WithdrawalsController@requestWithdrawal', 'as'   => 'request']);
        Route::get('/onboarding', ['uses' => 'WithdrawalsController@onboarding', 'as'   => 'onboarding']);
    });

    // Invoices routes
    Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function () {
        Route::get('/{id}', ['uses' => 'InvoicesController@index', 'as'   => 'get']);
    });

    // Countries routes
    Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {
        Route::get('', ['uses' => 'GenericController@countries', 'as'   => 'get']);
    });

    // Ai routes
    Route::group(['prefix' => 'suggestions', 'as' => 'suggestions.'], function () {
        Route::post('/generate', ['uses' => 'AiController@generateSuggestion', 'as'   => 'generate']);
    });
});

// 2FA related routes
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('device-verify', ['uses' => 'TwoFAController@index', 'as' => '2fa.index']);
    Route::post('device-verify', ['uses' => 'TwoFAController@store', 'as' => '2fa.post']);
    Route::get('device-verify/reset', ['uses' => 'TwoFAController@resend', 'as' => '2fa.resend']);
    Route::delete('device-verify/delete', ['uses' => 'TwoFAController@deleteDevice', 'as' => '2fa.delete']);
});

Route::any('beacon/{type}', [
    'as'   => 'beacon.send',
    'uses' => 'StatsController@sendBeacon',
]);

Route::post('payment/stripeStatusUpdate', [
    'as'   => 'stripe.payment.update',
    'uses' => 'PaymentsController@stripePaymentsHook',
]);

Route::post('payment/stripeConnectStatusUpdate', [
    'as'   => 'stripeConnect.payment.update',
    'uses' => 'PaymentsController@stripeConnectHook',
]);

Route::post('payment/paypalStatusUpdate', [
    'as'   => 'paypal.payment.update',
    'uses' => 'PaymentsController@paypalPaymentsHook',
]);

Route::post('payment/coinbaseStatusUpdate', [
    'as'   => 'coinbase.payment.update',
    'uses' => 'PaymentsController@coinbaseHook',
]);

Route::post('payment/nowPaymentsStatusUpdate', [
    'as'   => 'nowPayments.payment.update',
    'uses' => 'PaymentsController@nowPaymentsHook',
]);

Route::post('payment/ccBillPaymentStatusUpdate', [
    'as'   => 'ccBill.payment.update',
    'uses' => 'PaymentsController@ccBillHook',
]);

Route::post('payment/paystackPaymentStatusUpdate', [
    'as'   => 'paystack.payment.update',
    'uses' => 'PaymentsController@paystackHook',
]);

Route::post('payment/mercadoPaymentStatusUpdate', [
    'as'   => 'mercado.payment.update',
    'uses' => 'PaymentsController@mercadoHook',
]);

Route::post('transcoding/coconut/update', [
    'as'   => 'transcoding.coconut.update',
    'uses' => 'AttachmentController@handleCoconutHook',
]);

// Install & upgrade routes
Route::get('/install', ['uses' => 'InstallerController@install', 'as'   => 'installer.install']);
Route::post('/install/savedbinfo', ['uses' => 'InstallerController@testAndSaveDBInfo', 'as'   => 'installer.savedb']);
Route::post('/install/beginInstall', ['uses' => 'InstallerController@beginInstall', 'as'   => 'installer.beginInstall']);
Route::get('/install/finishInstall', ['uses' => 'InstallerController@finishInstall', 'as'   => 'installer.finishInstall']);
Route::get('/update', ['uses' => 'InstallerController@upgrade', 'as'   => 'installer.update']);
Route::post('/update/doUpdate', ['uses' => 'InstallerController@doUpgrade', 'as'   => 'installer.doUpdate']);

// Creator Dashboard Routes
Route::prefix('creator')->name('creator.')->middleware(['auth', 'verified', '2fa'])->group(function () {
    Route::get('/', [App\Http\Controllers\CreatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/videos', [App\Http\Controllers\CreatorController::class, 'videos'])->name('videos');
    Route::get('/streams', [App\Http\Controllers\CreatorController::class, 'streams'])->name('streams');
    Route::get('/analytics', [App\Http\Controllers\CreatorController::class, 'analytics'])->name('analytics');
    Route::get('/settings', [App\Http\Controllers\CreatorController::class, 'settings'])->name('settings');
});

// (Feed/Search) Suggestions filter
Route::post('/suggestions/members', ['uses' => 'FeedController@filterSuggestedMembers', 'as'   => 'suggestions.filter']);

// Public pages
Route::get('/pages/{slug}', ['uses' => 'PublicPagesController@getPage', 'as'   => 'pages.get']);

Route::get('/search', ['uses' => 'SearchController@index', 'as' => 'search.get']);
Route::get('/search/posts', ['uses' => 'SearchController@getSearchPosts', 'as' => 'search.posts']);
Route::get('/search/users', ['uses' => 'SearchController@getUsersSearch', 'as' => 'search.users']);
Route::get('/search/streams', ['uses' => 'SearchController@getStreamsSearch', 'as' => 'search.streams']);

Route::post('/markBannerAsSeen', ['uses' => 'GenericController@markBannerAsSeen', 'as'   => 'banner.mark.seen']);

// All other existing routes continue here...
Route::get('/debug-test', function() {
    return 'Debug route works!';
});

// Storage and diagnostic routes
Route::get('/storage-check', function () {
    $disk = Storage::disk(config('filesystems.defaultFilesystemDriver'));
    $directories = [
        'posts/images',
        'posts/videos',
        'post/images',
        'post/videos'
    ];
    
    $results = [];
    foreach ($directories as $dir) {
        $results[$dir] = [
            'exists' => $disk->exists($dir),
            'writable' => is_writable(storage_path('app/public/' . $dir)),
            'url' => Storage::url($dir)
        ];
        
        // Try to create if doesn't exist
        if (!$disk->exists($dir)) {
            try {
                $disk->makeDirectory($dir);
                $results[$dir]['created'] = true;
            } catch (\Exception $e) {
                $results[$dir]['error'] = $e->getMessage();
            }
        }
    }
    
    // Test file creation
    $testFile = 'posts/images/test_' . uniqid() . '.txt';
    try {
        $disk->put($testFile, 'Storage test file');
        $results['test_file'] = [
            'created' => true,
            'path' => $testFile,
            'url' => Storage::url($testFile)
        ];
    } catch (\Exception $e) {
        $results['test_file'] = [
            'created' => false,
            'error' => $e->getMessage()
        ];
    }
    
    return response()->json($results);
});

// Add a test route for attachment upload
Route::get('/test-upload-form', function () {
    return view('test-upload');
});

// Add a route to check the attachments table structure
Route::get('/check-attachments-table', function () {
    try {
        $columns = DB::select('SHOW COLUMNS FROM attachments');
        
        $columnList = [];
        foreach ($columns as $column) {
            $columnList[] = [
                'name' => $column->Field,
                'type' => $column->Type,
                'null' => $column->Null,
                'key' => $column->Key,
                'default' => $column->Default,
                'extra' => $column->Extra
            ];
        }
        
        return response()->json([
            'success' => true,
            'columns' => $columnList
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Add a test route to check if uploads are working
Route::get('/test-uploads', function () {
    return view('test-upload');
});

// Add a test route to process uploads
Route::post('/test-upload-process', function (Illuminate\Http\Request $request) {
    try {
        if (!$request->hasFile('file')) {
            return response()->json(['success' => false, 'message' => 'No file provided']);
        }
        
        $file = $request->file('file');
        $path = $file->store('test_uploads', 'public');
        
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'path' => $path,
            'url' => Storage::url($path)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
});

// Add a route to check storage configuration
Route::get('/check-storage', function () {
    $storage = Storage::disk(config('filesystems.defaultFilesystemDriver'));
    $publicPath = Storage::url('');
    $attachments = \App\Model\Attachment::latest()->take(10)->get();
    
    return [
        'storage_driver' => config('filesystems.defaultFilesystemDriver'),
        'storage_path' => config('filesystems.disks.public.root'),
        'public_url' => $publicPath,
        'directory_exists' => $storage->exists('post/images'),
        'recent_attachments' => $attachments->map(function($attachment) {
            return [
                'id' => $attachment->id,
                'filename' => $attachment->filename,
                'path' => Storage::url($attachment->filename),
                'exists' => Storage::disk(config('filesystems.defaultFilesystemDriver'))->exists($attachment->filename),
                'mime_type' => $attachment->mime_type,
                'file_size' => $attachment->file_size,
                'has_thumbnail' => $attachment->has_thumbnail,
                'post_id' => $attachment->post_id,
                'user_id' => $attachment->user_id
            ];
        })
    ];
})->middleware(['auth']);

Route::get('/storage-diagnostic', function () {
    $publicPath = public_path();
    $storagePath = storage_path('app/public');
    $symlinkExists = file_exists(public_path('storage'));
    $storagePathExists = file_exists(storage_path('app/public'));
    
    // Check if symlink is properly created
    if (!$symlinkExists) {
        // Create symlink if it doesn't exist
        try {
            symlink(storage_path('app/public'), public_path('storage'));
            $symlinkExists = true;
            $symlinkCreated = true;
        } catch (\Exception $e) {
            $symlinkError = $e->getMessage();
        }
    }
    
    // Check for recent attachments
    $recentAttachments = \App\Model\Attachment::latest()->take(5)->get();
    
    return [
        'public_path' => $publicPath,
        'storage_path' => $storagePath,
        'symlink_exists' => $symlinkExists,
        'symlink_created' => $symlinkCreated ?? false,
        'symlink_error' => $symlinkError ?? null,
        'storage_path_exists' => $storagePathExists,
        'filesystem_driver' => config('filesystems.default'),
        'public_disk_root' => config('filesystems.disks.public.root'),
        'recent_attachments' => $recentAttachments->map(function($attachment) {
            return [
                'id' => $attachment->id,
                'filename' => $attachment->filename,
                'full_path' => storage_path('app/public/' . $attachment->filename),
                'url_path' => asset('storage/' . $attachment->filename),
                'exists' => file_exists(storage_path('app/public/' . $attachment->filename)),
                'post_id' => $attachment->post_id
            ];
        })
    ];
});

Route::get('/test-upload', function() {
    return view('test-upload');
})->middleware(['auth']);

Route::get('/file-paths', function () {
    // Create test directories if they don't exist
    $dirs = [
        'post/images',
        'post/videos',
        'post/videos/thumbnails',
        'posts/images',
        'posts/videos',
    ];
    
    $fileSystem = config('filesystems.defaultFilesystemDriver', 'public');
    $storage = Storage::disk($fileSystem);
    
    foreach ($dirs as $dir) {
        if (!$storage->exists($dir)) {
            $storage->makeDirectory($dir);
        }
    }
    
    // Check paths
    $paths = [
        'post_path' => Storage::url('post/images/test.jpg'),
        'posts_path' => Storage::url('posts/images/test.jpg'),
        'storage_path' => storage_path('app/public'),
        'public_path' => public_path('storage'),
        'symlink_exists' => file_exists(public_path('storage')),
        'filesystem_driver' => config('filesystems.defaultFilesystemDriver'),
        'storage_url_base' => Storage::url(''),
        'app_url' => env('APP_URL'),
        'dirs' => $dirs,
        'dir_exists' => [],
    ];
    
    // Check if directories exist
    foreach ($dirs as $dir) {
        $paths['dir_exists'][$dir] = $storage->exists($dir);
    }
    
    // Show a few sample attachments
    $attachments = \App\Model\Attachment::latest()->take(5)->get();
    $sampleAttachments = [];
    foreach ($attachments as $attachment) {
        $sampleAttachments[] = [
            'id' => $attachment->id,
            'filename' => $attachment->filename,
            'path' => $attachment->path,
            'thumbnail' => $attachment->thumbnail,
            'normalized_path' => str_replace('posts/images', 'post/images', $attachment->path),
        ];
    }
    $paths['attachments'] = $sampleAttachments;
    
    return $paths;
})->middleware(['auth']);

// Add a path compatibility route
Route::get('/storage/{type}/{dirname}/{filename}', function ($type, $dirname, $filename) {
    $alternateType = ($type == 'post') ? 'posts' : 'post';
    $originalPath = "storage/{$type}/{$dirname}/{$filename}";
    $alternatePath = "storage/{$alternateType}/{$dirname}/{$filename}";
    
    // Check if the original file exists
    if (file_exists(public_path($originalPath))) {
        return redirect($originalPath);
    }
    
    // Check if the alternate path exists
    if (file_exists(public_path($alternatePath))) {
        return redirect($alternatePath);
    }
    
    // If neither exists, check storage directly
    $storageOriginalPath = "app/public/{$type}/{$dirname}/{$filename}";
    $storageAlternatePath = "app/public/{$alternateType}/{$dirname}/{$filename}";
    
    try {
        if (file_exists(storage_path($storageOriginalPath))) {
            // Make sure directory exists
            if (!file_exists(dirname(public_path($originalPath)))) {
                mkdir(dirname(public_path($originalPath)), 0755, true);
            }
            // Copy to public path
            copy(storage_path($storageOriginalPath), public_path($originalPath));
            return redirect($originalPath);
        }
        
        if (file_exists(storage_path($storageAlternatePath))) {
            // Make sure directory exists
            if (!file_exists(dirname(public_path($alternatePath)))) {
                mkdir(dirname(public_path($alternatePath)), 0755, true);
            }
            // Copy to public path
            copy(storage_path($storageAlternatePath), public_path($alternatePath));
            return redirect($alternatePath);
        }
    } catch (\Exception $e) {
        \Log::error('Path compatibility route error: ' . $e->getMessage());
    }
    
    // Return fallback image if the file is not found anywhere
    return redirect(asset('img/default-post-image.jpg'));
})->where('filename', '.*');

// Add a route for the fixed upload interface
Route::get('/test-upload-fixed', function() {
    return view('test-upload-fixed');
})->middleware(['auth']);

// Route to seed cryptocurrency data
Route::get('/seed-cryptocurrency', function () {
    try {
        // Get the first user
        $user = \App\User::first();
        
        if (!$user) {
            return 'No users found. Please create a user first.';
        }
        
        // Sample cryptocurrencies data
        $cryptos = [
            [
                'name' => 'JustCoin',
                'symbol' => 'JCOIN',
                'description' => 'JustCoin is a utility token for the platform. It can be used to purchase premium content, subscribe to creators, and reward high-quality content.',
                'initial_price' => 0.01,
                'current_price' => 0.015,
                'total_supply' => 1000000,
                'available_supply' => 800000,
                'blockchain_network' => 'binance',
                'logo' => null, // No logo initially
                'website' => 'https://example.com/justcoin',
                'whitepaper' => 'https://example.com/justcoin/whitepaper',
                'creator_fee_percentage' => 5.00,
                'platform_fee_percentage' => 2.50,
                'liquidity_pool_percentage' => 20.00,
                'token_type' => 'utility',
                'enable_burning' => true,
                'enable_minting' => false,
                'transferable' => true,
                'is_verified' => true,
                'is_active' => true,
            ],
            [
                'name' => 'ContentCreator Token',
                'symbol' => 'CCT',
                'description' => 'ContentCreator Token (CCT) is designed for content creators. Holders can participate in governance and earn revenue share from platform fees.',
                'initial_price' => 0.05,
                'current_price' => 0.08,
                'total_supply' => 500000,
                'available_supply' => 350000,
                'blockchain_network' => 'ethereum',
                'logo' => null,
                'website' => 'https://example.com/cct',
                'whitepaper' => 'https://example.com/cct/whitepaper',
                'creator_fee_percentage' => 7.50,
                'platform_fee_percentage' => 2.00,
                'liquidity_pool_percentage' => 30.00,
                'token_type' => 'governance',
                'enable_burning' => true,
                'enable_minting' => true,
                'transferable' => true,
                'is_verified' => true,
                'is_active' => true,
            ],
            [
                'name' => 'FanCoin',
                'symbol' => 'FAN',
                'description' => 'FanCoin is a social token that rewards fans for their engagement and loyalty. Use it to access exclusive content and experiences.',
                'initial_price' => 0.001,
                'current_price' => 0.0025,
                'total_supply' => 10000000,
                'available_supply' => 8000000,
                'blockchain_network' => 'polygon',
                'logo' => null,
                'website' => 'https://example.com/fancoin',
                'whitepaper' => 'https://example.com/fancoin/whitepaper',
                'creator_fee_percentage' => 6.00,
                'platform_fee_percentage' => 1.50,
                'liquidity_pool_percentage' => 15.00,
                'token_type' => 'utility',
                'enable_burning' => false,
                'enable_minting' => true,
                'transferable' => true,
                'is_verified' => false,
                'is_active' => true,
            ]
        ];
        
        $output = [];
        $modelsNS = class_exists('App\\Models\\Cryptocurrency') ? 'App\\Models\\' : 'App\\Model\\';
        
        $cryptoClass = $modelsNS . 'Cryptocurrency';
        $walletClass = $modelsNS . 'CryptoWallet';
        $transactionClass = $modelsNS . 'CryptoTransaction';
        
        foreach ($cryptos as $index => $cryptoData) {
            // Check if cryptocurrency already exists
            $existingCrypto = $cryptoClass::where('symbol', $cryptoData['symbol'])->first();
            if ($existingCrypto) {
                $output[] = "Cryptocurrency with symbol {$cryptoData['symbol']} already exists. Skipping...";
                continue;
            }
            
            $output[] = "Creating cryptocurrency: {$cryptoData['name']} ({$cryptoData['symbol']})...";
            
            // Create the cryptocurrency
            $crypto = new $cryptoClass();
            $crypto->creator_user_id = $user->id;
            $crypto->name = $cryptoData['name'];
            $crypto->symbol = $cryptoData['symbol'];
            $crypto->description = $cryptoData['description'];
            $crypto->initial_price = $cryptoData['initial_price'];
            $crypto->current_price = $cryptoData['current_price'];
            $crypto->total_supply = $cryptoData['total_supply'];
            $crypto->available_supply = $cryptoData['available_supply'];
            $crypto->blockchain_network = $cryptoData['blockchain_network'];
            $crypto->logo = $cryptoData['logo'];
            $crypto->website = $cryptoData['website'];
            $crypto->whitepaper = $cryptoData['whitepaper'];
            $crypto->creator_fee_percentage = $cryptoData['creator_fee_percentage'];
            $crypto->platform_fee_percentage = $cryptoData['platform_fee_percentage'];
            $crypto->liquidity_pool_percentage = $cryptoData['liquidity_pool_percentage'];
            $crypto->token_type = $cryptoData['token_type'];
            $crypto->enable_burning = $cryptoData['enable_burning'];
            $crypto->enable_minting = $cryptoData['enable_minting'];
            $crypto->transferable = $cryptoData['transferable'];
            $crypto->is_verified = $cryptoData['is_verified'];
            $crypto->is_active = $cryptoData['is_active'];
            $crypto->contract_address = '0x' . \Illuminate\Support\Str::random(40); // Fake contract address
            
            $crypto->save();
            
            $output[] = "Cryptocurrency {$cryptoData['name']} created successfully.";
            
            // Create a wallet for the creator
            $creatorWallet = new $walletClass();
            $creatorWallet->user_id = $user->id;
            $creatorWallet->cryptocurrency_id = $crypto->id;
            $creatorWallet->balance = $crypto->total_supply * 0.1; // Creator gets 10% of total supply
            $creatorWallet->wallet_address = '0x' . \Illuminate\Support\Str::random(40);
            $creatorWallet->save();
            
            $output[] = "Created wallet for {$user->name} with balance: {$creatorWallet->balance} {$crypto->symbol}";
            
            // Create some transactions
            if (defined("$transactionClass::BUY_TYPE")) {
                $transactionTypes = [
                    $transactionClass::BUY_TYPE,
                    $transactionClass::SELL_TYPE,
                    $transactionClass::TRANSFER_TYPE,
                    $transactionClass::MINT_TYPE,
                    $transactionClass::REWARD_TYPE
                ];
                
                // Create 5-10 random transactions
                $numTransactions = rand(5, 10);
                $output[] = "Creating {$numTransactions} sample transactions for {$crypto->symbol}...";
                
                for ($i = 0; $i < $numTransactions; $i++) {
                    $type = $transactionTypes[array_rand($transactionTypes)];
                    $amount = rand(100, 1000);
                    $pricePerToken = $crypto->current_price * (rand(80, 120) / 100); // Random price fluctuation
                    $totalPrice = $amount * $pricePerToken;
                    $feeAmount = $totalPrice * ($crypto->platform_fee_percentage / 100);
                    
                    // Create transaction
                    $transaction = new $transactionClass();
                    $transaction->cryptocurrency_id = $crypto->id;
                    $transaction->buyer_user_id = $user->id;
                    $transaction->seller_user_id = ($type == $transactionClass::SELL_TYPE) ? null : $user->id;
                    $transaction->type = $type;
                    $transaction->amount = $amount;
                    $transaction->price_per_token = $pricePerToken;
                    $transaction->total_price = $totalPrice;
                    $transaction->fee_amount = $feeAmount;
                    $transaction->transaction_hash = '0x' . \Illuminate\Support\Str::random(64);
                    $transaction->status = $transactionClass::COMPLETED_STATUS;
                    $transaction->created_at = now()->subDays(rand(1, 30));
                    $transaction->save();
                }
                
                $output[] = "Created {$numTransactions} transactions for {$crypto->symbol}";
            } else {
                $output[] = "Warning: Transaction types not defined, skipping transaction creation";
            }
        }
        
        return response()->view('cryptocurrency.seeded', ['output' => $output]);
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage() . "<br>Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// Route to test cryptocurrency UI components
Route::get('/cryptocurrency/test-ui', function () {
    return view('cryptocurrency.test');
});

// Route for cryptocurrency installation instructions
Route::get('/crypto-instructions', function () {
    return response()->file(public_path('cryptocurrency-instructions.php'));
});

// API routes for streams
Route::group(['prefix' => 'api'], function () {
    Route::post('/streams/{stream}/start', 'StreamController@start');
    Route::post('/streams/{stream}/end', 'StreamController@end');
    Route::post('/streams/{stream}/answer', 'StreamController@answer');
    Route::post('/streams/{stream}/ice-candidate', 'StreamController@iceCandididate');
    Route::get('/streams/{stream}/messages', 'StreamController@getMessages');
    Route::post('/streams/{stream}/messages', 'StreamController@sendMessage');
});

// Public profile - MOVED TO END TO AVOID CONFLICTS
Route::get('/{username}', ['uses' => 'ProfileController@index', 'as'   => 'profile']);
Route::get('/{username}/posts', ['uses' => 'ProfileController@getUserPosts', 'as'   => 'profile.posts']);
Route::get('/{username}/streams', ['uses' => 'ProfileController@getUserStreams', 'as'   => 'profile.streams']);

Route::fallback(function () {
    abort(404);
});



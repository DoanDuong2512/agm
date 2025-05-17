<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Route mặc định cho broadcasting authentication
        // Chỉ dùng cho web auth, không liên quan đến customer
        Broadcast::routes(['middleware' => ['web', 'auth']]);

        // Route xác thực websocket với JWT token cho customer guard
        // Thêm prefix để tránh xung đột route
        Broadcast::routes([
            'middleware' => ['customer'],
            'prefix' => 'api/customer'
        ]);
        // Route xác thực websocket với JWT token cho admin guard
        // Thêm prefix để tránh xung đột route
        Broadcast::routes([
            'middleware' => ['admin'],
            'prefix' => 'api/admin'
        ]);

        // Cấu hình channel authorization
        require base_path('routes/channels.php');
    }
}

<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Customer;
use App\Models\ConversationParticipant;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Đăng ký kênh trò chuyện private (prefix 'private-' tự động được thêm vào khi dùng Echo.private()
Broadcast::channel('conversation.{id}', function ($user, $id) {
    // Debug để kiểm tra user type khi authenticate channel
    \Illuminate\Support\Facades\Log::debug('Channel auth attempt (private)', [
        'user_type' => get_class($user),
        'user_id' => $user->id,
        'channel_id' => $id
    ]);

    // Kiểm tra xem user có phải participant trong conversation này không
    $isParticipant = ConversationParticipant::where('conversation_id', $id)
        ->where(function ($query) use ($user) {
            if ($user instanceof User) {
                $query->where('participant_id', $user->id)
                    ->where('participant_type', User::class);
            } else if ($user instanceof Customer) {
                $query->where('participant_id', $user->id)
                    ->where('participant_type', Customer::class);
            }
        })
        ->exists();

    \Illuminate\Support\Facades\Log::debug('Channel auth result (private)', [
        'is_participant' => $isParticipant
    ]);

    return $isParticipant;
});

// Đăng ký kênh presence cho cuộc trò chuyện (prefix 'presence-' tự động được thêm vào khi dùng Echo.join())
Broadcast::channel('presence-conversation.{id}', function ($user, $id) {
    // Debug để kiểm tra user type khi authenticate channel
    \Illuminate\Support\Facades\Log::debug('Channel auth attempt (presence)', [
        'user_type' => get_class($user),
        'user_id' => $user->id,
        'channel_id' => $id
    ]);

    // Kiểm tra xem user có phải participant trong conversation này không
    $participant = ConversationParticipant::where('conversation_id', $id)
        ->where(function ($query) use ($user) {
            if ($user instanceof User) {
                $query->where('participant_id', $user->id)
                    ->where('participant_type', User::class);
            } else if ($user instanceof Customer) {
                $query->where('participant_id', $user->id)
                    ->where('participant_type', Customer::class);
            }
        })
        ->first();

    // Nếu không phải là người tham gia, từ chối kết nối
    if (!$participant) {
        \Illuminate\Support\Facades\Log::debug('Channel auth failed: not a participant');
        return false;
    }

    // Trả về thông tin người dùng cho kênh presence
    // Các thông tin này sẽ có sẵn cho tất cả client khác
    $userData = [
        'id' => $user->id,
        'name' => $user->name,
        'type' => ($user instanceof Customer) ? 'customer' : 'user'
    ];

    \Illuminate\Support\Facades\Log::debug('Channel auth success (presence)', [
        'user_data' => $userData
    ]);

    return $userData;
});

// Kênh riêng cho admin - chỉ user đã xác thực mới có thể truy cập
Broadcast::channel('admin-notifications', function ($user) {
    // Debug để kiểm tra user type khi authenticate channel
    \Illuminate\Support\Facades\Log::debug('Admin channel auth attempt', [
        'user_type' => get_class($user),
        'user_id' => $user->id,
    ]);

    // Kiểm tra xem user có phải là admin không
    if ($user instanceof \App\Models\User) {
        // Có thể thêm logic kiểm tra quyền admin ở đây nếu cần
        // Ví dụ: if ($user->hasRole('admin'))
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'type' => 'admin'
        ];
    }

    \Illuminate\Support\Facades\Log::debug('Admin channel auth failed: not an admin user');
    return false;
});

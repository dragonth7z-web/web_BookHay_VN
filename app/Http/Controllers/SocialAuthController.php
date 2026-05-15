<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Notification;
use App\Models\SystemLog;
use App\Models\User;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect sang trang đăng nhập của provider (Google / Facebook).
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Xử lý callback sau khi user đồng ý đăng nhập.
     */
    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            SystemLog::ghi('security', 'oauth_failed', "OAuth {$provider} thất bại: " . $e->getMessage(), 'warning');
            return redirect()->route('login')
                ->with('error', 'Đăng nhập bằng ' . ucfirst($provider) . ' thất bại. Vui lòng thử lại.');
        }

        // Tìm hoặc tạo user
        $user = $this->findOrCreateUser($socialUser, $provider);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Không thể tạo tài khoản. Vui lòng thử lại.');
        }

        // Kiểm tra tài khoản bị khóa
        if ($user->status->value === 'suspended') {
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị tạm khóa.');
        }

        // Thiết lập session
        session([
            'user_id'     => $user->id,
            'user_name'   => $user->name,
            'user_email'  => $user->email,
            'user_role'   => $user->role_id,
            'user_avatar' => $user->avatar,
        ]);

        SystemLog::ghi('auth', 'login', "{$user->name} đăng nhập qua {$provider}", 'info', 'User', $user->id);

        return redirect()->intended(route('home'))
            ->with('success', 'Chào mừng ' . $user->name . '!');
    }

    /**
     * Tìm user theo email hoặc tạo mới nếu chưa có.
     */
    private function findOrCreateUser($socialUser, string $provider): ?User
    {
        $email = $socialUser->getEmail();

        if (!$email) {
            return null;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // Cập nhật avatar nếu chưa có
            if (!$user->avatar && $socialUser->getAvatar()) {
                $user->update(['avatar' => $socialUser->getAvatar()]);
            }
            return $user;
        }

        // Tạo user mới từ OAuth
        $user = User::create([
            'name'           => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Người dùng',
            'email'          => $email,
            'password'       => Hash::make(Str::random(32)), // Password ngẫu nhiên, user không cần dùng
            'avatar'         => $socialUser->getAvatar(),
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 100,
            'total_spent'    => 0,
        ]);

        // Tạo cart mặc định
        Cart::firstOrCreate(['user_id' => $user->id]);

        // Welcome notification
        Notification::create([
            'user_id'    => $user->id,
            'type'       => NotificationType::Promotion,
            'title'      => '🎉 Chào mừng đến với THLD Bookstore!',
            'content'    => 'Tài khoản đã được tạo qua ' . ucfirst($provider) . '. Bạn nhận được 100 điểm thưởng chào mừng.',
            'url'        => route('account.dashboard'),
            'is_read'    => false,
            'read_at'    => null,
            'created_at' => now(),
        ]);

        SystemLog::ghi('data', 'create', "Tài khoản mới qua {$provider}: {$user->name} ({$email})", 'info', 'User', $user->id);

        return $user;
    }

    /**
     * Chỉ cho phép provider hợp lệ.
     */
    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }
    }
}

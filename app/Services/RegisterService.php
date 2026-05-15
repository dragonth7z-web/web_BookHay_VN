<?php

namespace App\Services;

use App\Enums\CouponStatus;
use App\Enums\CouponType;
use App\Enums\NotificationType;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\SystemLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    /**
     * Tạo user mới từ dữ liệu đã validate.
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name'           => $data['ho_ten'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'phone'          => $data['so_dien_thoai'] ?: null,
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 100, // Tặng 100 điểm chào mừng
            'total_spent'    => 0,
        ]);
    }

    /**
     * Tạo giỏ hàng mặc định cho user mới.
     */
    public function createDefaultCart(User $user): void
    {
        Cart::firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * Tặng coupon chào mừng WELCOME10 cho user mới.
     * Tạo coupon nếu chưa tồn tại, hoặc dùng coupon có sẵn.
     */
    public function grantWelcomeCoupon(User $user): ?Coupon
    {
        // Tìm coupon WELCOME10 đang active
        $coupon = Coupon::where('code', 'WELCOME10')
            ->where('status', CouponStatus::Active)
            ->where('expires_at', '>=', now())
            ->first();

        // Nếu chưa có thì tạo mới
        if (!$coupon) {
            $coupon = Coupon::create([
                'code'             => 'WELCOME10',
                'name'             => 'Chào mừng thành viên mới',
                'type'             => CouponType::Percentage,
                'value'            => 10,
                'max_discount'     => 50000,
                'min_order_amount' => 100000,
                'usage_limit'      => null, // Không giới hạn
                'used_count'       => 0,
                'starts_at'        => now(),
                'expires_at'       => now()->addDays(30),
                'status'           => CouponStatus::Active,
                'ui_icon'          => null,
                'theme_class'      => null,
                'overlay_gradient' => null,
                'glow_color'       => null,
            ]);
        }

        return $coupon;
    }

    /**
     * Gửi welcome notification trong hệ thống.
     */
    public function sendWelcomeNotification(User $user, ?Coupon $coupon): void
    {
        $couponText = $coupon
            ? " Dùng mã **{$coupon->code}** để giảm 10% đơn hàng đầu tiên (tối đa 50.000đ)."
            : '';

        Notification::create([
            'user_id'    => $user->id,
            'type'       => NotificationType::Promotion,
            'title'      => '🎉 Chào mừng đến với THLD Bookstore!',
            'content'    => 'Tài khoản của bạn đã được tạo thành công. Bạn nhận được 100 điểm thưởng chào mừng.' . $couponText,
            'url'        => route('account.coupons'),
            'is_read'    => false,
            'read_at'    => null,
            'created_at' => now(),
        ]);
    }

    /**
     * Ghi log đăng ký kèm IP và user agent.
     */
    public function logRegistration(User $user, Request $request): void
    {
        $ip        = $request->ip();
        $userAgent = substr($request->userAgent() ?? 'unknown', 0, 200);

        SystemLog::ghi(
            'data',
            'create',
            "Tài khoản mới: {$user->name} ({$user->email}) | IP: {$ip} | UA: {$userAgent}",
            'info',
            'User',
            $user->id
        );
    }

    /**
     * Thiết lập session sau khi đăng ký thành công.
     */
    public function setupSession(User $user): void
    {
        session([
            'user_id'     => $user->id,
            'user_name'   => $user->name,
            'user_email'  => $user->email,
            'user_role'   => $user->role_id,
            'user_avatar' => $user->avatar,
        ]);
    }
}

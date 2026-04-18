<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SystemLog;

class AuthController extends Controller
{
    // ───────── Login ─────────
    public function showLogin()
    {
        if (session('user_id')) {
            return redirect()->route('account.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        $user = User::where('email', $request->email)
            ->where('status', 'active')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            SystemLog::ghi('security', 'login_failed', 'Đăng nhập thất bại với email: ' . $request->email, 'warning');
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput();
        }

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role_id,
            'user_avatar' => $user->avatar,
        ]);

        if ($request->remember) {
            cookie()->queue('remember_email', $user->email, 60 * 24 * 30);
        }

        $roleName = match ($user->role_id) {
            1 => 'Admin',
            3 => 'Staff',
            default => 'Customer',
        };
        SystemLog::ghi('auth', 'login', $user->name . ' đã đăng nhập thành công (Vai trò: ' . $roleName . ')', 'info');

        if (in_array($user->role_id, [1, 3])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    // ───────── Register ─────────
    public function showRegister()
    {
        if (session('user_id')) {
            return redirect()->route('account.dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        $user = User::create([
            'name' => $request->ho_ten,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->so_dien_thoai,
            'role_id' => 2,
            'status' => 'active',
            'loyalty_points' => 0,
            'total_spent' => 0,
        ]);

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role_id,
        ]);

        SystemLog::ghi('data', 'create', 'Tài khoản mới được tạo: ' . $user->name . ' (' . $user->email . ')', 'info', 'User', $user->id);

        return redirect()->route('account.dashboard')->with('success', 'Chào mừng ' . $user->name . '! Tài khoản đã được tạo thành công.');
    }

    // ───────── Logout ─────────
    public function logout(Request $request)
    {
        $userName = session('user_name', 'Người dùng');
        // Ghi log trước khi xóa session
        SystemLog::ghi('auth', 'logout', $userName . ' đã đăng xuất', 'info');
        session()->flush();
        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công.');
    }

    // ───────── Forgot Password ─────────
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // Always show success message (security: don't reveal if email exists)
        return back()->with('status', 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi link đặt lại mật khẩu.');
    }
}

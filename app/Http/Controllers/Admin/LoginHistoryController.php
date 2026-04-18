<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
    public function index()
    {
        $histories = LoginHistory::with('user')->orderByDesc('id')->paginate(20);
        return view('admin.login-histories.index', compact('histories'));
    }

    public function show(LoginHistory $loginHistory)
    {
        return view('admin.login-histories.show', compact('loginHistory'));
    }

    public function destroy(LoginHistory $loginHistory)
    {
        $loginHistory->delete();
        return redirect()->route('admin.login-histories.index')->with('success', 'Xóa thành công.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderByDesc('created_at')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(\Illuminate\Http\Request $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'phone', 'status', 'role_id']));
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa thành công.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->orderByDesc('created_at')->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        Notification::create($request->only(['user_id', 'type', 'title', 'content', 'url']));
        return redirect()->route('admin.notifications.index')->with('success', 'Gửi thông báo thành công.');
    }

    public function show(Notification $notification)
    {
        return view('admin.notifications.show', compact('notification'));
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Xóa thành công.');
    }
}

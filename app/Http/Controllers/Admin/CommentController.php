<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['book', 'user'])->orderByDesc('created_at')->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        return view('admin.comments.show', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->update($request->only(['status']));
        return redirect()->route('admin.comments.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Xóa thành công.');
    }
}

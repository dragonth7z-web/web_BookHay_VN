<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::orderBy('name')->paginate(20);
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $author = Author::create($request->only(['name', 'slug', 'country', 'biography', 'avatar']));
        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm tác giả: ' . $author->name,
            level: 'info',
            objectType: 'Author',
            objectId: $author->id
        );
        return redirect()->route('admin.authors.index')->with('success', 'Thêm thành công.');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $author->update($request->only(['name', 'slug', 'country', 'biography', 'avatar']));
        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật tác giả: ' . $author->name,
            level: 'info',
            objectType: 'Author',
            objectId: $author->id
        );
        return redirect()->route('admin.authors.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Author $author)
    {
        $id = $author->id;
        $name = $author->name;
        $author->delete();
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa tác giả: ' . $name,
            level: 'warning',
            objectType: 'Author',
            objectId: $id
        );
        return redirect()->route('admin.authors.index')->with('success', 'Xóa thành công.');
    }
}

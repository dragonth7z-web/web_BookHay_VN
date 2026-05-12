<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\SystemLog;
use App\Services\AuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        private AuthorService $authorService
    ) {}

    public function index()
    {
        $authors = $this->authorService->getPaginatedAuthors();
        $stats   = $this->authorService->getStats();

        return view('admin.authors.index', compact('authors', 'stats'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'slug'    => 'required|string|max:255|unique:authors,slug',
            'country' => 'nullable|string|max:100',
            'avatar'  => 'nullable|image|max:8192',
        ]);

        $author = $this->authorService->createAuthor(
            $request->only(['name', 'slug', 'country', 'biography']),
            $request->file('avatar')
        );

        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm tác giả: ' . $author->name,
            level: 'info',
            objectType: 'Author',
            objectId: $author->id
        );

        return redirect()->route('admin.authors.index')->with('success', 'Thêm tác giả thành công.');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'slug'    => 'required|string|max:255|unique:authors,slug,' . $author->id,
            'country' => 'nullable|string|max:100',
            'avatar'  => 'nullable|image|max:8192',
        ]);

        $this->authorService->updateAuthor(
            $author,
            $request->only(['name', 'slug', 'country', 'biography']),
            $request->file('avatar')
        );

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
        $id   = $author->id;
        $name = $author->name;

        $this->authorService->deleteAuthor($author);

        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa tác giả: ' . $name,
            level: 'warning',
            objectType: 'Author',
            objectId: $id
        );

        return redirect()->route('admin.authors.index')->with('success', 'Đã xóa tác giả.');
    }
}

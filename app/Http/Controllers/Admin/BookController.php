<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Models\Book;
use App\Models\SystemLog;
use App\Repositories\BookRepository;
use App\Services\BookService;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    public function __construct(
        private BookService $service,
        private BookRepository $repo
    ) {}

    public function index()
    {
        $books = $this->repo->paginatedWithRelations(20);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = $this->repo->getAllCategories();
        $publishers = $this->repo->getAllPublishers();
        $authors    = $this->repo->getAllAuthors();
        return view('admin.books.create', compact('categories', 'publishers', 'authors'));
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->service->create(
            $request->except(['cover_image', 'author_ids', '_token']),
            $request->file('cover_image'),
            $request->input('author_ids', [])
        );
        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm sách mới: ' . $book->title,
            level: 'info',
            objectType: 'Book',
            objectId: $book->id
        );
        
        // Clear homepage latest books cache
        Cache::forget('home_latest_books_12');
        
        return redirect()->route('admin.books.index')->with('success', 'Thêm sách thành công.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'publisher', 'authors']);
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = $this->repo->getAllCategories();
        $publishers = $this->repo->getAllPublishers();
        $authors    = $this->repo->getAllAuthors();
        return view('admin.books.edit', compact('book', 'categories', 'publishers', 'authors'));
    }

    public function update(StoreBookRequest $request, Book $book)
    {
        $this->service->update(
            $book,
            $request->except(['cover_image', 'author_ids', '_token', '_method']),
            $request->file('cover_image'),
            $request->input('author_ids', [])
        );
        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật sách: ' . $book->title,
            level: 'info',
            objectType: 'Book',
            objectId: $book->id
        );
        
        // Clear homepage latest books cache
        Cache::forget('home_latest_books_12');
        
        return redirect()->route('admin.books.index')->with('success', 'Cập nhật sách thành công.');
    }

    public function destroy(Book $book)
    {
        $id = $book->id;
        $title = $book->title;
        $this->service->delete($book);
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa sách: ' . $title,
            level: 'warning',
            objectType: 'Book',
            objectId: $id
        );
        
        // Clear homepage latest books cache
        Cache::forget('home_latest_books_12');
        
        return redirect()->route('admin.books.index')->with('success', 'Xóa sách thành công.');
    }
}

<?php

namespace App\Repositories;

use App\Contracts\Repositories\AuthorRepositoryInterface;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function paginatedWithBookCount(int $perPage = 10): LengthAwarePaginator
    {
        return Author::withCount('books')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function getStats(): array
    {
        return [
            'total'      => Author::count(),
            'active'     => Author::whereHas('books')->count(),
            'countries'  => Author::whereNotNull('country')->distinct('country')->count('country'),
            'totalBooks' => Book::whereHas('authors')->count(),
        ];
    }

    public function create(array $data): Author
    {
        return Author::create($data);
    }

    public function update(Author $author, array $data): Author
    {
        $author->update($data);
        return $author->fresh();
    }

    public function delete(Author $author): void
    {
        $author->delete();
    }
}

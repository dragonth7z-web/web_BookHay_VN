<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\BookRepository;
use App\Traits\UploadsFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookService
{
    use UploadsFile;

    public function __construct(private BookRepository $repo)
    {
    }

    public function create(array $data, ?UploadedFile $coverImage, array $authorIds): Book
    {
        if ($coverImage) {
            $data['cover_image'] = $this->uploadFile($coverImage, 'books');
        }

        $book = Book::create($data);
        $book->authors()->sync($authorIds);

        return $book;
    }

    public function update(Book $book, array $data, ?UploadedFile $coverImage, array $authorIds): Book
    {
        if ($coverImage) {
            if ($book->cover_image) {
                Storage::delete($book->cover_image);
            }
            $data['cover_image'] = $this->uploadFile($coverImage, 'books');
        }

        $book->update($data);
        $book->authors()->sync($authorIds);

        return $book;
    }

    public function delete(Book $book): void
    {
        if ($book->cover_image) {
            Storage::delete($book->cover_image);
        }
        $book->delete();
    }

    /**
     * Two-pass fuzzy search for search suggestions API.
     * Pass 1: SQL LIKE (fast). Pass 2: bigram scoring (typo-tolerant).
     */
    public function fuzzySearch(string $query, int $limit = 6): Collection
    {
        $pass1 = $this->repo->fuzzySearchPass1($query, $limit);

        if ($pass1->isNotEmpty()) {
            return $pass1;
        }

        return $this->repo->fuzzySearchPass2($query, $limit);
    }

    /**
     * Get book IDs matching a fuzzy search query — used for paginated listing.
     */
    public function getFuzzySearchIds(string $query): Collection
    {
        return $this->repo->getFuzzySearchIds($query);
    }
}

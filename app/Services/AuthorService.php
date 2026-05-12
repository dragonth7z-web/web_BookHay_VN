<?php

namespace App\Services;

use App\Contracts\Repositories\AuthorRepositoryInterface;
use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AuthorService
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository
    ) {}

    public function getPaginatedAuthors(): LengthAwarePaginator
    {
        return $this->authorRepository->paginatedWithBookCount();
    }

    public function getStats(): array
    {
        return $this->authorRepository->getStats();
    }

    public function createAuthor(array $data, ?UploadedFile $avatar): Author
    {
        if ($avatar) {
            $data['avatar'] = $avatar->store('authors', 'public');
        }

        return $this->authorRepository->create($data);
    }

    public function updateAuthor(Author $author, array $data, ?UploadedFile $avatar): Author
    {
        if ($avatar) {
            if ($author->avatar) {
                Storage::disk('public')->delete($author->avatar);
            }
            $data['avatar'] = $avatar->store('authors', 'public');
        }

        return $this->authorRepository->update($author, $data);
    }

    public function deleteAuthor(Author $author): void
    {
        if ($author->avatar) {
            Storage::disk('public')->delete($author->avatar);
        }

        $this->authorRepository->delete($author);
    }
}

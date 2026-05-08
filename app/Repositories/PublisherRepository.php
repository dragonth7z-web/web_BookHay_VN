<?php

namespace App\Repositories;

use App\Models\Publisher;
use App\Contracts\Repositories\PublisherRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PublisherRepository implements PublisherRepositoryInterface
{
    public function partners(): Collection
    {
        return Publisher::where('is_partner', true)->get();
    }

    public function all(): Collection
    {
        return Publisher::orderBy('name')->get();
    }

    public function paginated(int $perPage = 20): LengthAwarePaginator
    {
        return Publisher::withCount('books')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function getStats(): array
    {
        $total      = Publisher::count();
        $active     = Publisher::where('is_partner', true)->count();
        $totalBooks = \App\Models\Book::whereNotNull('publisher_id')->count();

        $newThisMonth = Publisher::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total'          => $total,
            'active'         => $active,
            'total_books'    => $totalBooks,
            'new_this_month' => $newThisMonth,
        ];
    }
}

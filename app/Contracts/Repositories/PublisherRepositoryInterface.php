<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PublisherRepositoryInterface
{
    public function partners(): Collection;

    public function all(): Collection;

    public function paginated(int $perPage = 20): LengthAwarePaginator;

    public function getStats(): array;
}

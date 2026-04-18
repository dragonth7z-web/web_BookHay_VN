<?php

namespace App\Repositories;

use App\Models\Publisher;
use App\Contracts\Repositories\PublisherRepositoryInterface;
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
}

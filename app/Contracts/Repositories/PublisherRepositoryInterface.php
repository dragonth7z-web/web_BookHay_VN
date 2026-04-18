<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface PublisherRepositoryInterface
{
    public function partners(): Collection;
    public function all(): Collection;
}

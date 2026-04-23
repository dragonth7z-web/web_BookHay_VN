<?php

namespace App\Contracts\Repositories;

use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Collection;

interface ShippingAddressRepositoryInterface
{
    public function getAllForUser(int $userId): Collection;

    public function findForUser(int $id, int $userId): ShippingAddress;

    public function create(int $userId, array $data): ShippingAddress;

    public function update(int $id, int $userId, array $data): ShippingAddress;

    public function delete(int $id, int $userId): void;

    public function setDefault(int $id, int $userId): void;
}

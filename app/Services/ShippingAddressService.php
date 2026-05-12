<?php

namespace App\Services;

use App\Contracts\Repositories\ShippingAddressRepositoryInterface;
use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Collection;

class ShippingAddressService
{
    public function __construct(
        private ShippingAddressRepositoryInterface $addressRepository
    ) {}

    public function getAddressesForUser(int $userId): Collection
    {
        return $this->addressRepository->getAllForUser($userId);
    }

    public function createAddress(int $userId, array $data): ShippingAddress
    {
        return $this->addressRepository->create($userId, $data);
    }

    public function updateAddress(int $id, int $userId, array $data): ShippingAddress
    {
        return $this->addressRepository->update($id, $userId, $data);
    }

    public function deleteAddress(int $id, int $userId): void
    {
        $this->addressRepository->delete($id, $userId);
    }

    public function setDefaultAddress(int $id, int $userId): void
    {
        $this->addressRepository->setDefault($id, $userId);
    }
}

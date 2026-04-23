<?php

namespace App\Repositories;

use App\Contracts\Repositories\ShippingAddressRepositoryInterface;
use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Collection;

class ShippingAddressRepository implements ShippingAddressRepositoryInterface
{
    public function getAllForUser(int $userId): Collection
    {
        return ShippingAddress::where('user_id', $userId)
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();
    }

    public function findForUser(int $id, int $userId): ShippingAddress
    {
        return ShippingAddress::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function create(int $userId, array $data): ShippingAddress
    {
        if (!empty($data['is_default'])) {
            ShippingAddress::where('user_id', $userId)->update(['is_default' => false]);
        }

        return ShippingAddress::create(array_merge($data, ['user_id' => $userId]));
    }

    public function update(int $id, int $userId, array $data): ShippingAddress
    {
        $address = $this->findForUser($id, $userId);

        if (!empty($data['is_default'])) {
            ShippingAddress::where('user_id', $userId)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $address->update($data);
        return $address->fresh();
    }

    public function delete(int $id, int $userId): void
    {
        ShippingAddress::where('id', $id)
            ->where('user_id', $userId)
            ->delete();
    }

    public function setDefault(int $id, int $userId): void
    {
        ShippingAddress::where('user_id', $userId)->update(['is_default' => false]);
        ShippingAddress::where('id', $id)->where('user_id', $userId)->update(['is_default' => true]);
    }
}

<?php

namespace App\Services;

use App\Models\Book;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function create(array $data, array $items, int $createdBy): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $items, $createdBy) {
            $po = PurchaseOrder::create(array_merge($data, [
                'created_by' => $createdBy,
                'created_at' => now(),
            ]));

            foreach ($items as $item) {
                if (empty($item['book_id']) || empty($item['quantity'])) {
                    continue;
                }

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'book_id'           => $item['book_id'],
                    'quantity'          => (int) $item['quantity'],
                    'cost_price'        => (float) ($item['unit_price'] ?? 0),
                ]);

                Book::where('id', $item['book_id'])->increment('stock', (int) $item['quantity']);
            }

            return $po;
        });
    }
}

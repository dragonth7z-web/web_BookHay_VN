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
            $po = PurchaseOrder::create(array_merge($data, ['created_by' => $createdBy]));

            foreach ($items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'book_id' => $item['book_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);

                Book::where('id', $item['book_id'])->increment('stock', $item['quantity']);
            }

            return $po;
        });
    }
}

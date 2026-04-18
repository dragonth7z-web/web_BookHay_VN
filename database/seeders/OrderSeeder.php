<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Book;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_items')->truncate();
        Order::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $customers = User::where('role_id', 2)->get();
        $books = Book::all();

        if ($customers->isEmpty() || $books->isEmpty()) {
            echo "⚠ Chưa có dữ liệu Customers / Books. Chạy UserSeeder + CustomerSeeder + BookSeeder trước.\n";
            return;
        }

        $statuses = ['completed', 'delivered', 'shipping', 'confirmed', 'pending', 'cancelled'];
        $cancelReasons = [
            'Đổi ý, không muốn mua nữa',
            'Tìm được giá rẻ hơn ở nơi khác',
            'Đặt nhầm sản phẩm',
            'Giao hàng quá lâu',
            'Lý do cá nhân',
        ];
        $payments = ['cod', 'vnpay', 'momo', 'bank_transfer'];

        // ── Tạo ~30 đơn hàng phân bố trong 30 ngày qua ──────────────────
        $ordersData = [];

        // 1) Đơn hàng lịch sử (15-30 ngày trước) — chủ yếu Hoàn thành
        for ($i = 0; $i < 10; $i++) {
            $ordersData[] = [
                'days_ago' => rand(15, 30),
                'status' => collect(['completed', 'completed', 'completed', 'cancelled'])->random(),
            ];
        }

        // 2) Đơn hàng tuần trước (7-14 ngày) — mix
        for ($i = 0; $i < 8; $i++) {
            $ordersData[] = [
                'days_ago' => rand(7, 14),
                'status' => collect(['completed', 'completed', 'delivered', 'cancelled'])->random(),
            ];
        }

        // 3) Đơn hàng tuần này (0-6 ngày) — nhiều trạng thái
        for ($i = 0; $i < 8; $i++) {
            $ordersData[] = [
                'days_ago' => rand(0, 6),
                'status' => collect(['completed', 'shipping', 'confirmed', 'pending', 'pending'])->random(),
            ];
        }

        // 4) Đơn hàng hôm nay — active
        for ($i = 0; $i < 4; $i++) {
            $ordersData[] = [
                'days_ago' => 0,
                'status' => collect(['pending', 'confirmed', 'completed'])->random(),
            ];
        }

        $orderCount = 0;
        foreach ($ordersData as $data) {
            $orderCount++;
            $customer = $customers->random();
            $orderDate = now()->subDays($data['days_ago'])->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $numBooks = rand(1, 3);
            $selectedBooks = $books->random(min($numBooks, $books->count()));

            $total_amount_of_goods = 0;
            $orderItems = [];

            foreach ($selectedBooks as $book) {
                $quantity = rand(1, 2);
                $unit_price = $book->sale_price;
                $subtotal = $unit_price * $quantity;
                $total_amount_of_goods += $subtotal;

                $orderItems[] = [
                    'book_id' => $book->id,
                    'book_title_snapshot' => $book->title,
                    'book_image_snapshot' => $book->cover_image,
                    'quantity' => $quantity,
                    'unit_price' => $unit_price,
                    'subtotal' => $subtotal,
                ];
            }

            $shipping_fee = $total_amount_of_goods >= 200000 ? 0 : 30000;
            $discount_amount = rand(0, 1) ? round($total_amount_of_goods * 0.1) : 0;
            $total = $total_amount_of_goods + $shipping_fee - $discount_amount;

            $status = $data['status'];
            $cancel_reason = null;
            $payment_status = 'unpaid';

            if ($status === 'cancelled') {
                $cancel_reason = $cancelReasons[array_rand($cancelReasons)];
                $payment_status = 'unpaid';
            }
            elseif (in_array($status, ['completed', 'delivered'])) {
                $payment_status = 'paid';
            }

            $order = Order::create([
                'order_number' => 'ORD-' . str_pad($orderCount, 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'recipient_name' => $customer->name,
                'recipient_phone' => $customer->phone ?? '0900000000',
                'shipping_address' => 'Số ' . rand(1, 200) . ', Đường ' . rand(1, 30) . ', Quận ' . rand(1, 12) . ', TP.HCM',
                'subtotal' => $total_amount_of_goods,
                'shipping_fee' => $shipping_fee,
                'discount_amount' => $discount_amount,
                'total' => $total,
                'payment_method' => $payments[array_rand($payments)],
                'payment_status' => $payment_status,
                'status' => $status,
                'notes' => null,
                'cancel_reason' => $cancel_reason,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            if (in_array($status, ['completed', 'delivered'])) {
                $customer->increment('total_spent', $total_amount_of_goods);
            }
        }

        echo "Seeded {$orderCount} orders with order details.\n";
    }
}

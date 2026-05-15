<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\PurchaseOrder;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function __construct(private PurchaseOrderService $service) {}

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['publisher', 'createdBy'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $publishers = Publisher::orderBy('name')->get();
        $books      = Book::orderBy('title')->get();

        return view('admin.purchase-orders.create', compact('publishers', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_number'    => 'required|string|max:20|unique:purchase_orders,po_number',
            'total_amount' => 'required|numeric|min:0',
            'publisher_id' => 'nullable|exists:publishers,id',
            'notes'        => 'nullable|string|max:1000',
            'items'        => 'required|array|min:1',
            'items.*.book_id'    => 'required|exists:books,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'po_number.required'         => 'Vui lòng nhập mã phiếu nhập.',
            'po_number.unique'           => 'Mã phiếu nhập đã tồn tại.',
            'total_amount.required'      => 'Vui lòng nhập tổng tiền.',
            'total_amount.min'           => 'Tổng tiền không được âm.',
            'items.required'             => 'Vui lòng thêm ít nhất 1 sách.',
            'items.*.book_id.required'   => 'Vui lòng chọn sách.',
            'items.*.book_id.exists'     => 'Sách không tồn tại trong hệ thống.',
            'items.*.quantity.required'  => 'Vui lòng nhập số lượng.',
            'items.*.quantity.min'       => 'Số lượng phải ít nhất là 1.',
            'items.*.unit_price.required'=> 'Vui lòng nhập giá nhập.',
            'items.*.unit_price.min'     => 'Giá nhập không được âm.',
        ]);

        // Dùng session user_id vì project dùng session-based auth
        $createdBy = session('user_id', 1);

        $this->service->create(
            $request->only(['po_number', 'publisher_id', 'total_amount', 'notes']),
            $request->input('items', []),
            $createdBy
        );

        return redirect()
            ->route('admin.purchase-orders.index')
            ->with('success', 'Tạo phiếu nhập thành công. Tồn kho đã được cập nhật.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['publisher', 'createdBy', 'items.book']);

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return redirect()
            ->route('admin.purchase-orders.index')
            ->with('success', 'Xóa phiếu nhập thành công.');
    }
}

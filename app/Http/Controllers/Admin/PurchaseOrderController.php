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
        $purchaseOrders = PurchaseOrder::with('publisher')->orderByDesc('created_at')->paginate(20);
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
        $this->service->create(
            $request->only(['po_number', 'publisher_id', 'total_amount', 'notes']),
            $request->input('items', []),
            auth()->id()
        );
        return redirect()->route('admin.purchase-orders.index')->with('success', 'Tạo phiếu nhập thành công.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['publisher', 'items.book']);
        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('admin.purchase-orders.index')->with('success', 'Xóa thành công.');
    }
}

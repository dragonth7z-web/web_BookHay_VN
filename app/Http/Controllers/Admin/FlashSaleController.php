<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Book;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class FlashSaleController extends Controller
{
    private const SLOT_COUNT = 8;

    public function index()
    {
        $flashSales = FlashSale::query()
            ->orderByDesc('start_date')
            ->paginate(10);

        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $books = Book::query()
            ->where('status', 'in_stock')
            ->orderBy('title')
            ->get();

        return view('admin.flash-sales.create', compact('books'));
    }

    public function store(Request $request)
    {
        // Normalize input: input type number rỗng thường submit là '' -> chuyển về null
        $items = $request->input('items', []);
        foreach ($items as $slot => &$item) {
            if (array_key_exists('flash_price', $item) && ($item['flash_price'] === '' || $item['flash_price'] === null)) {
                $item['flash_price'] = null;
            }
        }
        unset($item);
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'sale_name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'duration_hours' => 'required|integer|min:1',
            'items' => 'required|array',
            'items.*.book_id' => 'nullable|exists:books,id',
            'items.*.flash_price' => 'nullable|numeric|min:0',
        ]);

        $selected = collect($request->input('items', []))
            ->map(fn($x) => $x['book_id'] ?? null)
            ->filter()
            ->values();

        if ($selected->isEmpty()) {
            return back()->withErrors(['items' => 'Vui lòng chọn ít nhất 1 sách cho flash sale.'])->withInput();
        }

        if ($selected->unique()->count() !== $selected->count()) {
            return back()->withErrors(['items' => 'Mỗi sách chỉ được chọn 1 lần trong cùng một flash sale.'])->withInput();
        }

        $endDate = \Carbon\Carbon::parse($validated['start_date'])->addHours($validated['duration_hours']);

        $flashSaleId = null;
        DB::transaction(function () use ($validated, $request, $endDate, &$flashSaleId) {
            $flashSale = FlashSale::create([
                'name' => $validated['sale_name'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
            ]);
            $flashSaleId = $flashSale->id;

            foreach (range(1, self::SLOT_COUNT) as $slot) {
                $bookId = $request->input("items.$slot.book_id");
                if (empty($bookId)) {
                    continue;
                }

                $book = Book::query()->select(['id', 'sale_price'])->find($bookId);
                if (!$book) {
                    continue;
                }

                $flashPrice = $request->input("items.$slot.flash_price");
                $flashPrice = ($flashPrice === null || $flashPrice === '') ? (int) $book->sale_price : (int) $flashPrice;

                FlashSaleItem::create([
                    'flash_sale_id' => $flashSale->id,
                    'book_id' => $bookId,
                    'flash_price' => $flashPrice,
                    'display_order' => $slot,
                ]);
            }
        });

        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Tạo flash sale mới: ' . ($validated['sale_name'] ?? 'Không tên'),
            level: 'info',
            objectType: 'FlashSale',
            objectId: $flashSaleId
        );

        Cache::forget('home_flash_sale');
        Cache::forget('home_latest_books_15');

        return redirect()->route('admin.flash-sales.index')->with('success', 'Tạo flash sale thành công!');
    }

    public function show(FlashSale $flashSale)    {
        $flashSale->load('items.book');
        return view('admin.flash-sales.edit', [
            'flashSale' => $flashSale,
        ]);
    }
    public function edit(FlashSale $flashSale)
    {
        $flashSale->load('items.book');

        $books = Book::query()
            ->where('status', 'in_stock')
            ->orderBy('title')
            ->get();

        $selectedBySlot = $flashSale->items->keyBy('display_order');

        return view('admin.flash-sales.edit', compact('flashSale', 'books', 'selectedBySlot'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        // Normalize input: input type number rỗng thường submit là '' -> chuyển về null
        $items = $request->input('items', []);
        foreach ($items as $slot => &$item) {
            if (array_key_exists('flash_price', $item) && ($item['flash_price'] === '' || $item['flash_price'] === null)) {
                $item['flash_price'] = null;
            }
        }
        unset($item);
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'sale_name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'duration_hours' => 'required|integer|min:1',
            'items' => 'required|array',
            'items.*.book_id' => 'nullable|exists:books,id',
            'items.*.flash_price' => 'nullable|numeric|min:0',
        ]);

        $selected = collect($request->input('items', []))
            ->map(fn($x) => $x['book_id'] ?? null)
            ->filter()
            ->values();

        if ($selected->isEmpty()) {
            return back()->withErrors(['items' => 'Vui lòng chọn ít nhất 1 sách cho flash sale.'])->withInput();
        }

        if ($selected->unique()->count() !== $selected->count()) {
            return back()->withErrors(['items' => 'Mỗi sách chỉ được chọn 1 lần trong cùng một flash sale.'])->withInput();
        }

        $endDate = \Carbon\Carbon::parse($validated['start_date'])->addHours($validated['duration_hours']);

        DB::transaction(function () use ($validated, $request, $endDate, $flashSale) {
            $flashSale->update([
                'name' => $validated['sale_name'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
            ]);

            FlashSaleItem::where('flash_sale_id', $flashSale->id)->delete();

            foreach (range(1, self::SLOT_COUNT) as $slot) {
                $bookId = $request->input("items.$slot.book_id");
                if (empty($bookId)) {
                    continue;
                }

                $book = Book::query()->select(['id', 'sale_price'])->find($bookId);
                if (!$book) {
                    continue;
                }

                $flashPrice = $request->input("items.$slot.flash_price");
                $flashPrice = ($flashPrice === null || $flashPrice === '') ? (int) $book->sale_price : (int) $flashPrice;

                FlashSaleItem::create([
                    'flash_sale_id' => $flashSale->id,
                    'book_id' => $bookId,
                    'flash_price' => $flashPrice,
                    'display_order' => $slot,
                ]);
            }
        });

        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật flash sale: ' . $flashSale->name,
            level: 'info',
            objectType: 'FlashSale',
            objectId: $flashSale->id
        );

        Cache::forget('home_flash_sale');

        return redirect()->route('admin.flash-sales.index')->with('success', 'Cập nhật flash sale thành công!');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSaleId = $flashSale->id;
        $name = $flashSale->name;
        $flashSale->delete();
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa flash sale: ' . $name,
            level: 'warning',
            objectType: 'FlashSale',
            objectId: $flashSaleId
        );

        Cache::forget('home_flash_sale');

        return redirect()->route('admin.flash-sales.index')->with('success', 'Xóa flash sale thành công!');
    }
}


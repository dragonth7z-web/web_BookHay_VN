<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $discountCodes = Coupon::orderByDesc('created_at')->paginate(20);
        return view('admin.coupons.index', compact('discountCodes'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $coupon = Coupon::create($request->only(['code', 'name', 'type', 'value', 'max_discount', 'min_order_amount', 'usage_limit', 'starts_at', 'expires_at', 'status']));
        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm mã giảm giá: ' . $coupon->code,
            level: 'info',
            objectType: 'Coupon',
            objectId: $coupon->id
        );
        return redirect()->route('admin.coupons.index')->with('success', 'Thêm thành công.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($request->only(['code', 'name', 'type', 'value', 'max_discount', 'min_order_amount', 'usage_limit', 'starts_at', 'expires_at', 'status']));
        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật mã giảm giá: ' . $coupon->code,
            level: 'info',
            objectType: 'Coupon',
            objectId: $coupon->id
        );
        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Coupon $coupon)
    {
        $id = $coupon->id;
        $code = $coupon->code;
        $coupon->delete();
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa mã giảm giá: ' . $code,
            level: 'warning',
            objectType: 'Coupon',
            objectId: $id
        );
        return redirect()->route('admin.coupons.index')->with('success', 'Xóa thành công.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;

class ShippingAddressController extends Controller
{
    public function index()
    {
        $addresses = ShippingAddress::with('user')->paginate(20);
        return view('admin.shipping-addresses.index', compact('addresses'));
    }

    public function show(ShippingAddress $shippingAddress)
    {
        return view('admin.shipping-addresses.show', compact('shippingAddress'));
    }

    public function destroy(ShippingAddress $shippingAddress)
    {
        $shippingAddress->delete();
        return redirect()->route('admin.shipping-addresses.index')->with('success', 'Xóa thành công.');
    }
}

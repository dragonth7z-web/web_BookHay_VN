<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentTransactionController extends Controller
{
    public function index()
    {
        $transactions = PaymentTransaction::with('order')->orderByDesc('id')->paginate(20);
        return view('admin.payment-transactions.index', compact('transactions'));
    }

    public function show(PaymentTransaction $paymentTransaction)
    {
        return view('admin.payment-transactions.show', compact('paymentTransaction'));
    }

    public function update(Request $request, PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction->update($request->only(['status', 'notes']));
        return redirect()->route('admin.payment-transactions.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction->delete();
        return redirect()->route('admin.payment-transactions.index')->with('success', 'Xóa thành công.');
    }
}

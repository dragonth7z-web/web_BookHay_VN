<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::orderBy('name')->paginate(20);
        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $publisher = Publisher::create($request->only(['name', 'slug', 'logo', 'address', 'phone', 'email', 'is_partner']));
        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm NXB: ' . $publisher->name,
            level: 'info',
            objectType: 'Publisher',
            objectId: $publisher->id
        );
        return redirect()->route('admin.publishers.index')->with('success', 'Thêm thành công.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $publisher->update($request->only(['name', 'slug', 'logo', 'address', 'phone', 'email', 'is_partner']));
        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật NXB: ' . $publisher->name,
            level: 'info',
            objectType: 'Publisher',
            objectId: $publisher->id
        );
        return redirect()->route('admin.publishers.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Publisher $publisher)
    {
        $id = $publisher->id;
        $name = $publisher->name;
        $publisher->delete();
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa NXB: ' . $name,
            level: 'warning',
            objectType: 'Publisher',
            objectId: $id
        );
        return redirect()->route('admin.publishers.index')->with('success', 'Xóa thành công.');
    }
}

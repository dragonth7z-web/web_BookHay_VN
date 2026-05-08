<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\SystemLog;
use App\Repositories\PublisherRepository;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function __construct(
        private PublisherRepository $repo
    ) {}

    public function index()
    {
        $publishers = $this->repo->paginated(20);
        $stats      = $this->repo->getStats();

        return view('admin.publishers.index', compact('publishers', 'stats'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $publisher = Publisher::create($request->only([
            'name', 'slug', 'logo', 'address', 'phone', 'email', 'is_partner',
        ]));

        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm NXB: ' . $publisher->name,
            level: 'info',
            objectType: 'Publisher',
            objectId: $publisher->id
        );

        return redirect()->route('admin.publishers.index')->with('success', 'Thêm nhà xuất bản thành công.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $publisher->update($request->only([
            'name', 'slug', 'logo', 'address', 'phone', 'email', 'is_partner',
        ]));

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
        $id   = $publisher->id;
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

        return redirect()->route('admin.publishers.index')->with('success', 'Đã xóa nhà xuất bản.');
    }
}

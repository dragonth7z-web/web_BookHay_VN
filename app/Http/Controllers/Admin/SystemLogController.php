<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemLog::query()->orderByDesc('created_at');

        // Bộ lọc theo loại log
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Bộ lọc theo mức độ
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Bộ lọc theo hành động
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%{$s}%")
                    ->orWhere('user_name', 'like', "%{$s}%")
                    ->orWhere('ip_address', 'like', "%{$s}%")
                    ->orWhere('url', 'like', "%{$s}%");
            });
        }

        // Bộ lọc theo khoảng thời gian
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(20)->appends($request->all());

        // Thống kê nhanh
        $totalLogs = SystemLog::count();
        $totalToday = SystemLog::whereDate('created_at', today())->count();
        $totalErrors = SystemLog::where('level', 'error')->orWhere('level', 'critical')->count();
        $totalSecurity = SystemLog::where('type', 'security')->count();

        return view('admin.system-logs.index', compact(
            'logs',
            'totalLogs',
            'totalToday',
            'totalErrors',
            'totalSecurity'
        ));
    }

    public function show(SystemLog $systemLog)
    {
        return view('admin.system-logs.show', compact('systemLog'));
    }

    public function destroy(SystemLog $systemLog)
    {
        $systemLog->delete();
        return redirect()->route('admin.system-logs.index')
            ->with('success', 'Đã xóa bản ghi log.');
    }

    public function clearOld(Request $request)
    {
        $days = $request->input('days', 90);
        $deleted = SystemLog::where('created_at', '<', now()->subDays($days))->delete();

        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: "Đã xóa {$deleted} bản ghi log cũ hơn {$days} ngày",
            level: 'info'
        );

        return redirect()->route('admin.system-logs.index')
            ->with('success', "Đã xóa {$deleted} bản ghi log cũ hơn {$days} ngày.");
    }
}

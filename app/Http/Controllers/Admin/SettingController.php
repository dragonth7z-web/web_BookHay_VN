<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $setting = Setting::updateOrCreate(['key' => $request->key], $request->only(['value', 'description']));
        
        // Clear caches
        Cache::forget('home_configs');
        Cache::forget("setting_limit_{$setting->key}");
        
        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Cập nhật cấu hình: ' . $setting->key,
            level: 'info',
            objectType: 'Setting',
            objectId: $setting->id
        );
        return redirect()->route('admin.settings.index')->with('success', 'Lưu cấu hình thành công.');
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $setting->update($request->only(['value', 'description']));
        
        // Clear caches
        Cache::forget('home_configs');
        Cache::forget("setting_limit_{$setting->key}");
        
        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật giá trị cấu hình: ' . $setting->key,
            level: 'info',
            objectType: 'Setting',
            objectId: $setting->id
        );
        return redirect()->route('admin.settings.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Setting $setting)
    {
        $id = $setting->id;
        $key = $setting->key;
        $setting->delete();
        
        // Clear caches
        Cache::forget('home_configs');
        Cache::forget("setting_limit_{$key}");
        
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa cấu hình: ' . $key,
            level: 'warning',
            objectType: 'Setting',
            objectId: $id
        );
        return redirect()->route('admin.settings.index')->with('success', 'Xóa thành công.');
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

/**
 * Feature: admin-ui-upgrade, Property 2: Topbar phản ánh đúng page title và tên admin
 * Validates: Requirements 3.2, 3.3
 */
class AdminTopbarRenderTest extends TestCase
{
    /**
     * @dataProvider adminNameAndTitleProvider
     */
    public function test_topbar_renders_admin_name(string $adminName, string $pageTitle): void
    {
        $user = new User(['name' => $adminName]);
        $this->actingAs($user);

        $html = view('admin.partials.topbar')->render();

        $this->assertStringContainsString(htmlspecialchars($adminName), $html);
    }

    public static function adminNameAndTitleProvider(): array
    {
        return [
            ['Admin THLD', 'Dashboard'],
            ['Nguyễn Văn A', 'Quản lý Sách'],
            ['Trần Thị B', 'Đơn hàng'],
            ['Admin Test 123', 'Cài đặt'],
        ];
    }
}

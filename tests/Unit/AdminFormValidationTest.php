<?php

// Feature: admin-ui-upgrade, Property 3: Form validation hiển thị tất cả errors

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\MessageBag;

class AdminFormValidationTest extends TestCase
{
    /**
     * @dataProvider validationErrorsProvider
     *
     * Validates: Requirements 8.4
     *
     * For any set of validation error messages (non-empty), when rendering the CRUD form
     * with those errors, the output HTML SHALL contain all error messages in the error box.
     */
    public function test_form_displays_all_validation_errors(array $errors): void
    {
        $messageBag = new MessageBag($errors);

        $html = view('admin.categories.create')
            ->withErrors($messageBag)
            ->with('parents', collect([]))
            ->render();

        foreach ($messageBag->all() as $error) {
            $this->assertStringContainsString(htmlspecialchars($error), $html);
        }
    }

    public static function validationErrorsProvider(): array
    {
        return [
            [['name' => ['Tên danh mục là bắt buộc.']]],
            [['name' => ['Tên quá dài.'], 'slug' => ['Slug không hợp lệ.']]],
            [['name' => ['Lỗi 1', 'Lỗi 2']]],
            [['name' => ['Required'], 'slug' => ['Invalid slug'], 'sort_order' => ['Must be a number']]],
        ];
    }
}

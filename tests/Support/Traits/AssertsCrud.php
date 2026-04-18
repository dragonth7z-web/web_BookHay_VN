<?php

namespace Tests\Support\Traits;

use Illuminate\Support\Facades\DB;

trait AssertsCrud
{
    /**
     * Assert record count tăng đúng delta sau khi action được gọi.
     */
    protected function assertCountIncreasedBy(string $table, int $delta, callable $action): void
    {
        $before = DB::table($table)->count();
        $action();
        $this->assertEquals($before + $delta, DB::table($table)->count());
    }

    /**
     * Assert record count không đổi sau khi action được gọi.
     */
    protected function assertCountUnchanged(string $table, callable $action): void
    {
        $this->assertCountIncreasedBy($table, 0, $action);
    }

    /**
     * Assert soft-delete đúng: record không xuất hiện trong default query,
     * nhưng assertSoftDeleted pass (deleted_at được set).
     */
    protected function assertSoftDeletedCorrectly(string $table, int $id): void
    {
        $this->assertNull(
            DB::table($table)->where('id', $id)->whereNull('deleted_at')->first(),
            "Record id={$id} vẫn xuất hiện trong default query (deleted_at chưa được set)"
        );
        $this->assertSoftDeleted($table, ['id' => $id]);
    }

    /**
     * Assert field values được persist đúng trong DB.
     */
    protected function assertFieldPersisted(string $table, int $id, array $fields): void
    {
        $record = DB::table($table)->find($id);
        $this->assertNotNull($record, "Record id={$id} không tìm thấy trong bảng {$table}");
        foreach ($fields as $field => $value) {
            $this->assertEquals($value, $record->$field, "Field '{$field}' không khớp trong bảng {$table}");
        }
    }
}

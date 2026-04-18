<?php

namespace Tests\Support\Traits;

use App\Models\SystemLog;

trait AssertsSystemLog
{
    /**
     * Assert SystemLog tồn tại với đầy đủ fields và trả về log đó.
     */
    protected function assertSystemLog(
        string $action,
        string $objectType,
        int $objectId,
        string $level = 'info',
        string $type = 'data'
    ): SystemLog {
        $log = SystemLog::where('action', $action)
            ->where('object_type', $objectType)
            ->where('object_id', $objectId)
            ->where('level', $level)
            ->where('type', $type)
            ->latest()
            ->first();

        $this->assertNotNull(
            $log,
            "SystemLog không tìm thấy: action={$action}, object_type={$objectType}, object_id={$objectId}, level={$level}, type={$type}"
        );
        $this->assertNotNull($log->object_type, 'object_type không được NULL');

        return $log;
    }

    /**
     * Assert description của log chứa needle.
     */
    protected function assertLogDescriptionContains(SystemLog $log, string $needle): void
    {
        $this->assertStringContainsString(
            $needle,
            $log->description,
            "Log description không chứa '{$needle}'"
        );
    }

    /**
     * Assert user_id của log khớp với userId.
     */
    protected function assertLogUserIdMatches(SystemLog $log, int $userId): void
    {
        $this->assertEquals(
            $userId,
            $log->user_id,
            "Log user_id ({$log->user_id}) không khớp với expected ({$userId})"
        );
    }

    /**
     * Assert created_at của log trong vòng 5 giây so với now().
     */
    protected function assertLogTimely(SystemLog $log): void
    {
        $this->assertLessThanOrEqual(
            5,
            now()->diffInSeconds($log->created_at),
            'Log created_at phải trong vòng 5 giây so với now()'
        );
    }
}

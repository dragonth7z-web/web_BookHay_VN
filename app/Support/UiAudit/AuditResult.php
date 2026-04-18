<?php

namespace App\Support\UiAudit;

class AuditResult
{
    public string $module;
    public string $status; // 'pass' | 'fail' | 'fixed'
    public array $issues = [];
    public array $fixes = [];
    public int $issueCount = 0;
    public int $fixCount = 0;

    public function __construct(string $module, string $status = 'pass')
    {
        $this->module = $module;
        $this->status = $status;
    }
}

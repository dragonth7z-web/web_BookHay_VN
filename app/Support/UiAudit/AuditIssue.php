<?php

namespace App\Support\UiAudit;

class AuditIssue
{
    public string $type;
    public string $file;
    public int $line;
    public string $context;
    public ?string $suggestion;
    public bool $autoFixable;

    public function __construct(
        string $type,
        string $file,
        int $line,
        string $context,
        ?string $suggestion = null,
        bool $autoFixable = false
    ) {
        $this->type = $type;
        $this->file = $file;
        $this->line = $line;
        $this->context = $context;
        $this->suggestion = $suggestion;
        $this->autoFixable = $autoFixable;
    }
}

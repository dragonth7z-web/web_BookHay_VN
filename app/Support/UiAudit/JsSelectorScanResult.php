<?php

namespace App\Support\UiAudit;

class JsSelectorScanResult
{
    public string $cssClass;
    public array $files = [];
    public int $occurrences = 0;

    public function __construct(string $cssClass, array $files = [], int $occurrences = 0)
    {
        $this->cssClass = $cssClass;
        $this->files = $files;
        $this->occurrences = $occurrences;
    }
}

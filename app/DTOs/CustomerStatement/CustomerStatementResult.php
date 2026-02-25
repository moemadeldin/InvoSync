<?php

declare(strict_types=1);

namespace App\DTOs\CustomerStatement;

use Illuminate\Support\Collection;

final readonly class CustomerStatementResult
{
    public function __construct(
        public Collection $transactions,
        public CustomerStatementSummary $summary,
    ) {}
}

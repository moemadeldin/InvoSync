<?php

declare(strict_types=1);

namespace App\DTOs\CustomerStatement;

final readonly class CustomerStatementSummary
{
    public float $balance;

    public string $formattedTotalInvoiced;

    public string $formattedTotalPaid;

    public string $formattedTotalReturns;

    public string $formattedBalance;

    public function __construct(
        public float $totalInvoiced,
        public float $totalPaid,
        public float $totalReturns,
    ) {
        $this->balance = $totalInvoiced - $totalPaid - $totalReturns;
        $this->formattedTotalInvoiced = number_format($totalInvoiced, 2);
        $this->formattedTotalPaid = number_format($totalPaid, 2);
        $this->formattedTotalReturns = number_format($totalReturns, 2);
        $this->formattedBalance = number_format($this->balance, 2);
    }
}

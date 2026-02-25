<?php

declare(strict_types=1);

namespace App\DTOs\CustomerStatement;

use Carbon\Carbon;

final readonly class CustomerStatementData
{
    public string $formattedDebit;

    public string $formattedCredit;

    public string $formattedBalance;

    public function __construct(
        public string $type,
        public Carbon $date,
        public string $reference,
        public float $debit,
        public float $credit,
        public float $balance = 0.0,
    ) {
        $this->formattedDebit = $debit > 0 ? number_format($debit, 2) : '-';
        $this->formattedCredit = $credit > 0 ? number_format($credit, 2) : '-';
        $this->formattedBalance = number_format($balance, 2);
    }

    public function withBalance(float $balance): self
    {
        return new self(
            type: $this->type,
            date: $this->date,
            reference: $this->reference,
            debit: $this->debit,
            credit: $this->credit,
            balance: $balance,
        );
    }
}

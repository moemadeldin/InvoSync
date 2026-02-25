<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case BankTransfer = 'bank transfer';
    case Cheque = 'cheque';
    case CreditCard = 'credit card';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::BankTransfer => 'Bank Transfer',
            self::Cheque => 'Cheque',
            self::CreditCard => 'Credit Card',
            self::Other => 'Other',
        };
    }
}

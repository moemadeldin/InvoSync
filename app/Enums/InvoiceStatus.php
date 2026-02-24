<?php

declare(strict_types=1);

namespace App\Enums;

enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Paid = 'paid';
    case Returned = 'returned';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Sent => 'Sent',
            self::Paid => 'Paid',
            self::Returned => 'Returned',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-600 text-slate-200',
            self::Sent => 'bg-blue-500 text-white',
            self::Paid => 'bg-green-500 text-white',
            self::Returned => 'bg-yellow-500 text-black',
            self::Cancelled => 'bg-red-500 text-white',
        };
    }
}

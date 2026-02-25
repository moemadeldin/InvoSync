<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $invoice_id
 * @property string $description
 * @property int $qty
 * @property numeric $unit_price
 * @property numeric $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $formatted_total
 * @property-read string $formatted_unit_price
 * @property-read Invoice $invoice
 * @property-read Collection<int, SalesReturnItem> $salesReturnItems
 * @property-read int|null $sales_return_items_count
 */
final class InvoiceItem extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function salesReturnItems(): HasMany
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'invoice_id' => 'string',
            'description' => 'string',
            'qty' => 'integer',
            'unit_price' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    protected function formattedUnitPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format((float) $this->unit_price, 2),
        );
    }

    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format((float) $this->total, 2),
        );
    }
}

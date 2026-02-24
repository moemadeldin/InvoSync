<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $user_id
 * @property string $customer_id
 * @property string $invoice_number
 * @property numeric $subtotal
 * @property numeric $tax
 * @property numeric $tax_rate
 * @property numeric $total
 * @property InvoiceStatus $status
 * @property Carbon|null $invoice_date
 * @property Carbon|null $due_date
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Customer $customer
 * @property-read Collection<int, InvoiceItem> $items
 * @property-read User $user
 */
#[ScopedBy([TenantScope::class])]
final class Invoice extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'customer_id' => 'string',
        'invoice_number' => 'string',
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'status' => InvoiceStatus::class,
        'due_date' => 'date',
        'notes' => 'string',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    protected function formattedSubtotal(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format((float) $this->subtotal, 2),
        );
    }

    protected function formattedTax(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format((float) $this->tax, 2),
        );
    }

    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format((float) $this->total, 2),
        );
    }

    #[Scope]
    protected function search(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search): void {
            $q->where('invoice_number', 'like', sprintf('%%%s%%', $search))
                ->orWhereHas('customer', function (Builder $customerQuery) use ($search): void {
                    $customerQuery->where('name', 'like', sprintf('%%%s%%', $search));
                });
        });
    }

    #[Scope]
    protected function filterByStatus(Builder $query, ?InvoiceStatus $status): Builder
    {
        if (! $status instanceof InvoiceStatus) {
            return $query;
        }

        return $query->where('status', $status->value);
    }

    #[Scope]
    protected function filterByDateFrom(Builder $query, ?string $dateFrom): Builder
    {
        if (! $dateFrom) {
            return $query;
        }

        return $query->whereDate('due_date', '>=', $dateFrom);
    }

    #[Scope]
    protected function filterByDateTo(Builder $query, ?string $dateTo): Builder
    {
        if (! $dateTo) {
            return $query;
        }

        return $query->whereDate('due_date', '<=', $dateTo);
    }

    #[Scope]
    protected function withCustomerAndUsers(Builder $query): Builder
    {
        return $query->with(['customer', 'user'])->latest();
    }
}

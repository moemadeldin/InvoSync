<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $user_id
 * @property string $invoice_id
 * @property string $customer_id
 * @property numeric $amount
 * @property Carbon $payment_date
 * @property PaymentMethod $payment_method
 * @property string|null $reference_number
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Invoice $invoice
 * @property-read Customer $customer
 */
#[ScopedBy([TenantScope::class])]
final class Payment extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'invoice_id' => 'string',
        'customer_id' => 'string',
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'payment_method' => PaymentMethod::class,
        'reference_number' => 'string',
        'notes' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class)->withTrashed();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format((float) $this->amount, 2),
        );
    }

    #[Scope]
    protected function filterByInvoice(Builder $query, ?string $invoiceId): Builder
    {
        if (! $invoiceId) {
            return $query;
        }

        return $query->where('invoice_id', $invoiceId);
    }

    #[Scope]
    protected function filterByCustomer(Builder $query, ?string $customerId): Builder
    {
        if (! $customerId) {
            return $query;
        }

        return $query->where('customer_id', $customerId);
    }

    #[Scope]
    protected function filterByDateFrom(Builder $query, ?string $dateFrom): Builder
    {
        if (! $dateFrom) {
            return $query;
        }

        return $query->whereDate('payment_date', '>=', $dateFrom);
    }

    #[Scope]
    protected function filterByDateTo(Builder $query, ?string $dateTo): Builder
    {
        if (! $dateTo) {
            return $query;
        }

        return $query->whereDate('payment_date', '<=', $dateTo);
    }

    #[Scope]
    protected function withInvoiceAndCustomer(Builder $query): Builder
    {
        return $query->with(['invoice', 'customer']);
    }
}

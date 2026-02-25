<?php

declare(strict_types=1);

use App\Enums\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignUuid('invoice_id')
                ->constrained('invoices')
                ->restrictOnDelete();
            $table->foreignUuid('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('payment_date');
            $table->string('payment_method')->default(PaymentMethod::Cash->value);
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('invoice_id');
            $table->index('customer_id');
            $table->index('payment_date');
            $table->softDeletes();
        });
    }
};

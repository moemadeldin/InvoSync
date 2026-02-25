@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-slate-50">Create Invoice</h1>
            <a href="{{ route('invoices.index') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                Back
            </a>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 shadow-lg">
            <form method="POST" action="{{ route('invoices.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="customer_id" class="block text-slate-300 text-sm font-medium mb-2">Customer</label>
                        <select name="customer_id" id="customer_id" required
                            class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-slate-300 text-sm font-medium mb-2">Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                            @foreach(\App\Enums\InvoiceStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('status', 'draft') === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="invoice_date" class="block text-slate-300 text-sm font-medium mb-2">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date"
                            value="{{ old('invoice_date', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        @error('invoice_date')
                            <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-slate-300 text-sm font-medium mb-2">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                            class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        @error('due_date')
                            <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="tax_rate" class="block text-slate-300 text-sm font-medium mb-2">Tax</label>
                    <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        @error('tax')
                            <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                </div>

                <div>
                    <label for="notes" class="block text-slate-300 text-sm font-medium mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3 text-slate-50">Items</h3>
                    <div id="items-container" class="space-y-4">
                        <div class="item-row grid grid-cols-12 gap-2">
                            <div class="col-span-5">
                                <input type="text" name="items[0][description]" placeholder="Description" required
                                    class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm">
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="items[0][qty]" placeholder="Qty" value="1" min="1" required
                                    class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm">
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="items[0][unit_price]" placeholder="Unit Price" step="0.01" min="0" required
                                    class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="text-slate-400">$0.00</span>
                            </div>
                            <div class="col-span-1">
                                <button type="button" class="cursor-pointer remove-item text-red-400 hover:text-red-300">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-item"
                        class="cursor-pointer mt-3 px-3 py-1 bg-slate-700 hover:bg-slate-600 rounded-lg text-sm text-slate-200">+ Add Item</button>
                </div>

                <button type="submit"
                    class="cursor-pointer w-full py-3 rounded-lg text-white font-semibold text-sm gradient-primary hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    Create Invoice
                </button>
            </form>
        </div>
    </div>



    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemCount = 1;
        document.getElementById('add-item')?.addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'item-row grid grid-cols-12 gap-2';
            row.innerHTML = `
                <div class="col-span-5">
                    <input type="text" name="items[${itemCount}][description]" placeholder="Description" required 
                            class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm">
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${itemCount}][qty]" placeholder="Qty" value="1" min="1" required 
                            class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm">
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${itemCount}][unit_price]" placeholder="Unit Price" step="0.01" min="0" required 
                            class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm">
                </div>
                <div class="col-span-2 flex items-center">
                    <span class="text-slate-400">$0.00</span>
                </div>
                <div class="col-span-1">
                    <button type="button" class="cursor-pointer remove-item text-red-400 hover:text-red-300">X</button>
                </div>
            `;
            container.appendChild(row);
            itemCount++;

            row.querySelector('.remove-item').addEventListener('click', function () {
                row.remove();
            });
        });

        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function () {
                this.closest('.item-row').remove();
            });
        });
    });
</script>
@endsection

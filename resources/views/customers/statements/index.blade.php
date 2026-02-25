@extends('layouts.app')

@section('title', 'Customer Statements')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-slate-50">Customer Statements</h1>
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                Back
            </a>
        </div>

        <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-lg">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Total Invoiced</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Total Paid</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Returns</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Balance Due</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $customer->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-200">
                                ${{ $customer->formatted_total_invoiced }}
                            </td>
                            <td class="px-6 py-4 text-sm text-green-400">
                                ${{ $customer->formatted_total_paid }}
                            </td>
                            <td class="px-6 py-4 text-sm text-red-400">
                                ${{ $customer->formatted_total_returns }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold {{ $customer->balance > 0 ? 'text-red-400' : 'text-green-400' }}">
                                ${{ $customer->formatted_balance }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('customers.statements.show', $customer) }}"
                                    class="px-4 py-2 rounded-lg text-white font-semibold text-xs gradient-primary hover:shadow-lg transition-all">
                                    View Statement
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $customers->links() }}
        </div>
    </div>
@endsection

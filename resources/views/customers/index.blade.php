@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-slate-50">Customers</h1>
            <a href="{{ route('customers.create') }}"
                class="px-6 py-3 rounded-lg text-white font-semibold text-sm gradient-primary hover:shadow-lg hover:-translate-y-0.5 transition-all">
                Create Customer
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Form -->
        <div class="bg-slate-800 rounded-2xl p-6 mb-6 shadow-lg">
            <form method="GET" action="{{ route('customers.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search by name or email..."
                        class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all placeholder-slate-600">
                </div>
                <button type="submit"
                    class="cursor-pointer px-6 py-2 rounded-lg text-white font-semibold text-sm gradient-primary hover:shadow-lg transition-all">
                    Search
                </button>
                <a href="{{ route('customers.index') }}"
                    class="cursor-pointer px-6 py-2 rounded-lg text-white font-semibold text-sm bg-slate-700 hover:bg-slate-600 transition-all">
                    Clear
                </a>
            </form>
        </div>

        <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-lg">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Phone</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Address</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $customer->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $customer->email }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $customer->address ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('customers.show', $customer) }}"
                                        class="px-4 py-2 rounded-lg text-white font-semibold text-xs gradient-primary hover:shadow-lg transition-all">
                                        View
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}"
                                        class="px-4 py-2 rounded-lg text-white font-semibold text-xs bg-slate-700 hover:bg-slate-600 transition-all">
                                        Edit
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="cursor-pointer px-4 py-2 rounded-lg text-white font-semibold text-xs bg-gradient-to-r from-red-500 to-red-600 hover:shadow-lg transition-all"
                                            onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">No Customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-4">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
@extends('layouts.dashboard')
@section('content')

<div class="min-h-screen bg-white">
    <x-sidebar />

    <main class="ml-64">

        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <div class="border-b border-gray-200">
            <div class="px-8 lg:px-12 py-6">
                <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-2">
                    Management
                </p>
                <h1 class="text-3xl lg:text-4xl font-black text-black tracking-tight uppercase leading-none">
                    Orders
                </h1>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SEARCH & FILTER -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-6 border-b border-gray-200">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                
                <!-- Search Input -->
                <div class="flex-1 max-w-md">
                    <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Search
                    </label>
                    <div class="search-input-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Order number, customer name, or email..."
                            class="border border-gray-300 py-3 text-[12px] text-black focus:outline-none focus:border-black transition-colors">
                    </div>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Status
                    </label>
                    <select name="status" 
                            class="border border-gray-300 px-4 py-3 text-[12px] text-black focus:outline-none focus:border-black transition-colors">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Button Apply -->
                <button type="submit" 
                        class="bg-black text-white px-6 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                    Search
                </button>

                <!-- Button Reset -->
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" 
                       class="border border-gray-300 text-black px-6 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:border-black transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- ============================================ -->
        <!-- STATS -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8 border-b border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-200">
                
                <div class="px-6 first:pl-0">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Total</p>
                    <p class="text-3xl font-black text-black">{{ $stats['total'] }}</p>
                </div>

                <div class="px-6">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Pending</p>
                    <p class="text-3xl font-black text-black">{{ $stats['pending'] }}</p>
                </div>

                <div class="px-6">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Shipped</p>
                    <p class="text-3xl font-black text-black">{{ $stats['shipped'] }}</p>
                </div>

                <div class="px-6">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Completed</p>
                    <p class="text-3xl font-black text-black">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- TABLE -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8">
            
            <!-- Result Info -->
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
                <p class="text-[11px] tracking-wider uppercase text-gray-500">
                    Showing 
                    <span class="text-black font-bold">{{ $orders->firstItem() ?? 0 }}</span> 
                    — 
                    <span class="text-black font-bold">{{ $orders->lastItem() ?? 0 }}</span> 
                    of 
                    <span class="text-black font-bold">{{ $orders->total() }}</span> 
                    orders
                    @if(request('search'))
                        <span class="text-gray-400"> · </span>
                        for "<span class="text-black">{{ request('search') }}</span>"
                    @endif
                </p>
                <p class="text-[11px] tracking-wider uppercase text-gray-500">
                    Page <span class="text-black font-bold">{{ $orders->currentPage() }}</span> 
                    of 
                    <span class="text-black font-bold">{{ $orders->lastPage() }}</span>
                </p>
            </div>

            <!-- Table -->
            <div class="border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        
                        <thead class="bg-white border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Order ID</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Customer</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Total</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Status</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Date</th>
                                <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                
                                <td class="px-6 py-4">
                                    <span class="text-[12px] font-medium text-black tracking-wider">
                                        {{ $order->order_number }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-[12px] text-black tracking-wider uppercase">
                                        {{ $order->user->name ?? '—' }}
                                    </span>
                                    @if($order->user)
                                        <br>
                                        <span class="text-[10px] text-gray-500">{{ $order->user->email }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-[12px] font-medium text-black">
                                        IDR {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-[10px] tracking-[0.15em] uppercase font-medium px-3 py-1 border
                                        @if($order->status == 'completed') border-black bg-black text-white
                                        @elseif($order->status == 'pending') border-gray-400 text-gray-700
                                        @elseif($order->status == 'shipped') border-black text-black
                                        @elseif($order->status == 'cancelled') border-red-500 text-red-500
                                        @else border-gray-300 text-gray-500
                                        @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-[11px] text-gray-500 tracking-wider">
                                        {{ $order->created_at->format('d M Y') }}
                                    </span>
                                    <br>
                                    <span class="text-[10px] text-gray-400 tracking-wider">
                                        {{ $order->created_at->format('H:i') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.orders.detail', $order->id) }}" 
                                           class="text-[11px] tracking-[0.15em] uppercase text-black border-b border-black pb-0.5 hover:opacity-60 transition-opacity">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <i class="bi bi-receipt text-4xl text-gray-300 block mb-4"></i>
                                    <p class="text-[12px] tracking-[0.2em] uppercase text-black mb-2">
                                        @if(request('search') || request('status'))
                                            No orders found
                                        @else
                                            No orders yet
                                        @endif
                                    </p>
                                    @if(request('search') || request('status'))
                                        <a href="{{ route('admin.orders.index') }}" 
                                           class="text-[11px] tracking-wider uppercase text-gray-500 underline hover:text-black">
                                            Clear search
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- PAGINATION -->
            <!-- ============================================ -->
            @if($orders->hasPages())
                <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-t border-gray-200 pt-6">
                    
                    <!-- Prev Button -->
                    <div>
                        @if ($orders->onFirstPage())
                            <span class="inline-block px-5 py-2.5 text-[11px] tracking-[0.15em] uppercase text-gray-300 border border-gray-200 cursor-not-allowed">
                                ← Previous
                            </span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}" 
                               class="inline-block px-5 py-2.5 text-[11px] tracking-[0.15em] uppercase text-black border border-gray-300 hover:border-black transition-all">
                                ← Previous
                            </a>
                        @endif
                    </div>

                    <!-- Page Numbers -->
                    <div class="flex items-center gap-1">
                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            @if ($page == $orders->currentPage())
                                <span class="w-10 h-10 flex items-center justify-center text-[11px] font-bold tracking-wider bg-black text-white border border-black">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                   class="w-10 h-10 flex items-center justify-center text-[11px] tracking-wider text-black border border-gray-300 hover:border-black transition-all">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    <!-- Next Button -->
                    <div>
                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}" 
                               class="inline-block px-5 py-2.5 text-[11px] tracking-[0.15em] uppercase text-black border border-gray-300 hover:border-black transition-all">
                                Next →
                            </a>
                        @else
                            <span class="inline-block px-5 py-2.5 text-[11px] tracking-[0.15em] uppercase text-gray-300 border border-gray-200 cursor-not-allowed">
                                Next →
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>

@endsection
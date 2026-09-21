@extends('layouts.dashboard')
@section('content')

<div class="min-h-screen bg-white">
    <x-sidebar />

    <main class="ml-64">

        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <div class="border-b border-gray-200">
            <div class="px-8 lg:px-12 py-6 flex items-center justify-between">
                <div>
                    <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-2">
                        Analytics
                    </p>
                    <h1 class="text-3xl lg:text-4xl font-black text-black tracking-tight uppercase leading-none">
                        Sales Report
                    </h1>
                </div>
                <a href="{{ route('admin.report.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="bg-black text-white px-6 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                    Export CSV
                </a>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- FILTER DATE -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-6 border-b border-gray-200">
            <form action="{{ route('admin.report.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                
                <div>
                    <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="border border-gray-300 px-4 py-2 text-[12px] text-black focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                           class="border border-gray-300 px-4 py-2 text-[12px] text-black focus:outline-none focus:border-black">
                </div>

                <button type="submit" 
                        class="bg-black text-white px-6 py-2 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                    Apply
                </button>

                <!-- Quick Filters -->
                <div class="flex gap-2 ml-auto">
                    <a href="{{ route('admin.report.index', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                       class="border border-gray-300 text-black px-4 py-2 text-[10px] font-medium tracking-[0.15em] uppercase hover:border-black transition-all">
                        Today
                    </a>
                    <a href="{{ route('admin.report.index', ['start_date' => now()->subDays(7)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                       class="border border-gray-300 text-black px-4 py-2 text-[10px] font-medium tracking-[0.15em] uppercase hover:border-black transition-all">
                        7 Days
                    </a>
                    <a href="{{ route('admin.report.index', ['start_date' => now()->subDays(30)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                       class="border border-gray-300 text-black px-4 py-2 text-[10px] font-medium tracking-[0.15em] uppercase hover:border-black transition-all">
                        30 Days
                    </a>
                    <a href="{{ route('admin.report.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="border border-gray-300 text-black px-4 py-2 text-[10px] font-medium tracking-[0.15em] uppercase hover:border-black transition-all">
                        This Month
                    </a>
                </div>
            </form>
        </div>

        <!-- ============================================ -->
        <!-- STATS CARDS -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8 border-b border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-5 divide-x divide-gray-200">
                <div class="px-6 first:pl-0">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Revenue</p>
                    <p class="text-2xl font-black text-black">IDR {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="px-6">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Orders</p>
                    <p class="text-2xl font-black text-black">{{ $totalOrders }}</p>
                </div>
                <div class="px-6">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Products Sold</p>
                    <p class="text-2xl font-black text-black">{{ $totalProductsSold }}</p>
                </div>
                <div class="px-6">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Customers</p>
                    <p class="text-2xl font-black text-black">{{ $totalCustomers }}</p>
                </div>
                <div class="px-6">
                    <p class="text-[10px] tracking-[0.25em] uppercase text-gray-500 mb-2">Avg. Order</p>
                    <p class="text-2xl font-black text-black">IDR {{ number_format($avgOrder, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- CHART PENJUALAN -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8 border-b border-gray-200">
            <div class="pb-4 mb-6 border-b border-gray-200">
                <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">Sales Chart</h3>
            </div>
            <canvas id="salesChart" height="80"></canvas>
        </div>

        <!-- ============================================ -->
        <!-- GRID: PRODUK TERLARIS + CUSTOMER TERLOYAL -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8 grid grid-cols-1 lg:grid-cols-2 gap-12 border-b border-gray-200">
            
            <!-- Top Products -->
            <div>
                <div class="pb-4 mb-6 border-b border-gray-200">
                    <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">Top Products</h3>
                </div>
                <div class="space-y-3">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-bold tracking-wider text-gray-400 w-6">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <p class="text-[12px] font-medium text-black tracking-wider uppercase">
                                    {{ $product->product_name }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[12px] font-bold text-black">{{ $product->total_sold }} sold</p>
                                <p class="text-[10px] text-gray-500">IDR {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[11px] text-gray-500 tracking-wider uppercase text-center py-8">No data</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Customers -->
            <div>
                <div class="pb-4 mb-6 border-b border-gray-200">
                    <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">Top Customers</h3>
                </div>
                <div class="space-y-3">
                    @forelse($topCustomers as $index => $customer)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-bold tracking-wider text-gray-400 w-6">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div>
                                    <p class="text-[12px] font-medium text-black tracking-wider uppercase">
                                        {{ $customer->user->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-[10px] text-gray-500">{{ $customer->total_orders }} orders</p>
                                </div>
                            </div>
                            <p class="text-[12px] font-bold text-black">
                                IDR {{ number_format($customer->total_spent, 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-[11px] text-gray-500 tracking-wider uppercase text-center py-8">No data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- GRID: PAYMENT METHODS + COURIERS -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8 grid grid-cols-1 lg:grid-cols-2 gap-12 border-b border-gray-200">
            
            <!-- Payment Methods -->
            <div>
                <div class="pb-4 mb-6 border-b border-gray-200">
                    <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">Payment Methods</h3>
                </div>
                <div class="space-y-3">
                    @forelse($paymentMethods as $payment)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <p class="text-[12px] font-medium text-black tracking-wider uppercase">
                                {{ $payment->payment_method ?? 'Unknown' }}
                            </p>
                            <div class="text-right">
                                <p class="text-[12px] font-bold text-black">{{ $payment->total_orders }} orders</p>
                                <p class="text-[10px] text-gray-500">IDR {{ number_format($payment->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[11px] text-gray-500 tracking-wider uppercase text-center py-8">No data</p>
                    @endforelse
                </div>
            </div>

            <!-- Couriers -->
            <div>
                <div class="pb-4 mb-6 border-b border-gray-200">
                    <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">Couriers</h3>
                </div>
                <div class="space-y-3">
                    @forelse($couriers as $courier)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <p class="text-[12px] font-medium text-black tracking-wider uppercase">
                                {{ $courier->courier ?? 'Unknown' }}
                            </p>
                            <div class="text-right">
                                <p class="text-[12px] font-bold text-black">{{ $courier->total_orders }} orders</p>
                                <p class="text-[10px] text-gray-500">IDR {{ number_format($courier->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[11px] text-gray-500 tracking-wider uppercase text-center py-8">No data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- UNSOLD PRODUCTS -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8 border-b border-gray-200">
            <div class="pb-4 mb-6 border-b border-gray-200">
                <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">Unsold Products</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                @forelse($unsoldProducts as $product)
                    <div>
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->path) }}" 
                                 class="w-full aspect-square object-cover border border-gray-200 mb-3">
                        @else
                            <div class="w-full aspect-square bg-gray-50 border border-gray-200 flex items-center justify-center mb-3">
                                <i class="bi bi-image text-gray-300 text-2xl"></i>
                            </div>
                        @endif
                        <p class="text-[11px] font-medium text-black tracking-wider uppercase line-clamp-1">
                            {{ $product->name }}
                        </p>
                        <p class="text-[10px] text-gray-500 tracking-wider">
                            IDR {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <p class="col-span-full text-[11px] text-gray-500 tracking-wider uppercase text-center py-8">
                        All products have been sold 🎉
                    </p>
                @endforelse
            </div>
        </div>

        <!-- ============================================ -->
        <!-- TRANSACTIONS TABLE -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8">
            <div class="pb-4 mb-6 border-b border-gray-200">
                <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                    Transactions ({{ $transactions->total() }})
                </h3>
            </div>

            <div class="border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Order ID</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Customer</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Total</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Payment</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Verified</th>
                                <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($transactions as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-[12px] font-medium text-black tracking-wider">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[12px] text-black tracking-wider uppercase">{{ $order->user->name ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[12px] font-bold text-black">IDR {{ number_format($order->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[11px] text-gray-500 tracking-wider uppercase">{{ $order->payment_method }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[11px] text-gray-500">{{ $order->verified_at ? $order->verified_at->format('d M Y, H:i') : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.detail', $order->id) }}" 
                                       class="text-[11px] tracking-[0.15em] uppercase text-black border-b border-black pb-0.5 hover:opacity-60 transition-opacity">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <i class="bi bi-receipt text-4xl text-gray-300 block mb-4"></i>
                                    <p class="text-[12px] tracking-[0.2em] uppercase text-black">No transactions yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $transactions->links() }}
            </div>
        </div>

    </main>
</div>

<!-- ============================================ -->
<!-- CHART JS -->
<!-- ============================================ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    const salesData = @json($salesChart);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(item => item.date),
            datasets: [{
                label: 'Revenue (IDR)',
                data: salesData.map(item => item.revenue),
                borderColor: '#000000',
                backgroundColor: 'rgba(0, 0, 0, 0.05)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#000000',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#000000',
                    titleFont: { size: 11 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 0,
                    callbacks: {
                        label: function(context) {
                            return 'IDR ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f0f0f0'
                    },
                    ticks: {
                        font: { size: 10 },
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 10 }
                    }
                }
            }
        }
    });
});
</script>

@endsection
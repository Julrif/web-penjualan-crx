<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    // ============================================
    // HALAMAN LAPORAN UTAMA
    // ============================================
    public function index(Request $request)
    {
        // Default: 30 hari terakhir
        $startDate = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        $filterType = $request->filter_type ?? '30days';

        // Base Query: hanya order yang sudah verified
        $baseQuery = Order::where('payment_status', 'verified')
            ->whereNotNull('verified_at')
            ->whereBetween('verified_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);

        // ============================================
        // STATS
        // ============================================
        $totalRevenue = (clone $baseQuery)->sum('total');
        $totalOrders = (clone $baseQuery)->count();
        $totalCustomers = (clone $baseQuery)->distinct('user_id')->count('user_id');
        $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Total produk terjual
        $orderIds = (clone $baseQuery)->pluck('id');
        $totalProductsSold = OrderItem::whereIn('order_id', $orderIds)->sum('quantity');

        // ============================================
        // GRAFIK PENJUALAN PER HARI
        // ============================================
        $salesChart = Order::where('payment_status', 'verified')
            ->whereNotNull('verified_at')
            ->whereBetween('verified_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->select(
                DB::raw('DATE(verified_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ============================================
        // PRODUK TERLARIS (Top 10)
        // ============================================
        $topProducts = OrderItem::whereIn('order_id', $orderIds)
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();

        // ============================================
        // PRODUK TIDAK TERJUAL
        // ============================================
        $soldProductIds = OrderItem::whereIn('order_id', $orderIds)
            ->distinct('product_id')
            ->pluck('product_id')
            ->toArray();

        $unsoldProducts = \App\Models\Product::whereNotIn('id', $soldProductIds)
            ->with('primaryImage')
            ->take(10)
            ->get();

        // ============================================
        // LAPORAN METODE PEMBAYARAN
        // ============================================
        $paymentMethods = Order::where('payment_status', 'verified')
            ->whereNotNull('verified_at')
            ->whereBetween('verified_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->groupBy('payment_method')
            ->get();

        // ============================================
        // LAPORAN KURIR
        // ============================================
        $couriers = Order::where('payment_status', 'verified')
            ->whereNotNull('verified_at')
            ->whereBetween('verified_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->select(
                'courier',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->groupBy('courier')
            ->get();

        // ============================================
        // CUSTOMER TERLOYAL (Top 10)
        // ============================================
        $topCustomers = Order::where('payment_status', 'verified')
            ->whereNotNull('verified_at')
            ->whereBetween('verified_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_spent')
            )
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->take(10)
            ->with('user')
            ->get();

        // ============================================
        // DAFTAR TRANSAKSI
        // ============================================
        $transactions = (clone $baseQuery)
            ->with(['user', 'items'])
            ->orderBy('verified_at', 'desc')
            ->paginate(20);

        return view('pages.dashboard.report.index', compact(
            'startDate',
            'endDate',
            'filterType',
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'avgOrder',
            'totalProductsSold',
            'salesChart',
            'topProducts',
            'unsoldProducts',
            'paymentMethods',
            'couriers',
            'topCustomers',
            'transactions'
        ));
    }

    // ============================================
    // EXPORT CSV
    // ============================================
    public function export(Request $request)
    {
        $startDate = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        $orders = Order::where('payment_status', 'verified')
            ->whereNotNull('verified_at')
            ->whereBetween('verified_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->with('user')
            ->orderBy('verified_at', 'desc')
            ->get();

        $fileName = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Order Number',
                'Customer',
                'Email',
                'Total',
                'Payment Method',
                'Courier',
                'Status',
                'Verified At'
            ]);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? '-',
                    $order->user->email ?? '-',
                    $order->total,
                    $order->payment_method,
                    $order->courier,
                    $order->status,
                    $order->verified_at ? $order->verified_at->format('Y-m-d H:i:s') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
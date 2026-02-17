<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        // 1. หาค่าวันที่ขายชิ้นแรกสุด (Default Start)
        $firstOrderDate = Order::whereIn('shipping_status', ['shipping', 'completed'])->min('created_at');
        $defaultStart = $firstOrderDate ? \Carbon\Carbon::parse($firstOrderDate)->format('Y-m-d') : now()->subDays(30)->format('Y-m-d');

        // 2. ค่า Default End คือ วันนี้
        $defaultEnd = now()->format('Y-m-d');

        // 3. ตรวจสอบค่าจาก Request: ถ้าว่าง (โดนกด Clear) ให้ใช้ Default
        $start = $request->filled('start_date') ? $request->start_date : $defaultStart;
        $end = $request->filled('end_date') ? $request->end_date : $defaultEnd;

        // 4. ดึงข้อมูลยอดขายตามช่วงวันที่ (Logic เดิม)
        $query = Order::whereIn('shipping_status', ['shipping', 'completed'])
            ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);

        $totalRevenue = $query->sum('total_amount');
        $orderCount = $query->count();

        // 5. ดึงข้อมูลกราฟ (Logic เดิม)
        $chartData = Order::whereIn('shipping_status', ['shipping', 'completed'])
            ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('admin.reports.sales', compact('totalRevenue', 'orderCount', 'chartData', 'start', 'end'));
    }
}

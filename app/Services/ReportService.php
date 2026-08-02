<?php

namespace App\Services;

class ReportService
{
    public function getDashboardMetrics()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');

        $totalSalesCount = $db->table('bookings')->where('payment_status', 'paid')->countAllResults();
        
        $todayRevenue = $db->table('bookings')
            ->selectSum('final_amount')
            ->where('payment_status', 'paid')
            ->where('DATE(created_at)', $today)
            ->get()->getRow()->final_amount ?? 0;

        $monthlyRevenue = $db->table('bookings')
            ->selectSum('final_amount')
            ->where('payment_status', 'paid')
            ->where('created_at >=', $monthStart)
            ->get()->getRow()->final_amount ?? 0;

        $totalPassengers = $db->table('booking_passengers bp')
            ->join('bookings b', 'b.id = bp.booking_id')
            ->where('b.payment_status', 'paid')
            ->countAllResults();

        $todayTripsCount = $db->table('trips')
            ->where('trip_date', $today)
            ->countAllResults();

        $activeBoatsCount = $db->table('speed_boats')
            ->where('status', 'active')
            ->countAllResults();

        // Chart 1: Sales Trend Last 7 Days
        $salesTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $rev  = $db->table('bookings')
                ->selectSum('final_amount')
                ->where('payment_status', 'paid')
                ->where('DATE(created_at)', $date)
                ->get()->getRow()->final_amount ?? 0;
            $salesTrend['labels'][] = date('d M', strtotime($date));
            $salesTrend['data'][]   = (float)$rev;
        }

        // Chart 2: Top Popular Routes
        $topRoutes = $db->table('bookings b')
            ->select('loc1.city as origin, loc2.city as destination, COUNT(b.id) as total_bookings, SUM(b.final_amount) as total_revenue')
            ->join('trips t', 't.id = b.trip_id')
            ->join('schedules sch', 'sch.id = t.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->where('b.payment_status', 'paid')
            ->groupBy('r.id')
            ->orderBy('total_bookings', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return [
            'total_sales'      => $totalSalesCount,
            'today_revenue'    => (float)$todayRevenue,
            'monthly_revenue'  => (float)$monthlyRevenue,
            'total_passengers' => $totalPassengers,
            'today_trips'      => $todayTripsCount,
            'active_boats'     => $activeBoatsCount,
            'sales_trend'      => $salesTrend,
            'top_routes'       => $topRoutes
        ];
    }

    public function getSalesReport(?string $startDate = null, ?string $endDate = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('bookings b')
            ->select('b.booking_code, b.customer_name, b.customer_phone, b.total_passengers, b.final_amount, b.status, b.payment_status, b.created_at,
                      t.trip_code, t.trip_date, t.departure_time, sb.name as boat_name,
                      loc1.name as origin_name, loc2.name as destination_name')
            ->join('trips t', 't.id = b.trip_id')
            ->join('speed_boats sb', 'sb.id = t.speed_boat_id')
            ->join('schedules sch', 'sch.id = t.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->orderBy('b.created_at', 'DESC');

        if ($startDate) {
            $builder->where('DATE(b.created_at) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(b.created_at) <=', $endDate);
        }

        return $builder->get()->getResultArray();
    }
}

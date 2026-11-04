<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today()->toDateString();
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

        // Key Metrics
        $totalToday = Ticket::whereDate('service_date', $today)->count();
        $waitingToday = Ticket::whereDate('service_date', $today)->where('status', 'waiting')->count();
        $calledToday = Ticket::whereDate('service_date', $today)->where('status', 'called')->count();
        $completedToday = Ticket::whereDate('service_date', $today)->where('status', 'completed')->count();
        $skippedToday = Ticket::whereDate('service_date', $today)->where('status', 'skipped')->count();

        $totalWeek = Ticket::whereDate('service_date', '>=', $startOfWeek)->count();
        $totalMonth = Ticket::whereDate('service_date', '>=', $startOfMonth)->count();

        // Average wait time in minutes today
        $avgWaitMinutes = Ticket::whereDate('service_date', $today)
            ->whereNotNull('called_at')
            ->reorder()
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, called_at)) as avg_wait')
            ->value('avg_wait');
        $avgWaitMinutes = $avgWaitMinutes ? round((float) $avgWaitMinutes, 1) : 0;

        // Breakdown by service today
        $services = Service::active()->get();
        $serviceStats = [];
        foreach ($services as $service) {
            $count = Ticket::where('service_id', $service->id)
                ->whereDate('service_date', $today)
                ->count();
            $serviceStats[] = [
                'name' => $service->name,
                'code' => $service->code,
                'color' => $service->color,
                'count' => $count,
                'percentage' => $totalToday > 0 ? round(($count / $totalToday) * 100, 1) : 0,
            ];
        }

        // Daily trend last 7 days
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->toDateString();
            $count = Ticket::whereDate('service_date', $dateString)->count();
            $completed = Ticket::whereDate('service_date', $dateString)->where('status', 'completed')->count();

            $last7Days[] = [
                'date' => $date->translatedFormat('d M'),
                'total' => $count,
                'completed' => $completed,
            ];
        }

        // Active counters
        $activeCounters = Counter::active()->count();
        $totalPetugas = User::where('role', 'petugas')->where('is_active', true)->count();

        // Recent 10 tickets
        $recentTickets = Ticket::with(['service', 'counter', 'user'])
            ->whereDate('service_date', $today)
            ->latest('updated_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalToday',
            'waitingToday',
            'calledToday',
            'completedToday',
            'skippedToday',
            'totalWeek',
            'totalMonth',
            'avgWaitMinutes',
            'serviceStats',
            'last7Days',
            'activeCounters',
            'totalPetugas',
            'recentTickets'
        ));
    }
}

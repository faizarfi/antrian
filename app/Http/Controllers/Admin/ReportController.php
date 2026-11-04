<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * View all tickets with filters & pagination.
     */
    public function index(Request $request): View
    {
        $services = Service::all();
        $counters = Counter::all();
        $officers = User::where('role', 'petugas')->get();

        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $serviceId = $request->input('service_id');
        $counterId = $request->input('counter_id');
        $userId = $request->input('user_id');
        $status = $request->input('status');

        $query = Ticket::with(['service', 'counter', 'user'])
            ->whereDate('service_date', '>=', $startDate)
            ->whereDate('service_date', '<=', $endDate);

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        if ($counterId) {
            $query->where('counter_id', $counterId);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($status) {
            $query->where('status', $status);
        }

        // Statistics for current filtered query
        $stats = [
            'total' => (clone $query)->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'skipped' => (clone $query)->where('status', 'skipped')->count(),
            'waiting' => (clone $query)->where('status', 'waiting')->count(),
            'called' => (clone $query)->where('status', 'called')->count(),
        ];

        // Average wait time (reorder removes any ORDER BY clause that violates MySQL ONLY_FULL_GROUP_BY aggregate rules)
        $avgWait = (clone $query)->whereNotNull('called_at')
            ->reorder()
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, called_at)) as avg_wait')
            ->value('avg_wait');
        $stats['avg_wait'] = $avgWait ? round((float) $avgWait, 1) : 0;

        $tickets = (clone $query)->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.reports.index', compact(
            'tickets',
            'services',
            'counters',
            'officers',
            'startDate',
            'endDate',
            'serviceId',
            'counterId',
            'userId',
            'status',
            'stats'
        ));
    }

    /**
     * Export filtered tickets to CSV (Excel compatible).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $serviceId = $request->input('service_id');
        $counterId = $request->input('counter_id');
        $userId = $request->input('user_id');
        $status = $request->input('status');

        $query = Ticket::with(['service', 'counter', 'user'])
            ->whereDate('service_date', '>=', $startDate)
            ->whereDate('service_date', '<=', $endDate);

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        if ($counterId) {
            $query->where('counter_id', $counterId);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $filename = 'rekap_antrean_bps_'.$startDate.'_sd_'.$endDate.'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel indonesian character encoding compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'ID Tiket',
                'Nomor Antrean',
                'Nama Pengunjung',
                'Instansi / Asal',
                'Kode Layanan',
                'Nama Layanan',
                'Tanggal Pelayanan',
                'Waktu Pengambilan',
                'Waktu Pemanggilan',
                'Waktu Selesai',
                'Loket',
                'Petugas Melayani',
                'Jumlah Panggilan',
                'Status',
                'Waktu Tunggu (Menit)',
                'Durasi Layanan (Menit)',
                'Kepuasan (SKM)',
                'Ulasan Kepuasan',
                'Catatan',
            ]);

            $query->orderBy('created_at', 'asc')->chunk(200, function ($tickets) use ($handle) {
                foreach ($tickets as $ticket) {
                    $waitMinutes = $ticket->called_at ? $ticket->created_at->diffInMinutes($ticket->called_at) : '';
                    $serviceMinutes = ($ticket->called_at && $ticket->completed_at) ? $ticket->called_at->diffInMinutes($ticket->completed_at) : '';

                    fputcsv($handle, [
                        $ticket->id,
                        $ticket->ticket_number,
                        $ticket->visitor_name ?? '-',
                        $ticket->visitor_institution ?? '-',
                        $ticket->service ? $ticket->service->code : '',
                        $ticket->service ? $ticket->service->name : '',
                        $ticket->service_date->format('Y-m-d'),
                        $ticket->created_at->format('H:i:s'),
                        $ticket->called_at ? $ticket->called_at->format('H:i:s') : '',
                        $ticket->completed_at ? $ticket->completed_at->format('H:i:s') : '',
                        $ticket->counter ? $ticket->counter->name : '',
                        $ticket->user ? $ticket->user->name : '',
                        $ticket->call_count,
                        strtoupper($ticket->status),
                        $waitMinutes,
                        $serviceMinutes,
                        $ticket->feedback_label ?? '-',
                        $ticket->feedback_comment ?? '-',
                        $ticket->notes ?? '',
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

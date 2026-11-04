<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Setting;
use App\Services\QueueService;
use Illuminate\View\View;

class GuideController extends Controller
{
    public function __construct(
        protected QueueService $queueService
    ) {}

    /**
     * Display the comprehensive user guide page for visitors, operators, and administrators.
     */
    public function index(): View
    {
        $officeName = Setting::get('office_name', 'BPS KABUPATEN KARANGANYAR');
        $officeAddress = Setting::get('office_address', 'Jl. Lawu No. 385, Karanganyar');
        $services = Service::active()->get();
        $counter = Counter::active()->first();
        $onlineUrl = $this->queueService->getPublicOnlineUrl();

        return view('admin.guide.index', compact(
            'officeName',
            'officeAddress',
            'services',
            'counter',
            'onlineUrl'
        ));
    }
}

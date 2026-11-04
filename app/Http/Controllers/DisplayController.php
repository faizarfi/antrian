<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Setting;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DisplayController extends Controller
{
    public function __construct(
        protected QueueService $queueService
    ) {}

    /**
     * TV Display Main View (1920x1080 Full HD landscape).
     */
    public function tv(): View
    {
        $officeName = Setting::get('office_name', 'BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR');
        $runningText = Setting::get('running_text', 'Selamat Datang di Pelayanan Statistik Terpadu BPS Kabupaten Karanganyar • Seluruh Pelayanan BPS Bebas Biaya (Gratis) • Maklumat Pelayanan: Memberikan Pelayanan Cepat, Tepat, dan Akurat');
        $welcomeTitle = Setting::get('tv_welcome_title', 'Selamat Datang di Pelayanan Statistik Terpadu');
        $welcomeSubtitle = Setting::get('tv_welcome_subtitle', 'BPS Kabupaten Karanganyar siap melayani kebutuhan data statistik Anda dengan profesional, berintegritas, dan amanah.');

        $audioEnabled = Setting::get('audio_enabled', '1') === '1';
        $audioChime = Setting::get('audio_chime', '1') === '1';
        $audioVoiceRate = (float) Setting::get('audio_voice_rate', '0.9');
        $audioVoicePitch = (float) Setting::get('audio_voice_pitch', '1.0');
        $audioVolume = (float) Setting::get('audio_volume', '1.0');

        $announcements = Announcement::active()->get();
        $initialSnapshot = $this->queueService->getTodaySnapshot();
        $onlineUrl = $this->queueService->getPublicOnlineUrl();

        return view('display.tv', compact(
            'officeName',
            'runningText',
            'welcomeTitle',
            'welcomeSubtitle',
            'audioEnabled',
            'audioChime',
            'audioVoiceRate',
            'audioVoicePitch',
            'audioVolume',
            'announcements',
            'initialSnapshot',
            'onlineUrl'
        ));
    }

    /**
     * JSON snapshot for polling fallback or AJAX refresh.
     */
    public function data(): JsonResponse
    {
        $snapshot = $this->queueService->getTodaySnapshot();

        return response()->json($snapshot);
    }

    /**
     * Server-Sent Events (SSE) stream for low-latency live push.
     */
    public function stream(Request $request): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            // Keep connection alive for a brief cycle or push current state
            $snapshot = $this->queueService->getTodaySnapshot();
            echo 'data: '.json_encode($snapshot)."\n\n";
            ob_flush();
            flush();
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}

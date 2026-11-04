<?php

namespace Tests\Unit;

use App\Models\Counter;
use App\Models\Service;
use App\Models\Ticket;
use PHPUnit\Framework\TestCase;

class SpokenTextTest extends TestCase
{
    public function test_spoken_text_indonesian_pronunciation(): void
    {
        $service = new Service(['code' => 'A', 'name' => 'Pelayanan Perpustakaan']);
        $counter = new Counter(['number' => 1, 'name' => 'Loket 1']);

        $ticket = new Ticket([
            'ticket_number' => 'A-001',
            'queue_number' => 1,
        ]);
        $ticket->setRelation('service', $service);
        $ticket->setRelation('counter', $counter);

        $expected = 'Nomor antrean A nol nol satu, pelayanan perpustakaan, silakan menuju loket satu.';
        $this->assertEquals($expected, $ticket->spoken_text);
    }
}

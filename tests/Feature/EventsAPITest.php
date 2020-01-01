<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Events;
use Carbon\Carbon;

class EventsAPITest extends TestCase
{
    use TestsBase;
    protected $api = '/api/events';

    public function testExample()
    {
        $this->setupDB();
        $this->getEvents();
    }

    public function getEvents()
    {
        $endpoint = 'getEventsByMonth';

        $validResponse = [$this->event->toArray()];

        $year = $this->date->year;
        $month = $this->date->month;

        // Valid request
        $response = $this->API($endpoint, "year=$year&month=$month");
        $this->assertValidResponse($response, $validResponse);

        // Invalid request
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);

        // Invalid request
        $response = $this->API($endpoint, 'year=1999&month=13');
        $this->checkResponseCode($response, 400);

        // Valid request, no data
        $response = $this->API($endpoint, 'year=1970&month=1');
        $this->checkResponseCode($response, 404);

        $this->checkCaching($endpoint, "year=$year&month=$month");
    }

    public function setupDB()
    {
        $this->event = factory(Events::class)->create();

        $this->date = new Carbon();
        $this->event->date_from = $this->date;
        $this->event->date_to = $this->date;
        $this->event->save();
    }
}

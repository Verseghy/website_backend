<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Events;
use Carbon\Carbon;

class EventsAPITest extends TestsBase
{
    protected $api = '/api/events';
    
    public function testExample()
    {
        $this->setupDB();
        $this->getEvents();
    }
    
    public function getEvents()
    {
        $endpoint = 'getEventsByMonth';
        
        $validResponse = array($this->event->toArray());
        
        $year = $this->date->year;
        $month = $this->date->month;
        
        // Valid request
        $response = $this->API($endpoint, 'year='.$year.'&month='.$month);
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
        
        $farDate = 'Mon, 4 Jan 2100 00:00:00';
        $oldDate = 'Mon, 5 Jan 1970 00:00:00';
        
        
        // Valid request with if-mod-since header
        // (new data)
        $response = $this->API($endpoint, 'year='.$year.'&month='.$month, ['If-modified-since'=>$oldDate]);
        $this->assertValidResponse($response, $validResponse);
            
        // Valid request with if-mod-since header
        // (not modified)
        $response = $this->API($endpoint, 'year='.$year.'&month='.$month, ['If-modified-since'=>$farDate]);
        $this->checkResponseCode($response, 304);
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

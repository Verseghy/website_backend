<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsAPITest extends TestsBase
{
    protected $api = '/api/events';
    
    public function testExample()
    {
        $this->getEvents();
    }
    
    public function getEvents()
    {
        $endpoint = 'getEventsByMonth';
        
        
        // Valid request
        $response = $this->API($endpoint, 'year=1999&month=2');
        $this->assertContains($response->status(), array(404,200));
        
        // Invalid request
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
    }
}

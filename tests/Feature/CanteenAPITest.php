<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanteenAPITest extends TestsBase
{
    protected $api = '/api/canteen';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->getMenus();
        $this->getCanteen();
    }
    
    public function getMenus()
    {
        $endpoint = 'getCanteenMenus';
        
        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response);
        
        // Invalid request
        $response = $this->API($endpoint, 'id=6');
        $this->checkResponseCode($response, 400);
    }
    
    public function getCanteen()
    {
        $endpoint = 'getCanteenByWeek';
        
        // Valid request
        $response = $this->API($endpoint, 'year=1999&week=5');
        $this->assertContains($response->status(), array(404,200));
        
        // Invalid request
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
    }
}

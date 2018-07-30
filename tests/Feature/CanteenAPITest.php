<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Canteens;
use App\Models\Canteens\Menus;
use Carbon\Carbon;

class CanteenAPITest extends TestCase
{
    use TestsBase;
    protected $api = '/api/canteen';

    protected $dbSetUp = false;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->getMenus();
        $this->getCanteen();
        
        $this->setupDB();
        
        $this->getMenus();
        $this->getCanteen();
    }
    
    public function getMenus()
    {
        $endpoint = 'getCanteenMenus';
        
        
        
        if (!$this->dbSetUp) {
            // Invalid request
            $response = $this->API($endpoint, 'id=6');
            $this->checkResponseCode($response, 400);


            // Invalid request
            $response = $this->API($endpoint);
            $this->checkResponseCode($response, 400);


            // Valid request, no data alaviable
            $response = $this->API($endpoint, 'id=1');
            $this->checkResponseCode($response, 404);
        } else {
            $validResp = array($this->meal->setHidden(['canteens', 'created_at', 'updated_at'])->toArray());
        
            $farDate = 'Mon, 4 Jan 2100 00:00:00 GMT';
            $oldDate = 'Mon, 5 Jan 1970 00:00:00 GMT';
        
            // Valid request
            $response = $this->API($endpoint, 'id=1');
            $this->assertValidResponse($response, $validResp);
            
            // Valid request with if-mod-since header
            // (new data)
            $response = $this->API($endpoint, 'id=1', ['If-modified-since'=>$oldDate]);
            $this->assertValidResponse($response, $validResp);
            
            // Valid request with if-mod-since header
            // (not modified)
            $response = $this->API($endpoint, 'id=1', ['If-modified-since'=>$farDate]);
            $this->checkResponseCode($response, 304);
        }
    }
    
    public function getCanteen()
    {
        $endpoint = 'getCanteenByWeek';
        
        
        if (!$this->dbSetUp) {
            // Invalid request
            $response = $this->API($endpoint);
            $this->checkResponseCode($response, 400);
            
            // Valid request, no data
            $response = $this->API($endpoint, 'year=1970&week=1');
            $this->checkResponseCode($response, 404);
        } else {
            $year = Carbon::now()->year;
            $week = Carbon::now()->weekOfYear;
            
            $validResponse = array($this->canteen->setHidden(['date', 'created_at', 'updated_at'])->toArray());
            
            $farDate = 'Mon, 4 Jan 2100 00:00:00';
            $oldDate = 'Mon, 5 Jan 1970 00:00:00';
            
            // Valid request
            $response = $this->API($endpoint, 'year='.$year.'&week='.$week);
            $this->assertValidResponse($response, $validResponse);
            
            // Valid request with if-mod-since header
            // (new data)
            $response = $this->API($endpoint, 'year='.$year.'&week='.$week, ['If-modified-since'=>$oldDate]);
            $this->assertValidResponse($response, $validResponse);
            
            // Valid request with if-mod-since header
            // (not modified)
            $response = $this->API($endpoint, 'year='.$year.'&week='.$week, ['If-modified-since'=>$farDate]);
            $this->checkResponseCode($response, 304);
        }
    }
    
    public function setupDB()
    {
        $this->soup = new Menus();
        $this->meal = new Menus();
        $this->dessert = new Menus();
        
        $this->soup->type = 0;
        $this->meal->type = 1;
        $this->dessert->type = 2;
        
        $this->soup->menu = 'Soup';
        $this->meal->menu = 'Meal';
        $this->dessert->menu = 'Nothing';
        
        $this->soup->save();
        $this->meal->save();
        $this->dessert->save();
        
        $this->canteen = new Canteens;
        $this->canteen->date = Carbon::now();
        
        $this->canteen->menus()->attach($this->soup);
        $this->canteen->menus()->attach($this->meal);
        $this->canteen->menus()->attach($this->dessert);
        
        $this->canteen->save();
        
        $this->dbSetUp = true;
    }
}

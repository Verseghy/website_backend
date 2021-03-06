<?php

namespace Tests\Feature;

use Tests\TestCase;
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
            $validResp = [$this->meal->setHidden(['canteens', 'created_at', 'updated_at'])->toArray()];

            // Valid request, no data alaviable
            $response = $this->API($endpoint, 'id=1');
            $this->assertValidResponse($response, $validResp);

            $this->checkCaching($endpoint, 'id=1');
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

            $validResponse = [$this->canteen->setHidden(['date', 'created_at', 'updated_at'])->toArray()];

            // Valid request
            $response = $this->API($endpoint, "year=$year&week=$week");
            $this->assertValidResponse($response, $validResponse);

            $this->checkCaching($endpoint, "year=$year&week=$week");
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

        $this->canteen = new Canteens();
        $this->canteen->date = Carbon::now();

        $this->canteen->menus()->attach($this->soup);
        $this->canteen->menus()->attach($this->meal);
        $this->canteen->menus()->attach($this->dessert);

        $this->canteen->save();

        $this->dbSetUp = true;
    }
}

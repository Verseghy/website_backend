<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Newsletter;

class NewsletterAPITest extends TestCase
{
    use TestsBase;
    protected $api = '/api/newsletter';

    protected $dbSetUp = false;

    /**
     * A basic test example.
     */
    public function testExample()
    {
        $this->setupDB();

        $this->subscribe();
        $this->unsubscribe();
    }

    public function subscribe()
    {
        $endpoint = 'subscribe';

        $response = $this->API($endpoint, 'email=asd@asd.com'.'&mldata={1, 2, 3}');
        $this->checkResponseCode($response, 200);

        //Can't add duplicate
        $response = $this->API($endpoint, 'email=asd@asd.com'.'&mldata={1, 2, 3}');
        $this->checkResponseCode($response, 409);

        $response = $this->API($endpoint, 'email='.'&mldata={1, 2, 3}');
        $this->checkResponseCode($response, 400);

        $response = $this->API($endpoint, 'email=asdasd@asd.com'.'&mldata=');
        $this->checkResponseCode($response, 400);
    }

    public function unsubscribe()
    {
        $endpoint = 'unsubscribe';

        $response = $this->API($endpoint, 'email='.'&token='.$this->newsletter->token);
        $this->checkResponseCode($response, 400);

        $response = $this->API($endpoint, 'email='.$this->newsletter->email.'&token=');
        $this->checkResponseCode($response, 401);

        $response = $this->API($endpoint, 'email=ghj@ghj.com'.'&token='.$this->newsletter->token);
        $this->checkResponseCode($response, 400);

        $response = $this->API($endpoint, 'email='.$this->newsletter->email.'&token='.str_random(32));
        $this->checkResponseCode($response, 401);

        $response = $this->API($endpoint, 'email='.$this->newsletter->email.'&token='.$this->newsletter->token);
        $this->checkResponseCode($response, 200);
    }

    public function setupDB()
    {
        $this->newsletter = factory(Newsletter::class)->create();
        $this->newsletter->save();

        $this->dbSetUp = true;
    }
}

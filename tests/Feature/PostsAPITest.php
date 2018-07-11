<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsAPITest extends TestsBase
{
    protected $api = '/api/posts';
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->listPosts();
        $this->byId();
        $this->byLabel();
        $this->byAuthor();
    }
    
    /**
     * Test listPosts API endpoint
     *
     * @return void
     */
    public function listPosts()
    {
        $endpoint = 'listPosts';
    
        // Valid request without parameter
        $response = $this->API($endpoint);
        $this->assertValidResponse($response);
        
        // Valid request with optional parameter
        $response = $this->API($endpoint, 'page=1');
        $this->assertValidResponse($response);
        
        // Valid request with invalid parameter
        // ( ignores it )
        $response = $this->API($endpoint, 'page=-4');
        $this->assertValidResponse($response);
        
        // Valid request for non-existent resource
        $response = $this->API($endpoint, 'page=5000000');
        $this->checkResponseCode($response, 404);
        
        
        // Valid request, use If-mod-since header
        // ( Should return 304 not modified )
        // implemented on antother branch
        /*
        $farDate = Carbon::now()->addYear()->toRfc7231String();
        $response = $this->API($endpont, '', ['If-modified-since'=>$farDate]);
        $this->checkResponseCode($response, 304);
        */
    }
    
    public function byId()
    {
        $endpoint = 'getPost';
    
        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response);
        
        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
        
        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=-6');
        $this->checkResponseCode($response, 404);
    }
    
    public function byLabel()
    {
        $endpoint = 'getPostsByLabel';
    
        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response);
        
        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
        
        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=-6');
        $this->checkResponseCode($response, 404);
    }
    
    public function byAuthor()
    {
        $endpoint = 'getPostsByAuthor';
    
        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response);
        
        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
        
        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=-6');
        $this->checkResponseCode($response, 404);
    }
}

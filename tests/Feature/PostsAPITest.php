<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Posts;
use App\Models\Posts\Labels;
use App\Models\Posts\Images;
use App\Models\Posts\Authors;

class PostsAPITest extends TestCase
{
    use TestsBase;
    protected $api = '/api/posts';
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->setupDB();
    
        $this->listPosts();
        $this->byId();
        $this->byLabel();
        $this->byAuthor();
        $this->search();
        $this->recommend();
    }
    
    /**
     * Test listPosts API endpoint
     *
     * @return void
     */
    public function listPosts()
    {
        $endpoint = 'listPosts';
    
        $validResponse = array($this->post->setHidden(['content','images','author_id', 'index_image', 'date', 'created_at', 'updated_at', 'mldata'])->toArray());
        
        
        // Valid request without parameter
        $response = $this->API($endpoint);
        $this->assertValidResponse($response, $validResponse);
        
        // Valid request with optional parameter
        $response = $this->API($endpoint, 'page=1');
        $this->assertValidResponse($response, $validResponse);
        
        // Valid request with invalid parameter
        // ( ignores it )
        $response = $this->API($endpoint, 'page=-4');
        $this->assertValidResponse($response, $validResponse);
        
        // Valid request for non-existent resource
        $response = $this->API($endpoint, 'page=2');
        $this->checkResponseCode($response, 404);
        
        
        $this->checkCaching($endpoint);
    }
    
    public function byId()
    {
        $endpoint = 'getPost';
    
        $validResponse = $this->post->setHidden(['content','images','author_id', 'index_image', 'date', 'created_at', 'updated_at', 'mldata'])->toArray();
    
        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response, $validResponse);
        
        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
        
        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=2');
        $this->checkResponseCode($response, 404);
        
        
        $this->checkCaching($endpoint, 'id=1');
    }
    
    public function byLabel()
    {
        $endpoint = 'getPostsByLabel';
    
        $validResponse = array($this->post->setHidden(['content','images','author_id', 'index_image', 'date', 'created_at', 'updated_at', 'mldata'])->toArray());
    
        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response, $validResponse);
        
        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
        
        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=2');
        $this->checkResponseCode($response, 404);
        
        
        $this->checkCaching($endpoint, 'id=1');
    }
    
    public function byAuthor()
    {
        $endpoint = 'getPostsByAuthor';
    
        $validResponse = array($this->post->setHidden(['content','images','author_id', 'index_image', 'date', 'created_at', 'updated_at', 'mldata'])->toArray());
    
        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response, $validResponse);
        
        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
        
        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=-6');
        $this->checkResponseCode($response, 404);
        
        
        $this->checkCaching($endpoint, 'id=1');
    }
    
    public function search()
    {
        $endpoint = 'search';
        
        $searchTerm = str_word_count($this->post->title, 1)[0];
        
        $validResponse = array($this->post->setHidden(['content','images','author_id', 'index_image', 'date', 'created_at', 'updated_at', 'mldata'])->toArray());
    
        // Valid request
        $response = $this->API($endpoint, "term=$searchTerm");
        $this->assertValidResponse($response, $validResponse);
        
        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);
        
        // Valid request
        // No resource
        $response = $this->API($endpoint, 'term=GARBAGE');
        $this->checkResponseCode($response, 404);
        
        
        $this->checkCaching($endpoint, "term=$searchTerm");
    }
    
    public function recommend()
    {
        $endpoint = 'getRecommends';
     
        $validResponse = array($this->post->setHidden(['content','images','author_id', 'index_image', 'date', 'created_at', 'updated_at', 'mldata'])->toArray());
        
        
        // Valid request
        // No 'mldata'
        $response = $this->API($endpoint);
        $this->assertValidResponse($response, $validResponse);
        
        
        $mldata = urlencode('{"1":4}');
        
        // Valid request
        // As no other post is alaviable, we get an empty array
        $response = $this->API($endpoint, "mldata=$mldata");
        $this->assertValidResponse($response, []);
    }
    
    public function setupDB()
    {
        factory(Authors::class)->create();
        $label = factory(Labels::class, 1)->create();
        $this->post = factory(Posts::class, 1)->create()->first();
        $this->post->labels()->attach($label);
        $this->post->save();
        factory(Images::class, 'postImage', 2)->create();
        
        $this->post->labels;
        $this->post->author;
        $this->post->index_image;
        $this->post->images;
    }
}

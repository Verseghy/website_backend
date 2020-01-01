<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Posts;
use App\Models\Posts\Labels;
use App\Models\Posts\Authors;

class PostsAPITest extends TestCase
{
    use TestsBase;
    protected $api = '/api/posts';

    /**
     * A basic test example.
     */
    public function testExample()
    {
        $this->setupDB();

        $this->listPosts();
        $this->byId();
        $this->byLabel();
        $this->byAuthor();
        $this->search();
        $this->getPreview();
        $this->byYearMonth();
        $this->countByMonth();
    }

    /**
     * Test listPosts API endpoint.
     */
    public function listPosts()
    {
        $endpoint = 'listPosts';

        $validResponse = [$this->post->setHidden(['content', 'author_id', 'index_image', 'date', 'created_at', 'updated_at', 'published', 'previewToken'])->toArray()];

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

        $validResponse = $this->post->setHidden(['content', 'author_id', 'index_image', 'date', 'created_at', 'updated_at', 'published', 'previewToken'])->toArray();

        // Valid request
        $response = $this->API($endpoint, 'id=1');
        $this->assertValidResponse($response, $validResponse);

        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint);
        $this->checkResponseCode($response, 400);

        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=20');
        $this->checkResponseCode($response, 404);

        // Valid request, not published resoutrce
        $id = $this->hiddenPost->id;
        $response = $this->API($endpoint, "id=$id");
        $this->checkResponseCode($response, 404);

        $this->checkCaching($endpoint, 'id=1');
    }

    public function byLabel()
    {
        $endpoint = 'getPostsByLabel';

        $validResponse = [$this->post->setHidden(['content', 'author_id', 'index_image', 'date', 'created_at', 'updated_at', 'published', 'previewToken'])->toArray()];

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

        $validResponse = [$this->post->setHidden(['content', 'author_id', 'index_image', 'date', 'created_at', 'updated_at', 'published', 'previewToken'])->toArray()];

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

        $validResponse = [$this->post->setHidden(['content', 'author_id', 'index_image', 'date', 'created_at', 'updated_at', 'published', 'previewToken'])->toArray()];

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

    public function getPreview()
    {
        $endpoint = 'getPreview';

        $validResponse = $this->hiddenPost->setHidden(['content', 'author_id', 'index_image', 'date', 'created_at', 'updated_at', 'published', 'previewToken'])->toArray();
        $token = $this->hiddenPost->previewToken;
        $id = $this->hiddenPost->id;
        // Valid request
        $response = $this->API($endpoint, "id=$id&token=$token");
        $this->assertValidResponse($response, $validResponse);

        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint, "token=$token");
        $this->checkResponseCode($response, 400);

        $response = $this->API($endpoint, '');
        $this->checkResponseCode($response, 400);

        // unauthorized request
        // no token
        $response = $this->API($endpoint, "id=$id");
        $this->checkResponseCode($response, 401);

        // wrong token
        $response = $this->API($endpoint, "id=$id&token=ASD");
        $this->checkResponseCode($response, 401);

        // Valid request
        // No resource
        $response = $this->API($endpoint, 'id=-6');
        $this->checkResponseCode($response, 404);

        $this->checkCaching($endpoint, "id=$id&token=$token");
    }

    public function setupDB()
    {
        factory(Authors::class)->create();
        $label = factory(Labels::class, 1)->create();
        $this->post = factory(Posts::class, 1)->create()->first();
        $this->post->labels()->attach($label);
        $this->post->save();

        $this->post->labels;
        $this->post->author;

        $this->hiddenPost = factory(Posts::class, 1)->create()->first();
        $this->hiddenPost->labels()->attach($label);
        $this->hiddenPost->save();

        $this->hiddenPost->labels;
        $this->hiddenPost->author;
        $this->hiddenPost->published = false;
        $this->hiddenPost->save();
    }

    public function byYearMonth()
    {
        $endpoint = 'getPostsByYearMonth';

        $validResponse = [$this->post->setHidden(['content', 'author_id', 'index_image', 'date', 'created_at', 'updated_at', 'published', 'previewToken'])->toArray()];

        $date = Carbon::instance($this->post->date);

        $month = $date->month;
        $year = $date->year;

        // Valid request
        $response = $this->API($endpoint, "year=$year&month=$month");
        $this->assertValidResponse($response, $validResponse);

        // Invalid request
        // ( missing parameter )
        $response = $this->API($endpoint); // nothing
        $this->checkResponseCode($response, 400);

        $response = $this->API($endpoint, "year=$year"); // only year
        $this->checkResponseCode($response, 400);

        $response = $this->API($endpoint, "month=$month"); // only month
        $this->checkResponseCode($response, 400);

        // Valid request
        // No resource
        $invalidYear = $year + 1;
        $invalidMonth = $month + 1;
        $response = $this->API($endpoint, "year=$invalidYear&month=$invalidMonth");
        $this->checkResponseCode($response, 404);

        $this->checkCaching($endpoint, "year=$year&month=$month");
    }

    public function countByMonth()
    {
        $endpoint = 'getCountByMonth';

        $date = Carbon::instance($this->post->date);

        $month = $date->month;
        $year = $date->year;

        $validResponse = [['year' => $year, 'month' => $month, 'count' => 1]];

        // Valid request
        $response = $this->API($endpoint);
        $this->assertValidResponse($response, $validResponse);

        $this->checkCaching($endpoint, "year=$year&month=$month");
    }
}

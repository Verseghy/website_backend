<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

trait TestsBase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    protected function API($url, $params = '', $headers = array())
    {
        $root = $this->api;
        if ('/' !== substr($root, -1)) {
            $root = $root.'/';
        }
        if ('?' !== substr($params, -1)) {
            $params = '?'.$params;
        }

        $actual_headers = array();

        foreach ($headers as $header => $value) {
            $actual_headers['HTTP_'.$header] = $value;
        }

        return $this->call('GET', $root.$url.$params, [], [], [], $actual_headers);
    }

    protected function checkResponseCode($response, $code)
    {
        $this->assertEquals($code, $response->status());
    }

    protected function assertValidResponse($response, $content = array(), bool $fragment = false)
    {
        $response->assertOk();
        if ($fragment) {
            $response->assertJsonFragment($content);
        } else {
            $response->assertJson($content);
        }
    }

    protected function checkCaching($endpoint, $params = '')
    {
        $farDate = 'Mon, 4 Jan 2100 00:00:00';
        $oldDate = 'Mon, 5 Jan 1970 00:00:00';

        // Valid request with if-mod-since header
        // (new data)
        $response = $this->API($endpoint, $params, ['If-modified-since' => $oldDate]);
        $response->assertOk();

        // Valid request with if-mod-since header
        // (not modified)
        $response = $this->API($endpoint, $params, ['If-modified-since' => $farDate]);
        $this->checkResponseCode($response, 304);
    }
}

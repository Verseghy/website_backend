<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestsBase extends TestCase
{
    use RefreshDatabase;
    protected function API($url, $params = '', $headers = array())
    {
        $root = $this->api;
        if (substr($root, -1)!=='/') {
            $root = $root.'/';
        }
        if (substr($params, -1)!=='?') {
            $params = '?'.$params;
        }
    
        return $this->call('GET', $root.$url.$params, [], [], [], $headers);
    }
    
    protected function checkResponseCode($response, $code)
    {
        $this->assertEquals($response->status(), $code);
    }
    
    protected function assertValidResponse($response, $content = array())
    {
        $response->assertOk();
        $response->assertJson($content);
    }
}

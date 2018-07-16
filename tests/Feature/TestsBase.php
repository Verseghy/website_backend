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
        
        $actual_headers = array();
        
        foreach($headers as $header => $value)
        {
            $actual_headers['HTTP_'.$header] = $value;
        }
        
        return $this->call('GET', $root.$url.$params, [], [], [], $actual_headers);
    }
    
    protected function checkResponseCode($response, $code)
    {
        $this->assertEquals($code, $response->status());
    }
    
    protected function assertValidResponse($response, $content = array())
    {
        $response->assertOk();
        $response->assertJson($content);
    }
}

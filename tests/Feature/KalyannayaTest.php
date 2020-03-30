<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Kalyannaya;

class KalyannayaTest extends TestCase
{
    
    /**
     * Tests CREATE Kalyannaya
     *
     * @return void
    */ 
    public function testKalyannayaCreate()
    {
        $name = uniqid();
        
        $response = $this->json('POST', '/api/kalyannayas', ['name' => $name]);
        $response->assertStatus(200);
        
        $response = $this->json('POST', '/api/kalyannayas', ['name' => $name]);
        $response->assertStatus(404);
    }
    
    /**
     * Tests GET Kalyannayas list
     *
     * @return void
     */
    public function testKalyannayaList()
    {
        $this->json('GET', '/api/kalyannayas')->assertStatus(200);
    }
    
    /**
     * Tests GET Kalyannaya
     *
     * @return void
     */
    public function testKalyannayaShow()
    {
        $lastId = Kalyannaya::orderBy('created_at', 'desc')->first();
        
        $this->json('GET', '/api/kalyannayas/'.$lastId->id)->assertStatus(200);
    }
    
    /**
     * Tests UPDATE Kalyannaya
     *
     * @return void
    */ 
    public function testKalyannayaUpdate()
    {
        $lastId = Kalyannaya::orderBy('created_at', 'desc')->first();
        
        $response = $this->json('PUT', '/api/kalyannayas/'.$lastId->id, ['name' => uniqid()]);
        $response->assertStatus(200);
    }

    /**
     * Tests DELETE Kalyannaya
     *
     * @return void
    */ 
    public function testKalyannayaDestroy()
    {
        $lastId = Kalyannaya::orderBy('created_at', 'desc')->first();
        
        $response = $this->json('DELETE', '/api/kalyannayas/'.$lastId->id)->assertStatus(200);
    }
    
    
}
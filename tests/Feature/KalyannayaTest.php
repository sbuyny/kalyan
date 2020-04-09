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
        
        //call api to create new kalyannaya
        $response200 = $this->json('POST', '/api/kalyannayas', ['name' => $name]);
        $kalyannayaId = $response200->baseResponse->getData()->data->id;
        $response200->assertStatus(200);
        
        // load kalyannaya using Eloquent
        $model_kalyannayas = Kalyannaya::find($kalyannayaId);
        $model_kalyannayas_name = $model_kalyannayas->name;
        $this->assertEquals($name, $model_kalyannayas_name);
        
        //delete test Kalyannaya
        Kalyannaya::destroy($kalyannayaId);
    }
    
    /**
     * Tests GET Kalyannayas list
     *
     * @return void
     */
    public function testKalyannayaList()
    {
        //call api to show kalyannayas list
        $response = $this->json('GET', '/api/kalyannayas')->assertStatus(200);
        
        $kalyannayas = $response->baseResponse->getData()->data;
        foreach($kalyannayas as $kalyannaya){
            // load kalyannaya using Eloquent
            $model_kalyannayas = Kalyannaya::find($kalyannaya->id);
            $this->assertEquals($kalyannaya->name, $model_kalyannayas->name);
        }
    }
    
    /**
     * Tests GET Kalyannaya
     *
     * @return void
     */
    public function testKalyannayaShow()
    {
        //create new Kalyannaya
        $kalyannaya = Kalyannaya::create(['name' => uniqid()]);

        //call api to show this kalyannaya
        $this->json('GET', '/api/kalyannayas/'.$kalyannaya['id'])->assertStatus(200);
        
        // load kalyannaya using Eloquent
        $model_kalyannaya = Kalyannaya::find($kalyannaya['id']);
        $this->assertEquals($kalyannaya['name'], $model_kalyannaya->name);
        
        //delete test Kalyannaya
        Kalyannaya::destroy($kalyannaya['id']);
    }
    
    /**
     * Tests UPDATE Kalyannaya
     *
     * @return void
    */ 
    public function testKalyannayaUpdate()
    {
        //create new Kalyannaya
        $kalyannaya = Kalyannaya::create(['name' => uniqid()]);
        
        //call api to change name this kalyannaya to 'NewTestName'
        $response = $this->json('PUT', '/api/kalyannayas/'.$kalyannaya['id'], ['name' => 'NewTestName']);
        $response->assertStatus(200);
        
        // load kalyannaya using Eloquent
        $model_kalyannaya = Kalyannaya::find($kalyannaya['id']);
        $this->assertEquals('NewTestName', $model_kalyannaya->name);
        
        //delete test Kalyannaya
        Kalyannaya::destroy($kalyannaya['id']);
    }

    /**
     * Tests DELETE Kalyannaya
     *
     * @return void
    */ 
    public function testKalyannayaDestroy()
    {
        //create new Kalyannaya
        $kalyannaya = Kalyannaya::create(['name' => uniqid()]);
        
        //call api to delete this kalyannaya
        $response = $this->json('DELETE', '/api/kalyannayas/'.$kalyannaya['id'])->assertStatus(200);
        
        // load kalyannaya using Eloquent
        $model_kalyannaya = Kalyannaya::find($kalyannaya['id']);
        $this->assertEquals(NULL, $model_kalyannaya);
    }
    
    
}
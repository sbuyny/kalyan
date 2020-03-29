<?php
namespace App\Repositories\Interfaces;

interface KalyanRepositoryInterface
{
    /**
     * Return list of Kalyannayas
     * 
     * @return \Illuminate\Http\Response
     */
    public function all();
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id);
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Http\Response
     */
    public function create($input);
    
    /**
     * Return number of kalyannayas with that name
     * 
     * @param  string  $name
     * @return int
     */
    public function findByNameCount($name);
    
    /**
     * Return numbet of kalyannayas with that name
     * 
     * @param  int  $id
     * @param  string  $name
     * @return int
     */
    public function findByNameCountWithoutSelf($id, $name);
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findByKalyannayaId($id);
}

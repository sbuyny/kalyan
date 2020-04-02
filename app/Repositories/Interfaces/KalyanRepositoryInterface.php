<?php
namespace App\Repositories\Interfaces;

interface KalyanRepositoryInterface
{
    /**
     * Return list of Kalyannayas
     * 
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function all(): object;
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find(int $id): ?object;
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function create(array $input): object;
    
    /**
     * Return number of kalyannayas with that name
     * 
     * @param  string  $name
     * @return int
     */
    public function findByNameCount(string $name): int;
    
    /**
     * Return numbet of kalyannayas with that name
     * 
     * @param  int  $id
     * @param  string  $name
     * @return int
     */
    public function findByNameCountWithoutSelf(int $id, string $name): int;
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findByKalyannayaId(int $id): object;
}

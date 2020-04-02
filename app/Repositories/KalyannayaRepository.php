<?php
namespace App\Repositories;

use App\Kalyannaya;
use App\Repositories\Interfaces\KalyannayaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class KalyannayaRepository implements KalyannayaRepositoryInterface
{

    /**
     * Return Kalyannayas
     * 
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function all(): object
    {
        return Kalyannaya::all();
    }
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find(int $id): ?object
    {
        return Kalyannaya::find($id);
    }
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function create(array $input): object
    {
        return Kalyannaya::create($input);
    }
    
    /**
     * Return number of kalyannayas with that name
     * 
     * @param  string  $name
     * @return int
     */
    public function findByNameCount(string $name): int
    {
        $exists = Kalyannaya::select('id')
            ->where('name', '=', $name)
            ->get()
            ->count();

        return $exists;
    }
    
    /**
     * Return numbet of kalyannayas with that name
     * 
     * @param  int  $id
     * @param  string  $name
     * @return int
     */
    public function findByNameCountWithoutSelf(int $id, string $name): int
    {
        $exists = Kalyannaya::select('id')
            ->where([
                ['name', '=', $name],
                ['id', '!=', $id],
            ])
            ->get()
            ->count();

        return $exists;
    }
}

<?php
namespace App\Repositories;

use App\Kalyan;
use App\Repositories\Interfaces\KalyanRepositoryInterface;
use Illuminate\Support\Facades\DB;

class KalyanRepository implements KalyanRepositoryInterface
{

    /**
     * Return Kalyans
     * 
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function all(): object
    {
        return Kalyan::all();
    }
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findOrFail(int $id): ?object
    {
        return Kalyan::findOrFail($id);
    }
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findByKalyannayaId(int $id): object
    {
        return Kalyan::where('kalyannaya_id', $id)->orderBy('name')->get();
    }
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function create(array $input): object
    {
        return Kalyan::create($input);
    }
    
    /**
     * Return numbet of kalyannayas with that name
     * 
     * @param  string  $name
     * @return int
     */
    public function findByNameCount(string $name): int
    {
        $exists = Kalyan::select('id')
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
        $exists = Kalyan::select('id')
            ->where([
                ['name', '=', $name],
                ['id', '!=', $id],
            ])
            ->get()
            ->count();

        return $exists;
    }
}

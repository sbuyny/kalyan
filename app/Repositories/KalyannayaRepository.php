<?php
namespace App\Repositories;

use App\Kalyannaya;
use App\Repositories\Interfaces\KalyannayaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class KalyannayaRepository implements KalyannayaRepositoryInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    const TABLE = 'kalyannayas';

    /**
     * Return Kalyannayas
     * 
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return Kalyannaya::all();
    }
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        return Kalyannaya::find($id);
    }
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Http\Response
     */
    public function create($input)
    {
        return Kalyannaya::create($input);
    }
    
    /**
     * Return number of kalyannayas with that name
     * 
     * @param  string  $name
     * @return int
     */
    public function findByNameCount($name)
    {
        $exists = DB::table(self::TABLE)
            ->select('id')
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
    public function findByNameCountWithoutSelf($id, $name)
    {
        $exists = DB::table(self::TABLE)
            ->select('id')
            ->where([
                ['name', '=', $name],
                ['id', '!=', $id],
            ])
            ->get()
            ->count();

        return $exists;
    }
}

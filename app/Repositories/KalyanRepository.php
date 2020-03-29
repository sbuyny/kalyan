<?php
namespace App\Repositories;

use App\Kalyan;
use App\Repositories\Interfaces\KalyanRepositoryInterface;
use Illuminate\Support\Facades\DB;

class KalyanRepository implements KalyanRepositoryInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    const TABLE = 'kalyans';

    /**
     * Return Kalyans
     * 
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return Kalyan::all();
    }
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        return Kalyan::find($id);
    }
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findByKalyannayaId($id)
    {
        return Kalyan::where('kalyannaya_id', $id)->orderBy('name')->get();
    }
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Http\Response
     */
    public function create($input)
    {
        return Kalyan::create($input);
    }
    
    /**
     * Return numbet of kalyannayas with that name
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

<?php
namespace App\Repositories;

use App\Booking;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    const TABLE = 'bookings';

    /**
     * Return Kalyans
     * 
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return Booking::all();
    }
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        return Booking::find($id);
    }
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findByKalyannayaId($id)
    {
        return Booking::where('kalyannaya_id', $id)->orderBy('name')->get();
    }
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Http\Response
     */
    public function create($input)
    {
        return Booking::create($input);
    }
    
    /**
     * Return list of users made bookings
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findUsers()
    {
        $users = DB::table(self::TABLE)
            ->select('name')
            ->groupBy('name')
            ->orderBy('name')
            ->get();

        return $users;
    }
    
    /**
     * Return kalyans not available for booking for all kalyannayas by data
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansNotAvailableSearch($input)
    {
        $kalyansNotAvailable = array();

        $kalyansNotAvailableSearch = DB::table('booking_kalyans')
            ->leftJoin('bookings', 'bookings.id', '=', 'booking_kalyans.booking_id')
            ->select('booking_kalyans.kalyan_id')
            ->whereBetween('bookings.from', [$input['from'], $input['to']])
            ->orWhereBetween('bookings.to', [$input['from'], $input['to']])
            ->groupBy('booking_kalyans.kalyan_id')
            ->orderBy('booking_kalyans.kalyan_id', 'asc')
            ->get();

        foreach ($kalyansNotAvailableSearch as $k => $v) {
            array_push($kalyansNotAvailable, $v->kalyan_id);
        }

        return $kalyansNotAvailable;
    }
    
    /**
     * Return kalyans available for booking for all kalyannayas
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansAvailableSearch($input)
    {
        $kalyansNotAvailable = self::kalyansNotAvailableSearch($input);

        //search for available kalyans data in current kalyannaya
        $kalyansAvailableData = DB::table('kalyans')
            ->select('id', 'name', 'trubok')
            ->whereNotIn('id', $kalyansNotAvailable)
            ->orderBy('name')
            ->get();

        return $kalyansAvailableData;
    }
    
    /**
     * Return kalyans available for booking for current kalyannaya
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansAvailableData($input)
    {
        $kalyansNotAvailable = self::kalyansNotAvailableSearch($input);

        //search for available kalyans data in current kalyannaya
        $kalyansAvailableData = DB::table('kalyans')
            ->select('id', 'trubok')
            ->where('kalyannaya_id', '=', $input['kalyannaya_id'])
            ->whereNotIn('id', $kalyansNotAvailable)
            ->orderBy('trubok', 'desc')
            ->get();

        return $kalyansAvailableData;
    }
}

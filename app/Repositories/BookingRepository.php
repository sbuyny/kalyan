<?php
namespace App\Repositories;

use App\Booking;
use App\BookingKalyan;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;

class BookingRepository implements BookingRepositoryInterface
{
    const BRON_MINUTES = 30;
    const TRUBKA_PERSONS = 2;

    /**
     * Return Kalyans
     * 
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function all(): object
    {
        return Booking::all();
    }
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find(int $id): ?object
    {
        return Booking::find($id);
    }
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findByKalyannayaId(int $id): object
    {
        return Booking::where('kalyannaya_id', $id)->orderBy('name')->get();
    }
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function create(array $input): object
    {
        //calculate booking finish time for create booking
        $to = strtotime($input['from']) + self::BRON_MINUTES * 60;

        $input['to'] = date("Y-m-d H:i:s", $to);

        //search for available kalyans for selected time in all kalyannayas
        $kalyansAvailableData = self::kalyansAvailableData($input);
        
        $trubokAvailable = 0;

        foreach ($kalyansAvailableData as $k => $v) {
            $trubokAvailable += $v->trubok;
        }

        if ($trubokAvailable < $input['people'] / self::TRUBKA_PERSONS) {
            $response = [
            'success' => false,
            'message' => 'We don\'t have enough tubes in that time in that kalyannaya for those peoples.',
            ];
            return response()->json($response, 200);
        }
        
        $booking = Booking::create($input);
        
        $bk['booking_id'] = $booking['id'];

        $enough = 0;
        $trubokAdded = 0;

        //add kayans to booking
        foreach ($kalyansAvailableData as $k => $v) {
            //reserve kalyan until enough
            if ($enough == 0) {
                $bk['kalyan_id'] = $v->id;
                $booking_kalyan = BookingKalyan::create($bk);
                $trubokAdded += $v->trubok;
                if ($trubokAdded >= $input['people'] / self::TRUBKA_PERSONS) {
                    $enough = 1;
                }
            }
        }
        return $booking;
    }
    
    /**
     * Return list of users made bookings
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findUsers(): object
    {
        $users = Booking::select('name')
            ->groupBy('name')
            ->orderBy('name')
            ->get();

        return $users;
    }
    
    /**
     * Return kalyans not available for booking for all kalyannayas by data
     *
     * @param  array $input
     * @return array
     */
    public function kalyansNotAvailableSearch(array $input): array
    {
        $kalyansNotAvailable = [];

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
    public function kalyansAvailableSearch(array $input): object
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
    public function kalyansAvailableData(array $input): object
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

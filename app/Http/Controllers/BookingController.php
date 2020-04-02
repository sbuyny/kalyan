<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\BookingKalyan;
use App\Kalyannaya;
use Validator;

class BookingController extends BaseController
{

    const BRON_MINUTES = 30;
    const TRUBKA_PERSONS = 2;
    
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    private $bookingRepository;
    
    /**
     * Class constructor.
     * 
     * @param  \App\Repositories\Interfaces\BookingRepositoryInterface $bookingRepository
     */
    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Display a listing of the Bookings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): object
    {
        $booking = $this->bookingRepository->all();

        return $this->sendResponse($booking->toArray(), 'Bookings retrieved successfully.');
    }

    /**
     * Display a listing of Bookings for kalyannaya id.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kalyannaya(int $id): object
    {

        $booking = $this->bookingRepository->findByKalyannayaId($id);

        return $this->sendResponse($booking->toArray(), 'Bookings searched successfully.');
    }

    /**
     * Store a newly created Booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): object
    {
        $input = $request->all();

        $messages = [
            'people.integer' => 'Number of peoples need to be a digit.',
            'people.max' => 'We don\'t allow more than :max people.',
        ];

        $validator = Validator::make($input, [
                'name' => 'required|max:255',
                'people' => 'required|integer|max:50',
                'from' => 'required|date',
                'kalyannaya_id' => 'required|integer',
                ], $messages);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        //check if kalyannaya exists
        $kalyannaya = Kalyannaya::find($input['kalyannaya_id']);
        if (!isset($kalyannaya->id)) {
            return $this->sendError('Kalyannaya with that id not found.');
        }

        //calculate booking finish time for create booking
        $to = strtotime($input['from']) + self::BRON_MINUTES * 60;

        $input['to'] = date("Y-m-d H:i:s", $to);

        //search for available kalyans for selected time in all kalyannayas
        $kalyansAvailableData = $this->bookingRepository->kalyansAvailableData($input);

        $trubokAvailable = 0;

        foreach ($kalyansAvailableData as $k => $v) {
            $trubokAvailable += $v->trubok;
        }

        if ($trubokAvailable < $input['people'] / self::TRUBKA_PERSONS) {
            return $this->sendError('We don\'t have enough tubes in that time in that kalyannaya for those peoples.');
        }

        //create booking
        $booking = $this->bookingRepository->create($input);

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

        return $this->sendResponse($booking->toArray(), 'Booking created successfully.');
    }

    /**
     * Display the specified Booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): object
    {
        $booking = $this->bookingRepository->find($id);

        if (is_null($booking)) {
            return $this->sendError('Booking not found.');
        }

        return $this->sendResponse($booking->toArray(), 'Booking retrieved successfully.');
    }

    /**
     * Display a listing of the users, ordered kalyans.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(): object
    {
        $users = $this->bookingRepository->findUsers();

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully.');
    }
    
    /**
     * Display a listing of the kalyans, available to booking for selected date and number of people.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request): object
    {
        $input = $request->all();

        $messages = [
            'people.integer' => 'Number of peoples need to be a digit.',
            'people.max' => 'We don\'t allow more than :max people.',
        ];

        $validator = Validator::make($input, [
                'people' => 'required|integer|max:50',
                'from' => 'required|date',
                'to' => 'required|date',
                ], $messages);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $kalyansAvailableData = $this->bookingRepository->kalyansAvailableSearch($input);

        return $this->sendResponse($kalyansAvailableData->toArray(), 'Kalyans searched successfully.');
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Kalyannaya;
use App\Booking;
use Validator;

class BookingController extends BaseController
{
    
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

        //create booking
        $booking = $this->bookingRepository->create($input);

        if (!($booking instanceof Booking)) {
            return $this->sendError('We don\'t have enough tubes in that time in that kalyannaya for those peoples.');
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

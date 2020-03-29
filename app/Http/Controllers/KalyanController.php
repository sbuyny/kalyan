<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Repositories\Interfaces\KalyanRepositoryInterface;
use App\Kalyan;
use Validator;

class KalyanController extends BaseController
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    private $kalyanRepository;
    
    /**
     * Class constructor.
     */
    public function __construct(KalyanRepositoryInterface $kalyanRepository)
    {
        $this->kalyanRepository = $kalyanRepository;
    }
    
    /**
     * Display a listing of the Kalyans.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kalyan = $this->kalyanRepository->all();

        return $this->sendResponse($kalyan->toArray(), 'Kalyans retrieved successfully.');
    }

    /**
     * Display a listing of Kalyan for kalyannaya id.
     * 
     * @return \Illuminate\Http\Response
     */
    public function kalyannaya($id)
    {
        $kalyan = $this->kalyanRepository->findByKalyannayaId($id);

        return $this->sendResponse($kalyan->toArray(), 'Kalyans searched successfully.');
    }

    /**
     * Store a newly created Kalyan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
                'name' => 'required|max:255',
                'trubok' => 'required|integer|max:10',
                'kalyannaya_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $sameName = $this->kalyanRepository->findByNameCount($input['name']);

        if ($sameName > 0) {
            return $this->sendError('Kalyan with that name already exists.');
        }

        $kalyan = $this->kalyanRepository->create($input);

        return $this->sendResponse($kalyan->toArray(), 'Kalyan created successfully.');
    }

    /**
     * Display the specified Kalyan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kalyan = $this->kalyanRepository->find($id);

        if (is_null($kalyan)) {
            return $this->sendError('Kalyan not found.');
        }

        return $this->sendResponse($kalyan->toArray(), 'Kalyana retrieved successfully.');
    }

    /**
     * Update the specified Kalyan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kalyan $kalyan)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
                'name' => 'required',
                'trubok' => 'required',
                'kalyannaya_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $sameName = $this->kalyanRepository->findByNameCountWithoutSelf($kalyan->id, $input['name']);

        if ($sameName > 0) {
            return $this->sendError('Kalyan with that name already exists.');
        }
        
        $kalyan->name = $input['name'];
        $kalyan->trubok = $input['trubok'];
        $kalyan->kalyannaya_id = $input['kalyannaya_id'];
        $kalyan->save();

        return $this->sendResponse($kalyan->toArray(), 'Kalyan updated successfully.');
    }

    /**
     * Remove the specified Kalyan from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kalyan $kalyan)
    {
        $kalyan->delete();

        return $this->sendResponse($kalyan->toArray(), 'Kalyan deleted successfully.');
    }
}

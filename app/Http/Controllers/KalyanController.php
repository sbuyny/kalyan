<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Repositories\Interfaces\KalyanRepositoryInterface;
use App\Kalyan;
use App\Kalyannaya;
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
     * 
     * @param  App\Repositories\Interfaces\KalyanRepositoryInterface $kalyanRepository
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
    public function index(): object
    {
        $kalyan = $this->kalyanRepository->all();

        return $this->sendResponse($kalyan->toArray(), 'Kalyans retrieved successfully.');
    }

    /**
     * Display a listing of Kalyan for kalyannaya id.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kalyannaya(int $id): object
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
    public function store(Request $request): object
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
        
        //check if kalyannaya exists
        $kalyannaya = Kalyannaya::findOrFail($input['kalyannaya_id']);

        $kalyan = $this->kalyanRepository->create($input);

        return $this->sendResponse($kalyan->toArray(), 'Kalyan created successfully.');
    }

    /**
     * Display the specified Kalyan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): object
    {
        $kalyan = $this->kalyanRepository->findOrFail($id);

        return $this->sendResponse($kalyan->toArray(), 'Kalyana retrieved successfully.');
    }

    /**
     * Update the specified Kalyan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KalyanKalyan $kalyan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kalyan $kalyan): object
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
     * @param  \App\KalyanKalyan $kalyan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kalyan $kalyan): object
    {
        $kalyan->delete();

        return $this->sendResponse($kalyan->toArray(), 'Kalyan deleted successfully.');
    }
}

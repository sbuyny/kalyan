<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Repositories\Interfaces\KalyannayaRepositoryInterface;
use App\Kalyannaya;
use Validator;

class KalyannayaController extends BaseController
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    private $kalyannayaRepository;
    
    /**
     * Class constructor.
     * 
     * @param  App\Repositories\Interfaces\KalyannayaRepositoryInterface $kalyannayaRepository
     */
    public function __construct(KalyannayaRepositoryInterface $kalyannayaRepository)
    {
        $this->kalyannayaRepository = $kalyannayaRepository;
    }
    
    /**
     * Display a listing of the Kalyannayas.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): object
    {
        $kalyannayas = $this->kalyannayaRepository->all();

        return $this->sendResponse($kalyannayas->toArray(), 'Kalyannayas retrieved successfully.');
    }

    /**
     * Store a newly created Kalyannaya.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): object
    {
        $input = $request->all();

        $validator = Validator::make($input, [
                'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $sameName = $this->kalyannayaRepository->findByNameCount($input['name']);

        if ($sameName > 0) {
            return $this->sendError('Kalyannaya with that name already exists.');
        }

        $kalyannaya = $this->kalyannayaRepository->create($input);

        return $this->sendResponse($kalyannaya->toArray(), 'Kalyannaya created successfully.');
    }

    /**
     * Display the specified Kalyannaya.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): object
    {
        $kalyannaya = $this->kalyannayaRepository->find($id);

        if (is_null($kalyannaya)) {
            return $this->sendError('Kalyannaya not found.');
        }

        return $this->sendResponse($kalyannaya->toArray(), 'Kalyannaya retrieved successfully.');
    }

    /**
     * Update the specified Kalyannaya in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kalyannaya\Kalyannaya $kalyannaya
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kalyannaya $kalyannaya): object
    {
        $input = $request->all();

        $validator = Validator::make($input, [
                'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        $sameName = $this->kalyannayaRepository->findByNameCountWithoutSelf($kalyannaya->id, $input['name']);

        if ($sameName > 0) {
            return $this->sendError('Kalyannaya with that name already exists.');
        }

        $kalyannaya->name = $input['name'];
        $kalyannaya->save();

        return $this->sendResponse($kalyannaya->toArray(), 'Kalyannaya updated successfully.');
    }

    /**
     * Remove the specified Kalyannaya from storage.
     *
     * @param  \App\Kalyannaya\Kalyannaya $kalyannaya
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kalyannaya $kalyannaya): object
    {
        $kalyannaya->delete();

        return $this->sendResponse($kalyannaya->toArray(), 'Kalyannaya deleted successfully.');
    }
}

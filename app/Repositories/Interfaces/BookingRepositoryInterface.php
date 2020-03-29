<?php
namespace App\Repositories\Interfaces;

interface BookingRepositoryInterface
{
    /**
     * Return list of Kalyannayas
     * 
     * @return \Illuminate\Http\Response
     */
    public function all();
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id);
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Http\Response
     */
    public function create($input);
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findByKalyannayaId($id);
    
    /**
     * Return list of users made bookings
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findUsers();
    
    /**
     * Return kalyans not available for booking for all kalyannayas by data
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansNotAvailableSearch($input);
        
    /**
     * Return kalyans available for booking for all kalyannayas
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansAvailableSearch($input);
    
    /**
     * Return kalyans available for booking for current kalyannaya
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansAvailableData($input);
}

<?php
namespace App\Repositories\Interfaces;

interface BookingRepositoryInterface
{
    /**
     * Return list of Kalyannayas
     * 
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function all(): object;
    
    /**
     * Return Kalyannaya by id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find(int $id): ?object;
    
    /**
     * Create Kalyannaya by id
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function create(array $input): object;
    
    /**
     * Return Kalyans by Kalyannaya id
     * 
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findByKalyannayaId(int $id): object;
    
    /**
     * Return list of users made bookings
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function findUsers(): object;
    
    /**
     * Return kalyans not available for booking for all kalyannayas by data
     *
     * @param  array  $input
     * @return array
     */
    public function kalyansNotAvailableSearch(array $input): array;
        
    /**
     * Return kalyans available for booking for all kalyannayas
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansAvailableSearch(array $input): object;
    
    /**
     * Return kalyans available for booking for current kalyannaya
     *
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function kalyansAvailableData(array $input): object;
}

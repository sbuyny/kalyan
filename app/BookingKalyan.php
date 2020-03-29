<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingKalyan extends Model
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id', 'kalyan_id',
    ];

}

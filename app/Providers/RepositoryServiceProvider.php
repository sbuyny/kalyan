<?php

namespace App\Providers;

use App\Repositories\Interfaces\KalyannayaRepositoryInterface;
use App\Repositories\KalyannayaRepository;
use App\Repositories\Interfaces\KalyanRepositoryInterface;
use App\Repositories\KalyanRepository;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\BookingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(KalyannayaRepositoryInterface::class,KalyannayaRepository::class);
        $this->app->bind(KalyanRepositoryInterface::class,KalyanRepository::class);
        $this->app->bind(BookingRepositoryInterface::class,BookingRepository::class);
    }
}

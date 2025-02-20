<?php

namespace App\Providers;

use App\Models\Truck;
use Illuminate\Support\ServiceProvider;
use DB;
use App\Models\TransportState;
use App\Models\Causes;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Asia/Tashkent');

        // dd(Truck::all());

    }
}

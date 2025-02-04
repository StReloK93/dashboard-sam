<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Http;
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

        // $response = Http::post('http://127.0.0.1:3020/api/save-transports-positions', [
        //     [
        //         'trucks' => [7381],
        //         'excavators' => [
        //             ['group_id' => 4076, 'zone' => 2],
        //             ['group_id' => 10013, 'zone' => 3]
        //         ],
        //         'maingeozone' => 1
        //     ],
        //     [
        //         'trucks' => [10054],
        //         'excavators' => [],
        //         'maingeozone' => 5
        //     ]
        // ]);


        // if ($response->successful()) {
        //     $data = $response->json();
        //     dd($data);
        // } else {
        //     $error = $response->body();
        //     dd($error); // В случае ошибки можно получить тело ответа
        // }
    }
}

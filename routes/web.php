<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{path}', function () {

    return view('app', [
        'oil' => explode(',', env('BASE_OIL')),
        'smena' => explode(',', env('BASE_SMENA')),
        'uat' => explode(',', env('BASE_UAT')),
        'active' => explode(',', env('BASE_ACTIVE')),
        'day_smena' => env('BASE_SMENA_DAY'),
        'day_smena_job' => env('BASE_SMENA_DAY_JOB'),
        'night_smena' => env('BASE_SMENA_NIGHT'),
        'night_smena_job' => env('BASE_SMENA_NIGHT_JOB'),
        'table_link' => env('BASE_TABLE_LINK'),
        'excavators' => env('BASE_EXCAVATORS'),
        'tos' => env('BASE_TOS'),

        'pistali_frontals' => env('PISTALI_FRONTALS'),
        'pistali_mans' => env('PISTALI_MANS'),
        'frontend_mans' => explode(',', env('FRONTEND_PISTALI_MANS')),
        'frontend_frontals' => explode(',', env('FRONTEND_PISTALI_FRONTALS')),
        'gusaks' => env('BASE_GUSAKS_GROUP_ID'),
        'user_ip' => request()->ip(),
        'only_myip' => explode(',', env('BASE_ONLY_MYIP')) ,
    ]);
})->where('path', '.*');
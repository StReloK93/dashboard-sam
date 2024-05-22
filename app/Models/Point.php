<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv_speed';

    protected $table = "ttracks";

    public $timestamps = false;
    protected $fillable = [
        "ID",
        "ObjectID",
        "X",
        "Y",
        "Z",
        "Speed",
        "Created",
        "ZonaX",
        "ZonaY",
    ];

    protected $casts = [
        'average_speed' =>  'float'
        // 'x' => 'float',
        // 'y' => 'float',
        // 'objectid' => 'integer',
        // 'speed' => 'integer',
        // 'row' => 'integer',
        // 'column' => 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'transport_id',
        'x',
        'y',
        'time_message',
        'geozone',
        'distance_ex',
        'created_at',
        'updated_at',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Tashkent')->format('Y-m-d H:i:s');
    }


    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'speed' => 'integer',
        'transport_id' => 'integer',
    ];
}

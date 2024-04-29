<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Transport extends Model
{
    use HasFactory;


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Tashkent')->format('Y-m-d H:i:s');
    }




    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'transport_id' => 'integer',
    ];
}

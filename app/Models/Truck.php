<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;
    protected $connection = 'trucks';
    protected $table = 'sprACPark';

    public $timestamps = false;

    protected $fillable = [
        'transport_id',
        'group_id',
        'group_name',
    ];
}

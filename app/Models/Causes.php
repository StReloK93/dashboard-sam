<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Causes extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $fillable = [
        'transport_state_id',
        'cause_id',
    ];
}

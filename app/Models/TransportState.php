<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Smena;
class TransportState extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function currentDay()
    {
        $smenaClass = new Smena();
        $period = $smenaClass->getPeriod();
        return $this->hasMany(TransportState::class, 'transport_id', 'transport_id')
            ->where('geozone_out', '>', $period['start'])
            ->where('geozone_out', '<', $period['end']);
    }


    public function truck()
    {
        return $this->hasOne(Truck::class, 'transport_id', 'transport_id');
    }
}

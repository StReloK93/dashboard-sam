<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Smena;
use Carbon\Carbon;
class TransportState extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function inSmena()
    {
        return $this->hasMany(TransportState::class, 'transport_id', 'transport_id')->select('cause', 'geozone', 'name', 'geozone_in', 'geozone_out', 'transport_id');
    }


    public function truck()
    {
        return $this->hasOne(Truck::class, 'transport_id', 'transport_id');
    }

    public function tracks()
    {
        return $this->hasMany(Transport::class, 'transport_id', 'transport_id')->select('y', 'x', 'created_at', 'transport_id');
    }

    protected $casts = [
        'transport_id' => 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const KM = 0;

    public const MILES = 1;

    protected $fillable = [
        'entity_id',
        'entity_type',
        'name',
        'street',
        'city',
        'postcode',
        'country',
        'latitude',
        'longitude',
        'sizeradius',
        'sizeradiustype',
        'postcode_map_id',
    ];

    public $timestamps = false;

    protected $appends = [
        'asJson',
    ];

    public function entity()
    {
        return $this->morphTo('entity');
    }

    public function getAsJsonAttribute()
    {
        return json_encode([
            'street' => $this['street'],
            'city' => $this['city'],
            'postcode' => $this['postcode'],
            'country' => $this['country'],
            'latitude' => $this['latitude'],
            'longitude' => $this['longitude'],
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    public $timestamps = false;

    const FARMSCAPE = 0;
    const PROFILE_3 = 1;

    const PROFILE_6 = 2;

    protected $fillable = [
        'name',
        'product_line',
        'image',
        'colour',
        'shop_url',
    ];

    protected $appends = ['product_line_id'];

    protected $with = ['file'];

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'image');
    }

    public function colour()
    {
        return $this->hasOne(Colour::class, 'id', 'colour');
    }

    public function getProductLineIdAttribute()
    {
        return $this->attributes['product_line'];
    }

    public function getProductLineAttribute()
    {
        switch ($this->attributes['product_line']) {
            case self::PROFILE_3:
                return 'Profile 3';
            case self::PROFILE_6:
                return 'Profile 6';
            case self::FARMSCAPE:
                return 'FarmScape';
            default:
                return '';
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostcodeMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'outcode',
        'region_id',
    ];

    public $timestamps = false;

    protected $table = 'postcode_map';

    /**
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}

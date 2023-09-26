<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertType extends Model
{
    use HasFactory;

    protected $table = 'expert_type';

    protected $fillable = [
        'name',
    ];

    /**
     * Get the experts for the ExpertType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function experts()
    {
        return $this->hasMany(Expert::class);
    }
}

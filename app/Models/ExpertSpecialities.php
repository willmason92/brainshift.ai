<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertSpecialities extends Model
{
    use HasFactory;

    protected $table = 'expert_speciality';

    public function experts()
    {
        return $this->belongsToMany(Expert::class, 'expert_id', 'id');
    }

    public function speciality()
    {
        return $this->hasOne(Speciality::class, 'speciality_id', 'id');
    }
}

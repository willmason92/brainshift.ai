<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goals extends Model
{
    use HasFactory;

    public function shedSolution()
    {
        return $this->belongsToMany(ShedSolution::class, ShedSolutionGoals::class, 'shed_solution_id', 'goal_id');
    }
}

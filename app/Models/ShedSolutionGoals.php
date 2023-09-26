<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShedSolutionGoals extends Model
{
    use HasFactory;

    protected $table = 'shed_solution_goals';

    public $timestamps = false;

    protected $fillable = [
        'shed_solution_id',
        'goal_id',
    ];
}

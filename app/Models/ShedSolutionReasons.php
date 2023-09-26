<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShedSolutionReasons extends Model
{
    use HasFactory;

    protected $table = 'shed_solution_reasons';

    public $timestamps = false;

    protected $fillable = [
        'shed_solution_id',
        'reason_id',
    ];
}

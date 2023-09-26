<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShedSolutionMetrics extends Model
{
    use HasFactory;

    public const USER_FARMER = 0;

    public const USER_INSTALLER = 0;

    const METRIC_CREATED = 0;

    const METRIC_EDITED = 1;

    const METRIC_SHARED = 2;

    const METRIC_DELETED = 3;

    public $fillable = [
        'user_id',
        'event_id',
        'shed_solution_id',
        'installer_id',
        'email',
    ];

    public function shedSolution()
    {
        return $this->hasOne(ShedSolution::class);
    }

    public function installer()
    {
        return $this->hasOne(User::class, 'installer_id', 'id');
    }

    /**
     * Log when a Shed Solution is created
     *
     * @param $userId
     * @param $shedSolutionId
     * @return mixed
     */
    public static function logCreateShedSolution($userId, $shedSolutionId) {
        return ShedSolutionMetrics::create([
            'user_id' => $userId,
            'event_id' => self::METRIC_CREATED,
            'shed_solution_id' => $shedSolutionId,
        ]);
    }

    /**
     * Log when a Shed Solution is edited
     *
     * @param $userId
     * @param $shedSolutionId
     * @return mixed
     */
    public static function logEditShedSolution($userId, $shedSolutionId) {
        return ShedSolutionMetrics::create([
            'user_id' => $userId,
            'event_id' => self::METRIC_EDITED,
            'shed_solution_id' => $shedSolutionId,
        ]);
    }

    /**
     * Log when a Shed Solution is deleted
     *
     * @param $userId
     * @param $shedSolutionId
     * @return mixed
     */
    public static function logDeleteShedSolution($userId, $shedSolutionId) {
        return ShedSolutionMetrics::create([
            'user_id' => $userId,
            'event_id' => self::METRIC_DELETED,
            'shed_solution_id' => $shedSolutionId,
        ]);
    }
}

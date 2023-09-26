<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestShareMetrics extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
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
     * Log when a request is sent to an installer via email
     *
     * @param $id
     * @param $shedSolutionId
     * @param $email
     * @return mixed
     */
    public static function logSendEmail($userId, $shedSolutionId, $email)
    {
        return RequestShareMetrics::create([
            'user_id' => $userId,
            'shed_solution_id' => $shedSolutionId,
            'email' => $email,
        ]);
    }

    /**
     * Log when a shed solution is sent to an installer via find an expert
     *
     * @param $id
     * @param $shedSolutionId
     * @param $installerId
     * @return mixed
     */
    public static function logSendFindAnExpert($userId, $shedSolutionId, $installerId)
    {
        return RequestShareMetrics::create([
            'user_id' => $userId,
            'shed_solution_id' => $shedSolutionId,
            'installer_id' => $installerId,
        ]);
    }

    /**
     * Log when a shed solution is sent to an installer via find an expert
     * without an attached project
     *
     * @param $id
     * @param $installerId
     * @return mixed
     */
    public static function logSendFindAnExpertNoProject($userId, $installerId)
    {
        return RequestShareMetrics::create([
            'user_id' => $userId,
            'installer_id' => $installerId,
        ]);
    }
}

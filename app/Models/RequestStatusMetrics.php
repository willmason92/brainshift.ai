<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatusMetrics extends Model
{
    use HasFactory;

    public $fillable = [
        'request_id',
        'user_id',
        'status_before',
        'status_after',
    ];

    public function request()
    {
        return $this->hasOne(Request::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Log when a user changes the request status
     *
     * @param $requestId
     * @param $userId
     * @param $requestStatusBefore
     * @param $requestStatusAfter
     * @return mixed
     */
    public static function logRequestStatusChange($requestId, $userId, $requestStatusBefore, $requestStatusAfter)
    {
        return RequestStatusMetrics::create([
            'request_id' => $requestId,
            'user_id' => $userId,
            'status_before' => $requestStatusBefore,
            'status_after' => $requestStatusAfter,
        ]);
    }
}

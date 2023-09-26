<?php

namespace App\Models;

use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Request extends Model
{
    use HasFactory, EncryptedAttribute;

    protected $fillable = [
        'request_status',
        'user_id',
        'installer_id',
        'message',
        'contact_phone',
        'contact_email',
        'shed_solution_id',
        'project_type',
        'sector_id',
    ];

    protected $with = [
        'shed_solution',
        'user',
        'installer',
        'sector',
    ];

    protected $encryptable = [
        'contact_email', 'contact_phone',
    ];

    protected $appends = [

    ];

    const NEW_REQUEST = 0;

    const CONTACTED = 1;

    const CONVERTED = 2;

    const PROJECT_FINISHED = 3;

    const NOT_CONVERTED = 4;

    const PROJECT_FAILED = 5;

    const NEW_BUILD = 0;

    const REFURB = 1;

    public function shed_solution()
    {
        return $this->belongsTo(ShedSolution::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function installer()
    {
        return $this->hasOne(Expert::class, 'id', 'installer_id');
    }

    public function sector()
    {
        return $this->hasOne(Sector::class, 'id', 'sector_id');
//        return $this->belongsTo(Sector::class,'sector_id');
    }

    /**
     * @return array
     */
    public static function getRequests()
    {
        // TODO: Link to Auth::user()->id;
        $requests = DB::table('requests')
            ->where('installer_id', '=', 1)
            ->get();

        if (! empty($requests)) {
            return $requests;
        } else {
            return [];
        }
    }

    /**
     * @return int
     */
    public static function getNewRequestCount($userId)
    {
        $requestCount = Request::whereHas('installer.user', function($query) {
            $query->whereNull('deleted_at');
        })
            ->where('installer_id', '=', $userId)
            ->where('request_status', '=', Request::NEW_REQUEST)
            ->get()
            ->count();

        if (! empty($requestCount)) {
            return $requestCount;
        } else {
            return 0;
        }
    }

    /**
     * @return int
     */
    public static function getReadRequestCount($userId)
    {
        $requestCount = Request::whereHas('installer.user', function($query) {
            $query->whereNull('deleted_at');
        })
            ->where('installer_id', '=', $userId)
            ->where('request_status', '!=', Request::NEW_REQUEST)
            ->get()
            ->count();

        if (! empty($requestCount)) {
            return $requestCount;
        } else {
            return 0;
        }
    }

    /**
     * Filter requests based on request status
     *
     * @param $query
     * @param $value
     * @return array
     */
    public function scopeFilter($query, $value)
    {
        return $query->when($value, function ($query, $status) {
            $query->where('request_status', $status);
        });
    }

    public static function getTotalRequestCount()
    {
        $requestCount = Request::get()
            ->count();

        if (! empty($requestCount)) {
            return $requestCount;
        } else {
            return 0;
        }
    }

    public static function getTotalNewRequestCount()
    {
        $requestCount = Request::where('request_status', '=', Request::NEW_REQUEST)
            ->get()
            ->count();

        if (! empty($requestCount)) {
            return $requestCount;
        } else {
            return 0;
        }
    }
    public static function getTotalContactedRequestCount()
    {
        $requestCount = Request::whereNot('request_status', '=', Request::NEW_REQUEST)
            ->get()
            ->count();

        if (! empty($requestCount)) {
            return $requestCount;
        } else {
            return 0;
        }
    }
}

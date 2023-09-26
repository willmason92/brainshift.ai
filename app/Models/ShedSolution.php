<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShedSolution extends Model
{
    use HasFactory;

    public const TYPE_NEW_BUILD = 0;

    public const TYPE_REFURBISHMENT = 1;

    public const SIZE_TYPE_METERS = 0;

    public const SIZE_TYPE_FEET = 1;

    public const RESPONSIBILITY_INSTALLER = 0;

    public const RESPONSIBILITY_DIY = 1;

    public const RESPONSIBILITY_DONT_KNOW = 2;

    public const BUILDING_TYPE_WOODEN = 0;

    public const BUILDING_TYPE_METAL = 1;

    public const BUILDING_TYPE_DONT_KNOW = 2;

    protected $fillable = [
        'project_type_id',
        'sector_id',
        'length',
        'width',
        'size_type_id',
        'user_id',
        'title',
        'age',
        'responsibility_id',
        'frame_type',
        'np_json_config',
        'project_file_id',
    ];

    protected $appends = ['project_type', 'size_type', 'responsibility'];

    protected $with = [
        'user',
        'sector',
        'goals',
        'reasons',
        'projectFile',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->projectFile as $file) {
                if (is_object($file)) {
                    \Illuminate\Support\Facades\Storage::delete($file->path);
                    $file->delete();
                }
            }
        });
    }

    public function sector()
    {
        return $this->hasOne(Sector::class, 'id', 'sector_id');
    }

    public function goals()
    {
        return $this->belongsToMany(Goals::class, ShedSolutionGoals::class, 'shed_solution_id', 'goal_id');
    }

    public function reasons()
    {
        return $this->belongsToMany(Reasons::class, ShedSolutionReasons::class, 'shed_solution_id', 'reason_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'shed_solution_id');
    }

    public function shareMetrics()
    {
        return $this->hasMany(RequestShareMetrics::class);
    }

    public function projectFile()
    {
        return $this->hasOne(File::class, 'id', 'project_file_id');
    }

    /**
     * Mutator to get type based on ID given
     *
     * @return string
     */
    public function getProjectTypeAttribute()
    {
        return $this['project_type_id'] === self::TYPE_NEW_BUILD ? 'New Build' : 'Refurbishment';
    }

    /**
     * Mutator to get size type based on ID given
     *
     * @return string
     */
    public function getSizeTypeAttribute()
    {
        return $this['size_type_id'] === $this::SIZE_TYPE_METERS ? 'meter' : 'feet';
    }

    /**
     * Mutator to get responsibility based on ID given
     *
     * @param $value
     * @return string
     */
    public function getResponsibilityAttribute()
    {
        switch ($this['responsibility_id']) {
            case $this::RESPONSIBILITY_INSTALLER:
                return 'Installer';
                break;
            case $this::RESPONSIBILITY_DIY:
                return 'DIY';
                break;
            case $this::RESPONSIBILITY_DONT_KNOW:
                return 'Dont Know';
                break;
        }
    }
}

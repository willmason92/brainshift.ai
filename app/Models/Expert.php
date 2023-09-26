<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expert extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'company_email',
        'nationwide',
        'description',
        'region_id',
        'expert_url',
        'expert_type_id',
        'user_id',
        'location_id',
        'logo',

    ];

    protected $appends = [
        'image',
    ];

    protected $with = ['user', 'location', 'expertLogo', 'expertType', 'sectors', 'specialities'];

    public function location()
    {
        return $this->morphOne(Location::class, 'entity');
    }

    public function user()
    {
        return $this
            ->belongsTo(User::class)
            ->without('expert');
    }

    /**
     * Get the File (logo) for the expert.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function expertLogo()
    {
        return $this->hasOne(File::class, 'id', 'logo');
    }

    /**
     * Get the specialities for the expert.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specialities()
    {
        return $this->belongsToMany(Speciality::class, 'expert_speciality');
    }

    /**
     * Get the experttype for the expert.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expertType()
    {
        return $this->belongsTo(ExpertType::class);
    }

    /**
     * Get the sectors for the expert.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sectors()
    {
        return $this->belongsToMany(Sector::class, SectorUserExpert::class, 'expert_id', 'sector_id');
        //return $this->hasManyThrough(Sector::class, SectorUserExpert::class, 'expert_id', 'id', 'id', 'sector_id');
    }

    /**
     * Get csutom image attribute
     *
     * @return string
     */
    public function getImageAttribute()
    {
        return $this->expertLogo->url ?? null;
    }

    /**
     * Get the requests for the shedSolution.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installerProjects()
    {
        return $this->hasMany(Post::class, 'author', 'user_id');
    }
}

<?php

namespace App\Models;

use ESolution\DBEncryption\Encrypter;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, EncryptedAttribute, HasRoles, SoftDeletes;

    const BANNED = 0;

    const FARMER = 1;

    const INSTALLER = 2;

    const ADMIN = 3;

    const SUPERADMIN = 4;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_by_email',
        'token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $encryptable = [
        'name', 'email', 'phone',
    ];

    protected $with = ['expert', 'location', 'sectors', 'regions'];

    public function location()
    {
        return $this->morphOne(Location::class, 'entity');
    }

    public function expert()
    {
        return $this
            ->hasOne(Expert::class, 'user_id', 'id')
            ->without('user');
    }

    //users (farmers can have 1)
    //installers get their multiple tags via expert
    public function sectors()
    {
        return $this->belongsToMany(Sector::class, SectorUserExpert::class, 'user_id', 'sector_id');
        //return $this->hasManyThrough(Sector::class, SectorUserExpert::class, 'user_id', 'id', 'id', 'sector_id');
    }

    /**
     * @return HasOne
     */
    public function regions(): HasOne
    {
        return $this->hasOne(Region::class);
    }

    /**
     * @return HasManyThrough
     */
    public function postcodeMap(): HasManyThrough
    {
        return $this->hasManyThrough(PostcodeMap::class, Region::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shedSolution()
    {
        return $this->hasMany(ShedSolution::class);
    }
}

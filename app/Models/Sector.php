<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectors';

    /**
     * Get the experts for the sector.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function experts()
    {
        return $this->belongsToMany(Expert::class, 'sector_user_expert');
    }

    /**
     * Get the users for the sector.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'sector_user_expert');
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}

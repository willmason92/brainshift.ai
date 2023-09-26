<?php

namespace App\Models;

use App\Scopes\PostTypeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostType extends Model
{
    use HasFactory;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PostTypeScope);
    }

    public function post()
    {
        return $this->belongsToMany(Post::class);
    }

    public function field()
    {
        return $this->hasMany(Field::class);
    }
}

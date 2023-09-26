<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'posts_id',
        'fields_id',
        'value',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['field'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function field()
    {
        return $this->hasOne(Field::class, 'id', 'fields_id');
    }
}

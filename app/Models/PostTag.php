<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'blog_tag_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function tags()
    {
        return $this->hasOne(Tag::class);
    }
}

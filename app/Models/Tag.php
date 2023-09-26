<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'blog_tags';

    public function posttags()
    {
        return $this->BelongsToMany(PostTag::class);
    }
}

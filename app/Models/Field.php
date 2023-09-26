<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    const FIELD_TEXT = 1;

    const FIELD_DATE = 2;

    const FIELD_FILE = 3;

    const FIELD_CS_LOCATION = 4;

    const FIELD_CS_MATERIAL = 5;

    const FIELD_CS_TYPE = 6;

    const FIELD_IV_VIDEO = 7;

    const FIELD_IV_VIDEO_POSTER = 8;

    const FIELD_URL = 9;

    const FIELD_CS_GALLERY = 10;

    const FIELD_IP_SECTOR = 11;

    const FIELD_IP_SIZE_LENGTH = 12;

    const FIELD_IP_SIZE_WIDTH = 13;

    const FIELD_IP_SIZE_UNIT = 14;

    const CASE_STUDY_TYPE_NEW = 0;

    const CASE_STUDY_TYPE_REFURB = 1;

    public function postMeta()
    {
        return $this->belongsToMany(PostMeta::class, 'Posts', 'posts_id', '');
    }

    public function postType()
    {
        return $this->belongsTo(PostType::class);
    }

    /**
     * Mutator to get case study type based on ID given
     *
     * @param $value
     * @return string
     */
    public function getCaseStudyType($value)
    {
        return __($value == self::CASE_STUDY_TYPE_NEW ? 'New Build' : 'Refurbishment');
    }
}

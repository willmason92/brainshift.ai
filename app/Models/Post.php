<?php

namespace App\Models;

use Carbon\Carbon;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    private $_gallery = null;

    private $_materials = null;

    private $_meta = [];

    const PROJECT_IS_DRAFT = 0;

    const PROJECT_IS_PUBLISHED = 1;

    protected $fillable = [
        'id',
        'post_title',
        'post_content',
        'post_type',
        'author',
        'cover_image',
        'project_published_status',
        'uri_slug',
    ];

    protected $with = ['postType', 'postTags', 'coverImage'];

    protected $appends = [
        'gallery',
        'location',
        'type',
        'type_string',
        'completed_on',
        'materials',
        'document',
        'youtube',
        'youtube_poster',
        'social_media_link',
        'media_partner',
        'sector',
        'length',
        'width',
        'size_unit',
        'delete_key',
    ];

    public function postType()
    {
        return $this->hasOne(PostType::class, 'id', 'post_type');
    }

    public function postTags()
    {
        return $this->hasManyThrough(Tag::class, PostTag::class, 'post_id', 'id', 'id', 'blog_tag_id');
    }

    public function postMeta()
    {
        return $this->hasMany(PostMeta::class, 'posts_id', 'id');
    }

    public function coverImage()
    {
        return $this->hasOne(File::class, 'id', 'cover_image');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author');
    }

    public function getGalleryAttribute()
    {
        if (empty($this->_meta['_gallery'])) {
            $att = $this->postMeta->where('fields_id', 1)->first();
            if (! empty($att) && ! empty(json_decode($att->value))) {
                // Gallery not loaded, load gallery
                $this->_meta['_gallery'] = File::whereIn('id', json_decode($att->value))->get();
            }
        }

        return $this->_meta['_gallery'] ?? [];
    }

    public function getLocationAttribute()
    {
        if (empty($this->_meta['_location'])) {
            $att = $this->postMeta->where('fields_id', 2)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_location'] = Location::where('id', $att->value)->first();
            }
        }

        return $this->_meta['_location'] ?? null;
    }

    public function getTypeAttribute()
    {
        if (empty($this->_meta['_type'])) {
            $att = $this->postMeta->where('fields_id', 3)->first();
            if (! empty($att)) {
                $this->_meta['_type'] = $att->value;
            }
        }

        return $this?->_meta['_type'] ?? null;
    }

    public function getTypeStringAttribute()
    {
        if (empty($this->_meta['_typeString'])) {
            $att = $this->postMeta->where('fields_id', 3)->first();
            if (! empty($att)) {
                $this->_meta['_typeString'] = $att->field->getCaseStudyType($att->value);
            }
        }

        return $this?->_meta['_typeString'] ?? null;
    }

    public function getCompletedOnAttribute()
    {
        if (empty($this->_meta['_completed_on'])) {
            $att = $this->postMeta->where('fields_id', 4)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_completed_on'] = Carbon::parse($att->value);
            }
        }

        return $this->_meta['_completed_on'] ?? null;
    }

    public function getMaterialsAttribute()
    {
        if (empty($this->_meta['_materials'])) {
            $att = $this->postMeta->where('fields_id', 5)->first();
            if (! empty($att) && ! empty($att->value) && ! empty(json_decode($att->value))) {
                $this->_meta['_materials'] = Material::whereIn('id', json_decode($att->value))->get();
            }
        }

        return $this->_meta['_materials'] ?? null;
    }

    public function getDocumentAttribute()
    {
        if (empty($this->_meta['_document'])) {
            $att = $this->postMeta->where('fields_id', 6)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_document'] = File::where('id', $att->value)->first();
            }
        }

        return $this->_meta['_document'] ?? null;
    }

    public function getYouTubeAttribute()
    {
        if (empty($this->_meta['_youtube'])) {
            $att = $this->postMeta->where('fields_id', 7)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_youtube'] = secure_url($att->value);
            }
        }

        return $this->_meta['_youtube'] ?? null;
    }

    public function getYoutubePosterAttribute()
    {
        if (empty($this->_meta['_youtube_poster'])) {
            $att = $this->postMeta->where('fields_id', 8)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_youtube_poster'] = File::where('id', $att->value)->get();
            }
        }

        return $this->_meta['_youtube_poster'] ?? null;
    }

    public function getSocialMediaLinkAttribute()
    {
        if (empty($this->_meta['_social_media_link'])) {
            $att = $this->postMeta->where('fields_id', 9)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_social_media_link'] = secure_url($att->value);
            }
        }

        return $this->_meta['_social_media_link'] ?? null;
    }

    public function getMediaPartnerAttribute()
    {
        if (empty($this->_meta['_media_partner'])) {
            $att = $this->postMeta->where('fields_id', 10)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_media_partner'] = secure_url($att->value);
            }
        }

        return $this->_meta['_media_partner'] ?? null;
    }

    public function getSectorAttribute()
    {
        if (empty($this->_meta['_sector'])) {
            $att = $this->postMeta->where('fields_id', 11)->first();
            if (! empty($att) && ! empty($att->value)) {
                $this->_meta['_sector'] = Sector::where('id', $att->value)->first();
            }
        }

        return $this->_meta['_sector'] ?? null;
    }

    public function getLengthAttribute()
    {
        if (empty($this->_meta['_length'])) {
            $att = $this->postMeta->where('fields_id', 12)->first();
            if (! empty($att) && ! empty($att->value) && ctype_digit($att->value)) {
                $this->_meta['_length'] = $att->value;
            }
        }

        return $this->_meta['_length'] ?? null;
    }

    public function getWidthAttribute()
    {
        if (empty($this->_meta['_width'])) {
            $att = $this->postMeta->where('fields_id', 13)->first();
            if (! empty($att) && ! empty($att->value) && ctype_digit($att->value)) {
                $this->_meta['_width'] = $att->value;
            }
        }

        return $this->_meta['_width'] ?? null;
    }

    public function getSizeUnitAttribute()
    {
        if (empty($this->_meta['_size_unit'])) {
            $att = $this->postMeta->where('fields_id', 14)->first();
            if (! empty($att) && ctype_digit($att->value) && in_array($att->value, [0, 1])) {
                $this->_meta['_size_unit'] = $att->value;
            }
        }

        return $this->_meta['_size_unit'] ?? null;
    }

    /**
     * Filter the posts when radio selection is changed
     *
     * @param $query
     * @param $value
     */
    public function scopeFilter($query, $value)
    {
        $query->when($value, function ($query, $type) {
            $query->where('post_type', $type);
        });
    }

    public function getDeleteKeyAttribute()
    {
        //detect installer projects (postType 1 is descoped)
        if ($this->post_type === 1) {
            return Encrypter::encrypt(json_encode([$this->id, $this->author, $this->created_at->toDateTimeString()]));
        }

        return null;
    }

    /**
     * Return a collection of all Installer Projects
     *
     * @return mixed
     */
    public static function getInstallerProjects()
    {
        return self::where('post_type', 1);
    }
    /**
     * Return an Installer Project based on the Project ID
     *
     * @return mixed
     */
    public static function getInstallerProject($projectId)
    {
        return self::where('post_type', 1)
            ->whereId($projectId);
    }

    public static function getProjectsForAdminInRegion($user)
    {
        $region = $user->regions;
        if (!$region) {
            return response()->json(['message' => 'Region is not defined for the logged-in admin user'], 404);
        }

        $postcodesInRegion = $region->postcodeMap->pluck('outcode');

        $projects = Post::whereHas('author', function ($query) use ($postcodesInRegion) {
            $query->whereHas('location', function ($query) use ($postcodesInRegion) {
                $query->whereIn('postcode_map_id', function ($query) use ($postcodesInRegion) {
                    $query->select('id')
                        ->from('postcode_map')
                        ->whereIn('outcode', $postcodesInRegion);
                });
            });
        })
            ->where('post_type', 1)
            ->orderByDesc('project_published_status')
            ->with('postMeta', 'postType', 'author')
            ->get();

        return $projects;
    }

}

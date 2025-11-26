<?php

namespace App\Models\LearningResources;

use App\Models\Lookups\LearningResourceTypeLookup;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Models\Tags\Tag;
use App\Models\User;

class LearningResource extends Model implements HasMedia
{
    use HasMediaTrait, Filterable;

    protected $table = 'learning_resources';

    protected $fillable = [
        'resource_type',
        'resource_name',
        'resource_short_description',
        'resource_content',
        'resource_url',
        'is_featured',
        'likes',
        'created_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function tags() 
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'LearningResource')
            ->orderBy('order_column');
    }

    public function featured()
    {
        return $this->is_featured;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'learning_resource_user')
            ->withPivot('liked', 'bookmarked')
            ->withTimestamps();
    }

    public function userLike()
    {
        return $this->hasOne(LearningResourceUser::class)
            ->where('user_id', auth()->id());
    }

    public function userBookmark()
    {
        return $this->hasOne(LearningResourceUser::class)
            ->where('user_id', auth()->id());
    }

    public function icon()
    {
        if( $this->resource_type == LearningResourceTypeLookup::TYPE_FILE_UPLOAD )
        {
            return 'fa-download';
        }
        elseif( $this->resource_type == LearningResourceTypeLookup::TYPE_URL )
        {
            return 'fa-external-link';
        }
        elseif( $this->resource_type == LearningResourceTypeLookup::TYPE_TEXT )
        {
            return 'fa-file-text';
        }
        return 'fa-book';
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($learningResource) {
            $learningResource->tags()->detach();
            $learningResource->media()->each(function ($media) {
                $media->delete();
            });
        });
    }
}

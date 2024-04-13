<?php

namespace App\Models;

use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Video extends Model implements Viewable
{
    use HasFactory, InteractsWithViews;
    protected $guarded = ['id'];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            $video->uuid = (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function formats(): HasMany
    {
        return $this->hasMany(VideoFormat::class);
    }
    public function dislikes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')->where('type', 'dislike');
    }
    public function likeModel(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')->where('type', 'like');
    }
    public function updateLikeStatus($type): void
    {
        $existingRating = $this->likeModel()->where('user_id', auth()->id())->first();
        if ($existingRating) {
            if ($existingRating->type === $type) {
                $existingRating->delete();
            } else {
                $existingRating->update([
                    'type' => $type
                ]);
            }
        } else {
            $this->likeModel()->create([
                'user_id' => auth()->id(),
                'type' => $type
            ]);
        }
    }
}

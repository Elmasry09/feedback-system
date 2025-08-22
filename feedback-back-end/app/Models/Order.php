<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasOne;

class Order extends Model
{
    use HasSlug;
    protected $connection = 'mongodb';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answer() : HasOne
    {
        return $this->hasOne(Answer::class);
    }
    
    public function message() : HasOne
    {
        return $this->hasOne(Message::class);
    }

    public function getImageAttribute($value)
    {
        return asset($value);
    }
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}

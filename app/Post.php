<?php

namespace App;

//Spatie is a package that provides a trait that will generate a unique slug when saving any Eloquent model.
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasSlug;

    protected $fillable = ['title', 'description', 'author', 'slug','filename','mime','original_filename'];

    public function getSlugOptions(): SlugOptions
    {

        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
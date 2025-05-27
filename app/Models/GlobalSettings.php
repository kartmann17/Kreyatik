<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Models\SEO;

class GlobalSettings extends Model
{
    protected $fillable = [
        'site_name',
        'default_description',
        'default_keywords',
        'default_image',
        'locale',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
    ];

    public function seo()
    {
        return $this->morphOne(SEO::class, 'model');
    }

    public static function getInstance()
    {
        $instance = static::first();
        if (!$instance) {
            $instance = static::create([
                'site_name' => config('app.name'),
                'locale' => 'fr_FR'
            ]);
        }
        return $instance;
    }
}

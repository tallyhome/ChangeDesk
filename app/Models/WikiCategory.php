<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'order'
    ];

    /**
     * Get the articles for the category.
     */
    public function articles()
    {
        return $this->hasMany(WikiArticle::class, 'wiki_category_id');
    }

    /**
     * Get published articles for the category.
     */
    public function publishedArticles()
    {
        return $this->hasMany(WikiArticle::class, 'wiki_category_id')
            ->where('is_published', true)
            ->orderBy('order');
    }
}
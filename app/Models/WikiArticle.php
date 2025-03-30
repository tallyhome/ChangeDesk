<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'slug',
        'wiki_category_id',
        'order',
        'is_published'
    ];

    /**
     * Get the category that owns the article.
     */
    public function category()
    {
        return $this->belongsTo(WikiCategory::class, 'wiki_category_id');
    }

    /**
     * Get related articles from the same category.
     */
    public function getRelatedArticlesAttribute()
    {
        if (!$this->wiki_category_id) {
            return WikiArticle::where('id', '!=', $this->id)
                ->where('is_published', true)
                ->take(5)
                ->get();
        }

        return WikiArticle::where('id', '!=', $this->id)
            ->where('wiki_category_id', $this->wiki_category_id)
            ->where('is_published', true)
            ->take(5)
            ->get();
    }
}
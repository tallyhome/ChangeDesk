<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiArticle extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'content',
        'slug',
        'wiki_category_id',
        'order',
        'is_published',
    ];

    public function category()
    {
        return $this->belongsTo(WikiCategory::class, 'wiki_category_id');
    }

    public function getRelatedArticlesAttribute()
    {
        if (! $this->wiki_category_id) {
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

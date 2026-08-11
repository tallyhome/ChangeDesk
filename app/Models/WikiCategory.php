<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiCategory extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'order',
    ];

    public function articles()
    {
        return $this->hasMany(WikiArticle::class, 'wiki_category_id');
    }

    public function publishedArticles()
    {
        return $this->hasMany(WikiArticle::class, 'wiki_category_id')
            ->where('is_published', true)
            ->orderBy('order');
    }
}

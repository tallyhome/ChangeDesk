<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'version_number',
        'release_date',
        'description',
        'content',
        'image_path',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];
}

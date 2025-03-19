<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Version extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'version_number',
        'release_date',
        'content'
    ];
    
    protected $casts = [
        'release_date' => 'datetime',
    ];
}

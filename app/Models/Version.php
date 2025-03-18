<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = ['version_number', 'release_date', 'content'];
    protected $dates = [
        'release_date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'release_date' => 'date'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TodoItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'status',
        'progress',
        'color',
        'expected_date',
    ];
    
    protected $casts = [
        'expected_date' => 'date',
        'estimated_completion_date' => 'date',
    ];
    
    // Accesseur pour s'assurer que estimated_completion_date est toujours un objet Carbon
    public function getEstimatedCompletionDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
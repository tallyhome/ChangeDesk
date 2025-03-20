<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BugReport extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'status',
        'progress',
        'color',
        'severity',
        'expected_fix_date',
        'reporter_name',
        'reporter_email',
    ];
    
    protected $casts = [
        'expected_fix_date' => 'date',
    ];
    
    // Accesseur pour s'assurer que expected_fix_date est toujours un objet Carbon
    public function getExpectedFixDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
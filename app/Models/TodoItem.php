<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TodoItem extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
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

    public function getEstimatedCompletionDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}

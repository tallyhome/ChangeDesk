<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BugReport extends Model
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
        'severity',
        'expected_fix_date',
        'reporter_name',
        'reporter_email',
    ];

    protected $casts = [
        'expected_fix_date' => 'date',
    ];

    public function getExpectedFixDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}

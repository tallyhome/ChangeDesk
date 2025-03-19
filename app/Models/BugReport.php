<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'reporter_name',
        'reporter_email',
        'status',
        'admin_notes'
    ];
}
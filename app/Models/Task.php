<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'title',
        'detail',
        'status',
        'project_id',
        'start_date',
    ];

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}

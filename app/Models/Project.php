<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'title',
        'detail',
        'slug',
        'progress',
        'start_date',
        'deadline_date',
        ];

    public function tasks():HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}

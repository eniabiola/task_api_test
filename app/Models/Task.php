<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['task_status_id', 'user_id', 'title', 'description', 'task_status_id', 'user_id', 'due_date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taskStatus(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }
}

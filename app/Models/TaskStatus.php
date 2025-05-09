<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $fillable = ['name', 'final_step', 'badge_colour'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

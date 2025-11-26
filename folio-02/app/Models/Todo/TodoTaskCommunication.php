<?php

namespace App\Models\Todo;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TodoTaskCommunication extends Model
{
    protected $fillable = [
        'task_id', 'user_id', 'message', 'read_by_user'
    ];

    public function task()
    {
        return $this->belongsTo(TodoTask::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

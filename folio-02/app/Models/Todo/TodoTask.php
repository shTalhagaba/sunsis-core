<?php

namespace App\Models\Todo;

use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class TodoTask extends Model
{
    use Filterable;

    protected $fillable = [
        'title', 'description', 'belongs_to', 'created_by', 'completed'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'completed' => 'boolean',
    ];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function belongsToUser()
    {
        return $this->belongsTo(User::class, 'belongs_to');
    }

    public function communications()
    {
        return $this->hasMany(TodoTaskCommunication::class, 'task_id');
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->where(function($q) use ($query, $userId){
            return $query->where('belongs_to', $userId)
                ->orWhere('created_by', $userId);
        });
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($task) {
            if(! app()->runningInConsole() )
            {
                $task->created_by = auth()->user()->id;
            }
        });
    }
}

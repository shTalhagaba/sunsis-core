<?php

namespace App\Models\Messager;

use App\Helpers\MessageStatus;
use App\Helpers\QueryMessages;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use MessageStatus, QueryMessages ,HasUuid;

    protected $fillable = [
        'from_id', 'to_id', 'content', 'state', 'root_id', 'subject'
    ];

    /**
     * Get the conversation Messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversation()
    {
        return $this->hasMany(Message::class, 'root_id');
    }

    /**
     * Get the parent of a message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function root()
    {
        return $this->belongsTo(Message::class, 'root_id', 'id');
    }

    /**
     * Create a new instance of message
     *
     * @param Message $message
     * @return static
     */
    public static function copyFrom(Message $message)
    {
        return new static([
            'content' => $message->content,
            'title' => $message->title,
            'from_id' => $message->from_id,
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at,
            'state' => $message->state,
            'subject' => $message->subject,
        ]);
    }

    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'from_id');
    }

    public function receiver()
    {
        return $this->belongsTo(\App\Models\User::class, 'to_id');
    }


}

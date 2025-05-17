<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFeedback extends Model
{
    protected $table = 'request_feedbacks';
    protected $fillable = [
        'request_id',
        'user_id',
        'feedback_type',
        'feedback',
        'status',
        'is_read',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Btx\Query\Model;

class Request extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'workspace_id',
        'from_date',
        'to_date',
    ];
    public function assigned()
    {
        return $this->hasMany(RequestAssigned::class, 'request_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(RequestLog::class, 'request_id', 'id');
    }

    public function feedbacks()
    {
        return $this->hasMany(RequestFeedback::class, 'request_id', 'id');
    }
}

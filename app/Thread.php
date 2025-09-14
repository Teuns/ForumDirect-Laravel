<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = [
        'title', 'slug', 'body', 'user_id', 'subforum_id', 'lastpost_date', 'lastpost_uid', 'published', 'closed', 'updated_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lastUser()
    {
        return $this->belongsTo(User::class, 'lastpost_uid');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('published', false);
    }
}

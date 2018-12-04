<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = [];
    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function addReply($reply)
    {
        return $this->replies()->create($reply );
    }
    
    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }
    
    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }
}

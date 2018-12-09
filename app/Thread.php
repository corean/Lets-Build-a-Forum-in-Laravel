<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
     use RecordsActivity;
    
    protected $guarded = [];
    protected $with = ['creator', 'channel'];
    
    protected static function boot()
    {
        parent::boot();
        //글로벌 스코프 확장
        static::addGlobalScope('replies_count', function (Builder $builder) {
            $builder->withCount('replies');
        });
        //포럼글 삭제시 댓글도 삭제
        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
    }
    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    
    public function replies()
    {
//        return $this->hasMany(Reply::class)->withCount('replies');
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
        return $this->replies()
                    ->create($reply);
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

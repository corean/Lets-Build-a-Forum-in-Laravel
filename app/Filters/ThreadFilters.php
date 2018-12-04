<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters {
    
    protected $filters = ['by', 'title', 'popular'];
    
    /**
     * @param string $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
    
    protected function title($title)
    {
        return $this->builder->where('title', 'like', '%'. $title . '%');
    }

    protected function popular()
    {
        $this->builder->getQuery()->order = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}

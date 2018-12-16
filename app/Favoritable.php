<?php
/**
 * Created by PhpStorm.
 * User: corean
 * Date: 2018-12-08
 * Time: 23:30
 */

namespace App;


trait Favoritable
{
    
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())
                                 ->count();
    }
    
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
    
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }
    
    public function favorite()
    {
        $attributes = [
            'user_id' => auth()->user()->id,
        ];
        
        if (!$this->favorites()
                  ->where($attributes)
                  ->exists()) {
            $this->favorites()
                 ->create($attributes);
        }
    }
    
    public function unFavorite()
    {
        $attributes = [
            'user_id' => auth()->user()->id,
        ];
    
        $this->favorites()->where($attributes)->delete();
    }
    
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}

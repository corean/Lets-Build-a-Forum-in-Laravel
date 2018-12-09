<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

/**
 * Class FavoritesController
 * @package App\Http\Controllers
 */
class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Reply $reply)
    {
//         Favorite::create([
//                               'user_id'        => auth()->user()->id,
//                               'favorited_id'   => $reply->id,
//                               'favorited_type' => get_class($reply)
//                           ]);
        $reply->favorite();
        return back();
    }
}
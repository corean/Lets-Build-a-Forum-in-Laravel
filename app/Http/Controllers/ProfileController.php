<?php
/**
 * Created by PhpStorm.
 * User: corean
 * Date: 2018-12-09
 * Time: 00:38
 */

namespace App\Http\Controllers;

use App\Thread;
use App\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'threads'     => $user->threads()
                                  ->paginate(5)
        ]);
    }
    
}

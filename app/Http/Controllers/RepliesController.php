<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    /**
     * ParticipateInForumTest constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($channel, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);
        $thread->addReply([
                              'body'    => request('body'),
                              'user_id' => auth()->user()->id
                          ]);
        return back()->with('flash', 'Your reply has been left.');
    }
    
}

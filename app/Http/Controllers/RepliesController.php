<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    /**
     * ParticipateInForumTest constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }
    
    public function index($channelId , Thread $thread)
    {
        return $thread->replies()->paginate(1);
    }
    /**
     * @param $channel
     * @param Thread $thread
     * @return Reply
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), [
                'body' => 'required']
        );
        $reply = $thread->addReply([
                'body'    => request('body'),
                'user_id' => auth()->user()->id]
        );
        if (request()->expectsJson()) {
            return $reply->load('owner');
        }
        return back()->with('flash', 'Your reply has been left.');
    }
    
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply
        );
        $reply->delete();
        if (request()->expectsJson()) {
            return response(['status' => 'deleted']);
        }
        return back();
    }
    
    public function update(Reply $reply)
    {
        //$this->validate(request(), ['body' => 'required']);
        
        $this->authorize('update', $reply
        );
        $reply->update(request(['body']));
    }
    
}

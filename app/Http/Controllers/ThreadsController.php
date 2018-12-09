<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use http\Env\Response;
use Illuminate\Http\Request;

/**
 * Class ThreadsController
 * @package App\Http\Controllers
 */
class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')
             ->except('index', 'show');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getTreads($channel, $filters);
        
        if (request()->wantsJson()) {
            return $threads;
        }
        
        return view('threads.index', compact('threads'));
    }
    
    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getTreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()
                         ->filter($filters);
        
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        $threads = $threads->get();
        return $threads;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'      => 'required',
            'body'       => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);
        $thread = Thread::create([
                                     'user_id'    => auth()->user()->id,
                                     'channel_id' => request('channel_id'),
                                     'title'      => request('title'),
                                     'body'       => request('body'),
                                 ]);
        return redirect($thread->path());
    }
    
    /**
     * Display the specified resource.
     *
     * @param $channelID
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelID, Thread $thread)
    {
//        return $thread->load('replies.favorites')->load('replies.owner');
//        return $thread->replies;
        return view('threads.show', [
            'thread'  => $thread,
            'replies' => $thread->replies()
                                ->paginate(5)
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param $channel
     * @param  \App\Thread $thread
     * @return Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread); // 인증
        $thread->delete();
        
        if (request()->wantsJson()) {
            return response([], 204);
        }
        return redirect('/threads');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     * @return void
     */
    public function edit(Thread $thread)
    {
    
    }
    
    /**
     * Updatge the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }
    
    
}

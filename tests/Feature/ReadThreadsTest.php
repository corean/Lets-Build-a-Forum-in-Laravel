<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function 사용자가_포럼글_목록을_읽을수_있는지()
    {
        $thread = create('App\Thread');
        $this->get('/threads')->assertSee($thread->title);
    }
    
    /** @test  */
    public function 사용자가_포럼글_하나를_읽을수_있는지()
    {
        $thread = create('App\Thread');
        $this->get($thread->path())->assertSee($thread->title);
    }
    
    /** @test */
    public function 사용자가_포럼글에_대한_댓글을_읽을수_있는지()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $this->get($thread->path())->assertSee($reply->body);
    }
    
    /** @test */
    public function 사용자가_태그로_필터리된_포럼글_보기()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');
        
        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }
    
    /** @test */
    public function 사용자가_다른_사용자명으로_검색()
    {
        $this->SignIn(create('App\User', ['name'=>'JohnDoe']));
       
        $threadByJohn = create('App\Thread', ['user_id' => auth()->user()->id]);
        $threadNotByJohn = create('App\Thread');
        
        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}

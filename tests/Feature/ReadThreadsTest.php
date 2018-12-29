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
        $this->get('/threads')
             ->assertSee($thread->title);
    }
    
    /** @test */
    public function 사용자가_포럼글_하나를_읽을수_있는지()
    {
        $thread = create('App\Thread');
        $this->get($thread->path())
             ->assertSee($thread->title);
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
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));
        
        $threadByJohn = create('App\Thread', ['user_id' => auth()->user()->id]);
        $threadNotByJohn = create('App\Thread');
        
        $this->get('/threads?by=JohnDoe')
             ->assertSee($threadByJohn->title)
             ->assertDontSee($threadNotByJohn->title);
    }
    
    /** @test */
    public function 사용자가_포럼글을_댓글순으로_필터링할_수_있는지()
    {
        $threadWithTwoReply = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReply->id], 2);
        
        $threadWithThreeReply = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReply->id], 3);
        
        $threadWithNoneReply = create('App\Thread');
        
        $response = $this->getJson('/threads?popular=1')->json();
        //dd($response);
        $this->assertEquals([3, 2, 0, 0], array_column($response, 'replies_count'));
    }
    
    /** @test */
    public function 사용자가_댓글이_없는_포럼글_필터()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);
        
        $response = $this->getJson('threads?unanswered=1')
                         ->json();
        $this->assertCount(1, $response);
        
    }
    
    /** @test */
    public function 사용자가_주어진_포럼글의_댓글을_요청할_수_있는지()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id], 2);
        
        $response = $this->getJson($thread->path() . '/replies')->json();

//        dd($response);
        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
    
    protected function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }
    
}

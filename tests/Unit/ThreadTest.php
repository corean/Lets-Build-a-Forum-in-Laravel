<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;
    
    protected function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }
    
    /** @test */
    function 포럼글이_path가_같은지()
    {
        $thread = create('App\Thread');
        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }
    
    /** @test */
    function 포럼_작성자가_user_모델인지()
    {
        $this->assertInstanceOf('App\User', $this->thread->creater);
    }
    
    /** @test */
    function 포럼의_댓글들을_가지고_있는지()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);
        
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }
    
    /** @test */
    function 포럼_댓글_등록되는지()
    {
        $this->thread->addReply([
                                    'body' => 'foobar', 'user_id' => 1
                                ]);
        $this->assertCount(1, $this->thread->replies);
    }
    
    /** @test */
    function 포럼글이_채널에_속하는지()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
}


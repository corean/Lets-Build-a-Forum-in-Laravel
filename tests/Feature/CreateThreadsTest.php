<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;
    
    /** @test */
    function 미인증된_사용자가_포럼글_작성_실패()
    {
        $this->withExceptionHandling();
        $this->post('/threads')->assertRedirect('/login');
        $this->get('/threads/create')->assertRedirect('/login');

//        $this->expectException('Illuminate\Auth\AuthenticationException');
//        $thread = make(Thread::class);
//        $this->post('/threads', $thread->toArray());
    }
    
    /** @test */
    function 인증된_사용자가_포럼글_작성()
    {
        $this->signIn();
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());
        $this->get($response->headers->get('location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
    
    /** @test */
    function 포럼글쓰기중_제목이_있는지()
    {
        $this->publishThread(['title'=>null])->assertSessionHasErrors('title');
    }
    
    /** @test */
    function 포럼글쓰기중_내용이_있는지()
    {
        $this->publishThread(['body'=>null])->assertSessionHasErrors('body');
    }
    
    /** @test */
    function 포럼글쓰기중_채널id가_있는지()
    {
        factory('App\Channel', 2)->create();
        $this->publishThread(['channel_id'=>null])->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id'=>999])->assertSessionHasErrors('channel_id');
    }
    
    /**
     * @param $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    function publishThread(array $overrides)
    {
        $this->withExceptionHandling()->signIn();
    
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
        
    }
}

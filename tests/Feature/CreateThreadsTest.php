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
        $this->SignIn();
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
        $this->get('/threads')->assertSee($thread->title)->assertSee($thread->body);
    }
}

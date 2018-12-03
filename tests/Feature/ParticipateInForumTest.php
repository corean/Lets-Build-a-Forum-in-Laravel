<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpKernel\Tests\Exception\UnauthorizedHttpExceptionTest;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;
    
    protected function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }
    
    
    /** @test */
    function 미인증된_사용자는_댓글_등록_실패()
    {
        // 인증된 사용자
        //$this->expectException(\Illuminate\Auth\AuthenticationException::class);
        // 포럼글에 댓글 남기기
        $this->withExceptionHandling()
            ->post($this->thread->path() . '/replies', [])
            ->assertRedirect('/login');
    }
    
    /** @test */
    function 인증된_사용자_포럼내_참여하는_방법()
    {
        // 인증된 사용자
        $this->be($user = factory(User::class)->create());
        $reply = make(Reply::class);
        
        // 포럼글에 댓글 남기기
        $this->post($this->thread->path() . '/replies', $reply->toArray());
        // 댓글은 포럼글에 보여주기
        $this->get($this->thread->path())->assertSee($reply->body);
    }
}

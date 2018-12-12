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
    
    /** @test */
    function 미인증된_사용자는_댓글_등록_실패()
    {
        $thread = create(Thread::class);
        // 인증된 사용자
        //$this->expectException(\Illuminate\Auth\AuthenticationException::class);
        // 포럼글에 댓글 남기기
        $this->withExceptionHandling()
             ->post($thread->path() . '/replies', [])
             ->assertRedirect('/login');
    }
    
    /** @test */
    function 인증된_사용자_포럼내_댓글_등록()
    {
        // 인증된 사용자
        $this->be($user = factory(User::class)->create());
        $reply = make(Reply::class);
        $thread = create(Thread::class);
        // 포럼글에 댓글 남기기
        $this->post($thread->path() . '/replies', $reply->toArray());
        // 댓글은 포럼글에 보여주기
        $this->get($thread->path())
             ->assertSee($reply->body);
    }
    
    /** @test */
    function 댓글_내용이_있는지()
    {
        $this->withExceptionHandling()
             ->signIn();
        $thread = create(Thread::class);
        
        $reply = make('App\Reply', ['body' => null]);
        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertSessionHasErrors('body');
    }
    
    /** @test */
    function 미인증_사용자는_댓글을_삭제할수_없음()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        
        $this->delete('/replies/' . $reply->id)
             ->assertRedirect('/login');
        
        $this->signIn()
             ->delete('/replies/' . $reply->id)
             ->assertStatus(403);
    }
    
    /** @test */
    function 인증된_사용자는_댓글을_삭제할수_있음()
    {
        $user = $this->signIn();
        $reply = create('App\Reply', [ 'user_id' => auth()->id()]);
        
        $this->delete('/replies/' . $reply->id)->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['body' => $reply->body]);
        
    }
}

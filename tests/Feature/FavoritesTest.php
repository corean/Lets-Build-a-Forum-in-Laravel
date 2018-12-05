<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function 손님이_댓글에_좋아요를_누를_수_있는지()
    {
        $this->withExceptionHandling()
             ->post('replies/1/favorites')
             ->assertRedirect('login');
    }
    
    /** @test */
    public function 로그인한_유저가_댓글에_좋아요를_누를_수_있는지()
    {
        //유저가 좋Î아요 버튼을 보낸다
        //데이타베이스에 저장
        
        $this->SignIn(create('App\User', ['name' => 'JohnDoe']));
        $reply = create('App\Reply');
        
        $this->post('replies/' . $reply->id . '/favorites');
        $this->assertCount(1, $reply->favorites);
        
    }
    
    /** @test */
    public function 로그인한_유저는_해당_댓글에_한번만_좋아요를_누를수_있다()
    {
        $this->SignIn(create('App\User', ['name' => 'JohnDoe']));
        $reply = create('App\Reply');
        
        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('이중등록 방지');
        }
        
        $this->assertCount(1, $reply->favorites);
        
    }
}

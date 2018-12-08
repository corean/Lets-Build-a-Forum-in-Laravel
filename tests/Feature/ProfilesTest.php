<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function 사용자는_프로파일을_가진다()
    {
        $user = create('App\User');
        $this->get("/profiles/" . $user->name)
             ->assertSee($user->name);
    }
    /** @test */
    public function 사용자가_작성한_포럼글을_모두_보여준다() {
        $user = create('App\User');
        $thread = create('App\Thread', ['user_id' => $user->id]);
    
        $this->get("/profiles/" . $user->name)
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    
    }
}

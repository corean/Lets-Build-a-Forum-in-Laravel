<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function 포럼글이_작성되었을때_Activity_저장()
    {
        $this->signIn();
        $thread = create('App\Thread');
        
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);
        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }
    
    /** @test */
    public function 댓글이_작성되었을때_Activity_저장()
    {
        $this->signIn();
        $reply = create('App\Reply');
        
        $this->assertEquals(2, Activity::count());
        
        
    }
    
    
}

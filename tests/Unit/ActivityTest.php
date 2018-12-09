<?php

namespace Tests\Feature;

use App\Activity;
use Carbon\Carbon;
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
        create('App\Reply');
        
        $this->assertEquals(2, Activity::count());
    }
    
    /** @test */
    public function 사용자의_Activity_feed_가져오기()
    {
        // 사용자는 포럼글을 가지고 있음
        // 사용자는 일주일전에 다른 포럼글을 쓴 적 있음
        $this->signIn();
        create('App\Thread', [ 'user_id' => auth()->id()], 2);
        auth()->user()->activities()->first()->update(['created_at' => Carbon::now()->subWeek()]);
        
        // 해당 activity 를 가져올 수 있음
        $feed = Activity::feed(auth()->user());
        // 적절한 형식으로 가져올 수 있음.
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
        
        
    }
    
    
    
}

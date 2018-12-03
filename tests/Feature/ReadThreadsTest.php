<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    protected function setUp()
    {
        parent::setUp();
    
        $this->thread = factory(Thread::class)->create();
    }
    
    /** @test */
    public function 사용자가_포럼글_목록을_읽을수_있는지()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }
    
    /** @test  */
    public function 사용자가_포럼글_하나를_읽을수_있는지()
    {
        $this->get($this->thread->path())->assertSee($this->thread->title);
    }
    
    /** @test */
    public function 사용자가_포럼글에_대대한_댓글을_읽을수_있는지()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $this->get($this->thread->path())->assertSee($reply->body);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function 채널이_포럼글을_포함하고_있는지()
    {
        $channel = create('App\Channel');
        $thread = create('App\Thread', ['channel_id' => $channel->id]);
        
        //$this->assertInstanceOf('App\Thread', $channel->threads);
        $this->assertTrue($channel->threads->contains($thread));
    }
}

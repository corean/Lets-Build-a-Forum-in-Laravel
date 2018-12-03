<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function 댓글_작성자가_user_모델인지() {
        $reply = create(Reply::class);
        $this->assertInstanceOf(User::class, $reply->owner);
    }
}

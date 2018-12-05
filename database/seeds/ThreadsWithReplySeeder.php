<?php

use Illuminate\Database\Seeder;

class ThreadsWithReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'corean',
            'email' => 'corean@corean.biz',
            'password' => bcrypt('cor6858'),
            'email_verified_at' => now()
                            ]);
        $threads = factory('App\Thread', 50)->create();
    
        $threads->each(function($thread) {
            factory('App\Reply', rand(1,15))->create(['thread_id' => $thread->id]);
        });
    }
}

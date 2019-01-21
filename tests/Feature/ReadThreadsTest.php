<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReadThreadsTest extends TestCase
{
    use DataBaseMigrations;

    public function setUp()
    {
    	parent::setUp();

    	$this->thread = factory('App\Thread')->create();
    }

    public function test_a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_a_single_thread()
    {
    	$this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    public function test_a_can_read_replies_that_are_associated_with_a_thread()
    {
    	// 如果有 Thread
    	// 并且该 Thread 有回复
    	$reply = factory('App\Reply')
    	    ->create(['thread_id' => $this->thread->id]);
    	// 那么当我们看 Thread 时
    	// 我们也要看到回复
    	$this->get($this->thread->path())
    	    ->assertSee($reply->body);
    }

}

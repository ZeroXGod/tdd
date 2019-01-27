<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class ReadThreadsTest extends TestCase
{
    use DataBaseMigrations;

    public function setUp()
    {
    	parent::setUp();

    	$this->thread = create('App\Thread');
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
    	$reply = create('App\Reply', ['thread_id' => $this->thread->id]);
    	// 那么当我们看 Thread 时
    	// 我们也要看到回复
    	$this->get($this->thread->path())
    	    ->assertSee($reply->body);
    }

    public function test_a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_a_auer_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'NoNo1']));

        $threadByNoNo1 = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByNoNo1 = create('App\Thread');

        $this->get('threads?by=NoNo1')
            ->assertSee($threadByNoNo1->title)
            ->assertDontSee($threadNotByNoNo1->title);
    }

    public function test_a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id'=>$threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id'=>$threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popularity=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }

}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
    	$this->signIn(); // 已登录用户

    	$thread = create('App\Thread');
    	$this->post('/threads', $thread->toArray());
        
    	$this->get($thread->path())
    	    ->assertSee($thread->title)
    	    ->assertSee($thread->body);
    }


    public function test_guests_may_not_create_threads()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
             ->assertRedirect('/login');
    }
}
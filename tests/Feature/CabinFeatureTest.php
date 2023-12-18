<?php

namespace Nocs\Cabin\Tests\Feature;

use Nocs\Cabin\Tests\TestCase;
use Nocs\Cabin\Tests\Models\Article;
use Nocs\Cabin\Tests\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class CabinFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_article_can_be_locked()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $article = Article::factory()->create();

        $this->be($userA);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_A');
        $key = 'article_'. $article->id;
        cabin()->lock($key);
        $this->assertFalse(cabin()->isLocked($key));
        $this->assertEquals(cabin()->lockedBy($key), 1);

        $this->be($userB);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_B');
        cabin()->refreshSessionID();
        $this->assertTrue(cabin()->isLocked($key));
        $this->assertEquals(cabin()->lockedBy($key), 1);

    }

    /** @test */
    public function locked_article_expires()
    {

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $article = Article::factory()->create();

        $this->be($userA);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_A');
        $key = 'article_'. $article->id;
        cabin()->lock($key);
        
        $this->be($userB);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_B');
        cabin()->refreshSessionID();
        $this->assertTrue(cabin()->isLocked($key));

        $this->travel(30)->minutes();

        $this->assertFalse(cabin()->isLocked($key));
        $this->assertFalse(cabin()->lockedBy($key));

    }

    /** @test */
    public function locked_article_article_can_be_pinged()
    {

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $article = Article::factory()->create();

        $this->be($userA);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_A');
        $key = 'article_'. $article->id;
        cabin()->lock($key);
        
        $this->be($userB);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_B');
        cabin()->refreshSessionID();
        $this->assertTrue(cabin()->isLocked($key));

        $this->travel(30)->minutes();

        $this->be($userA);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_A');
        cabin()->refreshSessionID();
        cabin()->ping($key);

        $this->be($userB);
        Session::shouldReceive('getId')
                ->byDefault()
                ->andReturn('session_user_B');
        cabin()->refreshSessionID();

        $this->assertTrue(cabin()->isLocked($key));

    }

}

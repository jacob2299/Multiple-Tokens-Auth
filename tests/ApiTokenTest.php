<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\Data\User;

class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_api_token()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $token = $user->createToken();

        $this->assertNotNull($token);
        $this->assertEquals($token, $user->tokens()->latest()->first()->api_token);
    }

    /** @test */
    public function it_can_remove_an_api_token()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $token = $user->createToken();

        $this->assertNotNull($user->tokens()->latest()->first());

        $user->removeToken($token);

        $this->assertNull($user->tokens()->latest()->first());
    }

    /** @test */
    public function it_can_reset_the_api_tokens_to_the_maximum_allowed_amount()
    {
        config(['multiple-tokens-auth.active_tokens' => 2]);
        /** @var User $user */
        $user = factory(User::class)->create();

        $token1 = $user->createToken();
        Carbon::setTestNow(now()->addDay());
        $token2 = $user->createToken();

        $this->assertEquals(2, $user->tokens()->count());
        Carbon::setTestNow(now()->addDays(2));
        $token3 = $user->createToken();

        $this->assertEquals(2, $user->tokens()->count());

        $this->assertEquals($token3, $user->tokens()->latest()->first()->api_token);
        $this->assertEquals($token2, $user->tokens()->latest()->get()[1]->api_token);
    }

    /** @test */
    public function it_doenst_reset_total_api_tokens_when_the_maximum_allowed_amount_is_set_to_null()
    {
        config(['multiple-tokens-auth.active_tokens' => null]);
        /** @var User $user */
        $user = factory(User::class)->create();

        $user->createToken();
        $user->createToken();
        $user->createToken();

        $this->assertEquals(3, $user->tokens()->count());
    }
}

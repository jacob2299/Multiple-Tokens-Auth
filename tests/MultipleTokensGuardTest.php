<?php

namespace Tests;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use MultipleTokenAuth\ApiToken;
use MultipleTokenAuth\MultipleTokensGuard;
use Tests\Data\User;
use Mockery as m;

class MultipleTokensGuardTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_get_the_user_from_the_multiple_tokens_guard()
    {
        $provider = m::mock(UserProvider::class);
        /** @var User $createdUser */
        $createdUser = factory(User::class)->create();
        $createdUser->createToken();
        $apiToken = new ApiToken();
        $apiToken->user = $createdUser;
        $provider->shouldReceive('retrieveByCredentials')->once()->with(['api_token' => 'foo'])->andReturn($apiToken);
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer foo']);

        $guard = new MultipleTokensGuard($provider, $request);

        $user = $guard->user();

        $this->assertEquals($createdUser, $user);
    }
}

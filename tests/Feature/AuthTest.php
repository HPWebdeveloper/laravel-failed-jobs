<?php

namespace HPWebdeveloper\LaravelFailedJobs\Tests\Feature;

use HPWebdeveloper\LaravelFailedJobs\Exceptions\ForbiddenException;
use HPWebdeveloper\LaravelFailedJobs\FailedJobs;
use HPWebdeveloper\LaravelFailedJobs\Http\Middleware\Authenticate;
use HPWebdeveloper\LaravelFailedJobs\Tests\IntegrationTest;

class AuthTest extends IntegrationTest
{
    public function test_authentication_callback_works()
    {
        $this->assertFalse(FailedJobs::check('taylor'));

        FailedJobs::auth(function ($request) {
            return $request === 'taylor';
        });

        $this->assertTrue(FailedJobs::check('taylor'));
        $this->assertFalse(FailedJobs::check('adam'));
        $this->assertFalse(FailedJobs::check(null));
    }

    public function test_authentication_middleware_can_pass()
    {
        FailedJobs::auth(function () {
            return true;
        });

        $middleware = new Authenticate;

        $response = $middleware->handle(
            new class {
            },
            function ($value) {
                return 'response';
            }
        );

        $this->assertSame('response', $response);
    }

    public function test_authentication_middleware_throws_on_failure()
    {
        $this->expectException(ForbiddenException::class);

        FailedJobs::auth(function () {
            return false;
        });

        $middleware = new Authenticate;

        $middleware->handle(
            new class {
            },
            function ($value) {
                return 'response';
            }
        );
    }
}

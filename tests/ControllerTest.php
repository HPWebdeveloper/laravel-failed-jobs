<?php

namespace HPWebdeveloper\LaravelFailedJobs\Tests;

use HPWebdeveloper\LaravelFailedJobs\FailedJobs;

abstract class ControllerTest extends IntegrationTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.key', 'base64:UTyp33UhGolgzCK5CJmT+hNHcA+dJyp3+oINtX+VoPI=');

        FailedJobs::auth(function () {
            return true;
        });
    }
}

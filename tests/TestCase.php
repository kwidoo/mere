<?php

namespace Kwidoo\Mere\Tests;

use Kwidoo\Mere\Contracts\BaseService;
use Kwidoo\Mere\Tests\Fixtures\FakeRepository;
use Kwidoo\Mere\Tests\Fixtures\FakeService;
use Orchestra\Testbench\TestCase as Orchestra;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            \Kwidoo\Mere\MereServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('mere.resources', []);

        $app->bind(BaseService::class, FakeService::class);
        $app->bind(RepositoryInterface::class, FakeRepository::class);
    }
}

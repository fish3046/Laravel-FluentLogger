<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Ytake\LaravelFluent\LogServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LogServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $filesystem = new Filesystem();
        $app['config']->set('fluent', $filesystem->getRequire(__DIR__ . '/config/fluent.php'));
        $app['config']->set('logging', $filesystem->getRequire(__DIR__ . '/config/logging.php'));
    }
}

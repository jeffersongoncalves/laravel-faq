<?php

namespace JeffersonGoncalves\Faq\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JeffersonGoncalves\Faq\FaqServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'JeffersonGoncalves\\Faq\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            FaqServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', $this->testing_connection());

        $configPath = __DIR__.'/../config/faq.php';
        if (file_exists($configPath)) {
            $app['config']->set('faq', require $configPath);
        }
    }

    /**
     * Defaults to an in-memory SQLite connection for local development; CI
     * (tests.yml) sets FAQ_TEST_DB_* to run the same suite against real
     * MySQL and PostgreSQL instances too. Deliberately not the plain DB_*
     * names: Orchestra Testbench itself sets DB_CONNECTION=testing by
     * convention, which would collide with (and always win over) a driver
     * value read from the same variable here.
     *
     * @return array<string, mixed>
     */
    protected function testing_connection(): array
    {
        $driver = env('FAQ_TEST_DB_DRIVER', 'sqlite');

        if ($driver === 'sqlite') {
            return ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''];
        }

        return [
            'driver' => $driver,
            'host' => env('FAQ_TEST_DB_HOST', '127.0.0.1'),
            'port' => env('FAQ_TEST_DB_PORT'),
            'database' => env('FAQ_TEST_DB_DATABASE', 'testing'),
            'username' => env('FAQ_TEST_DB_USERNAME', 'root'),
            'password' => env('FAQ_TEST_DB_PASSWORD', ''),
            'charset' => $driver === 'pgsql' ? 'utf8' : 'utf8mb4',
            'prefix' => '',
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $stubsPath = __DIR__.'/../database/migrations';
        $tempPath = sys_get_temp_dir().'/laravel-faq-migrations';

        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        foreach (glob($stubsPath.'/*.php.stub') as $stub) {
            $filename = basename(str_replace('.php.stub', '.php', $stub));
            $target = $tempPath.'/'.$filename;

            copy($stub, $target);
        }

        $this->loadMigrationsFrom($tempPath);
    }
}

<?php

namespace Bryse\Eloquent\Files;

use Illuminate\Support\ServiceProvider;

class FilesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the config publish path.
        $migrations_path = __DIR__ . '/../migrations/2017_02_03_222826_create_files_table.php';

        $this->publishes([
            $migrations_path => database_path('migrations/2017_02_03_222826_create_files_table.php')
        ], 'migrations');
    }
}

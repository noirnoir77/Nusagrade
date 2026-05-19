<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        if ($this->app->environment('production') && !$this->app->runningInConsole()) {
            $this->app->booted(fn () => $this->runPendingMigrations());
        }
    }

    private function runPendingMigrations(): void
    {
        try {
            $migrator = $this->app->make('migrator');
            if (!$migrator->repositoryExists()) {
                return;
            }
            $files = array_keys($migrator->getMigrationFiles(database_path('migrations')));
            $ran   = $migrator->getRepository()->getRan();
            if (!empty(array_diff($files, $ran))) {
                Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Throwable) {
            // Don't break requests for migration issues; check logs if site behaves unexpectedly
        }
    }
}

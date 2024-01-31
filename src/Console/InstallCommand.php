<?php

namespace HPWebdeveloper\LaravelFailedJobs\Console;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'failedjobs:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the FailedJobs resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing FailedJobs Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'failedjobs-provider']);

        $this->comment('Publishing FailedJobs Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'failedjobs-assets']);

        $this->comment('Publishing FailedJobs Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'failedjobs-config']);

        $this->registerFailedJobsServiceProvider();

        $this->info('FailedJobs scaffolding installed successfully.');
    }

    /**
     * Register the FailedJobs service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerFailedJobsServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        if (file_exists($this->laravel->bootstrapPath('providers.php'))) {
            // @phpstan-ignore-next-line
            ServiceProvider::addProviderToBootstrapFile("{$namespace}\\Providers\\FailedJobsServiceProvider");
        } else {
            $appConfig = file_get_contents(config_path('app.php'));

            if (Str::contains($appConfig, $namespace.'\\Providers\\FailedJobsServiceProvider::class')) {
                return;
            }

            file_put_contents(config_path('app.php'), str_replace(
                "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
                "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\FailedJobsServiceProvider::class,".PHP_EOL,
                $appConfig
            ));
        }

        file_put_contents(app_path('Providers/FailedJobsServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/FailedJobsServiceProvider.php'))
        ));
    }
}

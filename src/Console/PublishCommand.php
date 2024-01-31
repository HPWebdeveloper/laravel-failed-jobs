<?php

namespace HPWebdeveloper\LaravelFailedJobs\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'failedjobs:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the FailedJobs resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'failedjobs-assets',
            '--force' => true,
        ]);
    }
}

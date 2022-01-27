<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class AllCacheClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'all:cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Laravel caches';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startTime = microtime(true);

        Artisan::call('config:clear');
        $this->info('Done: config:clear');

        Artisan::call('route:clear');
        $this->info('Done: route:clear');

        Artisan::call('view:clear');
        $this->info('Done: view:clear');

        Artisan::call('clear-compiled');
        $this->info('Done: clear-compiled');

        // Artisan::call('optimize');
        // $this->info('Done: optimize');

        $this->info(sprintf('Time: %.2f sec', microtime(true) - $startTime));
    }
}

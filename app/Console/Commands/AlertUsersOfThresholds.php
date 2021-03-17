<?php

namespace App\Console\Commands;

use App\Jobs\AlertUsersOfThresholdsJob;
use App\Models\User;
use Illuminate\Console\Command;

class AlertUsersOfThresholds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'threshold:alert-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check thresholds and alert affected users';

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
     * @return int
     */
    public function handle()
    {
        User::whereHas('alertThresholds')->chunk(100, function ($users) {
            foreach ($users as $user) {
                AlertUsersOfThresholdsJob::dispatch($user);
            }
        });
        return 0;
    }
}

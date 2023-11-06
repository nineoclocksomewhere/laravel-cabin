<?php

namespace Nocs\Cabin\Commands;

use Nocs\ModelActivity\Models\ModelActivity;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CabinLockRemoveExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cabin:remove-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired locks';

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

        cabin()->removeExpired();

        $this->info('Expired locks have been removed');
        
    }
}

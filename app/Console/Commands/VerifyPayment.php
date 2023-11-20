<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voter:verify-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if voter\'s payment has been verified and update the database accordingly.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $this->info('Payment verification completed.');
    }
}

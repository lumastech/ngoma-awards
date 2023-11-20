<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $this->info('Verifying voter\'s payment...');

        $payments = \App\Models\VoterPayment::where('is_verified', false)->get();

            $MSISDN = '';
            $SESSION_ID = now()->timestamp;
            $externalID = now()->timestamp;
            $amount = 2.00;
            $currency = "ZMW";
            $token = env('MOMO_TOKEN');

        foreach ($payments as $payment) {
            // Perform actions for users with balances less than 50
            // For example, send notifications, update the balance, etc.
            // You can use $user->id, $user->name, etc. to access user attributes
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post('https://lipila-prod.hobbiton.app/transactions/status?transactionId=' . $payment->tnx_id, [
                'currency' => $currency,
                'amount' => $amount,
                'accountNumber' => $MSISDN,
                'fullName' => "Zipezemo Participant-{$MSISDN}",
                'phoneNumber' => $MSISDN,
                'email' => 'user@gmail.com',
                'externalId' => now()->timestamp,
                'narration' => 'Ngoma Awards Voting',
            ]);
        }

        $this->info('Payment verification completed.');
    }
}

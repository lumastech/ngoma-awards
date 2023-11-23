<?php

namespace App\Console\Commands;

use App\Models\Vote;
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

        $token = $token = 'LPLSECK-99587279c3ad4b7daa20265a9da28aae';

        foreach ($payments as $payment) {
            // Perform actions for users with balances less than 50
            // For example, send notifications, update the balance, etc.
            // You can use $user->id, $user->name, etc. to access user attributes
            $this->info('Payment Object: ' . $payment);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->get('https://lipila-prod.hobbiton.app/transactions/status?transactionId=' . $payment->txn_id);

            // Accessing the response body as an array
            $responseBody = $response->json();

            //$this->info('Verifying voter\'s payment...');

            $status = $responseBody['status'];

            $this->info('Payment Status: ' . $status);

            if ($status == 'Pending') {
                break;
            }

            if ($status == 'Failed' || $status == 'Cancelled' ) {
                $payment->is_verified = true;
                $payment->save();
            }

            if ($status == 'Successful') {

                $vote = Vote::create([
                    'name' => 'Voter',
                    'artists_id' => $payment->artist_id,
                ]);

                $payment->is_verified = true;
                $payment->save();

            }

        }

        $this->info('Payment verification completed.');
    }
}

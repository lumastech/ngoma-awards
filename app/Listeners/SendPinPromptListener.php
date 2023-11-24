<?php

namespace App\Listeners;

use App\Events\SendPinPromptEvent;
use App\Models\VoterPayment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SendPinPromptListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function generateUniqueString()
    {
        return Str::random(22) . now()->timestamp;
    }

    /**
     * Handle the event.
     */
    public function handle(SendPinPromptEvent $event): void
    {

        // Your logic here
        $data = $event->data;
        $amount = 2.00;
        $currency = "ZMW";
        $token = 'LPLSECK-99587279c3ad4b7daa20265a9da28aae'; // Replace with your actual token environment variable
        //$uniqueStr = Str::random(22) . now()->timestamp;

        sleep(2);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])->post('https://lipila-prod.hobbiton.app/transactions/mobile-money', [
                    'currency' => $currency,
                    'amount' => $amount,
                    'accountNumber' => $data['MSISDN'],
                    'fullName' => "Ngoma Awards-{$data['MSISDN']}",
                    'phoneNumber' => $data['MSISDN'],
                    'email' => 'user@gmail.com',
                    'externalId' => now()->timestamp,
                    'narration' => 'Ngoma Awards',
                ]);

                // Accessing the response body as an array
                $responseBody = $response->json();

                //dd($responseBody);

                //$responseBody['transactionId']

                if ($responseBody['status'] != 'Pending') {

                }

                $txn = $responseBody['transactionId'];

                //dd($txn);

                $vote = VoterPayment::create([
                    'txn_id' => $txn,
                    'artist_id' => $data['artist_id'],
                ]);


    }
}

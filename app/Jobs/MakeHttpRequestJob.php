<?php

namespace App\Jobs;

use App\Models\VoterPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class MakeHttpRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        sleep(3);

        $amount = 2.00;
        $currency = "ZMW";
        $token = 'LPLSECK-99587279c3ad4b7daa20265a9da28aae'; // Replace with your actual token environment variable

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post('https://lipila-prod.hobbiton.app/transactions/mobile-money', [
            'currency' => $currency,
            'amount' => $amount,
            'accountNumber' => $this->data['MSISDN'],
            'fullName' => "Ngoma Awards-" . $this->data['MSISDN'],
            'phoneNumber' => $this->data['MSISDN'],
            'email' => 'user@gmail.com',
            'externalId' => now()->timestamp,
            'narration' => 'Ngoma Awards',
        ]);

        // Accessing the response body as an array
        $responseBody = $response->json();

        //dd($responseBody);

        //$responseBody['transactionId']

        if ($responseBody['status'] == 'Pending') {

            $txn = $responseBody['transactionId'];

            //dd($txn);

            $vote = VoterPayment::create([
                'txn_id' => $txn,
                'artist_id' => $this->data['artist_id'],
            ]);
        }



    }
}

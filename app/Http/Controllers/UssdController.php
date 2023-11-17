<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UssdController extends Controller
{
    public function generateUniqueString()
    {
        return Str::random(22) . now()->timestamp;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {
            $MSISDN = $request->query('MSISDN');
            $SESSION_ID = $request->query('SESSION_ID', now()->timestamp);
            $externalID = now()->timestamp;
            $amount = 2.00;
            $currency = "ZMW";
            $token = env('YOUR_TOKEN_HERE'); // Replace with your actual token environment variable

            if ($request->query('RequestType') === "2") {

                sleep(1.5); // Delay for 1.5 seconds

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])->post('https://lipila-prod.hobbiton.app/transactions/mobile-money', [
                    'currency' => $currency,
                    'amount' => $amount,
                    'accountNumber' => $MSISDN,
                    'fullName' => "Zipezemo Participant-{$MSISDN}",
                    'phoneNumber' => $MSISDN,
                    'email' => 'user@gmail.com',
                    'externalId' => now()->timestamp,
                    'narration' => 'Zipezemo Games show',
                ]);

                $response_msg = 'Thank you for the confirmation you will soon receive a prompt shortly.';

                return response($response_msg, 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
            } else {
                $menu_options = [
                    '1' => 'Enter 1 to play Zipezemo Game show. \n',
                ];

                $response_msg = 'Welcome to millennium TV to participate on the following programs: \n';
                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$key}. {$value}\n";
                }

                return response($response_msg, 200)
                    ->header('Freeflow', 'FC')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
            }
        } catch (\Exception $e) {
            $response_msg = 'Sorry there was an issue on the server. Try again later.';
            return response($response_msg, 200)
                ->header('Freeflow', 'FB')
                ->header('charge', 'N')
                ->header('cpRefId', $this->generateUniqueString());
        }

        return response()->json([
            'message' => 'Hello, world!'
        ]);
    }

}
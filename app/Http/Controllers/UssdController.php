<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\UserJourney;
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
            $SUBSCRIBER_INPUT = $request->query('INPUT');
            $amount = 2.00;
            $currency = "ZMW";
            $token = env('MOMO_TOKEN'); // Replace with your actual token environment variable

            $userJourney = UserJourney::where('phone_number', $MSISDN)->first();

            if($userJourney == null){
                $userJourney = UserJourney::create([
                    'phone_number' => $MSISDN
                ]);
            }

            if ($userJourney->step == 1) {

                $awards = Award::all();

                $menu_options = [];

                foreach ($awards as $award) {
                    $menu_options[] = $award->id . '.' . ' ' . $award->name . ' ' . "\n";
                }

                $response_msg = 'Welcome to the Ngoma Awards. To participate in the awards, please select the award you want to vote for: ' . "\n";

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$value} \n";
                }

                $userJourney->update([
                    'step' => 2
                ]);


                return response($response_msg, 200)
                        ->header('Freeflow', 'FC')
                        ->header('charge', 'N')
                        ->header('cpRefId', $this->generateUniqueString());

            }

            if ($userJourney->step == 2) {

                $award = Award::find($userJourney->selected_award);

                $menu_options = [];

                foreach ($award->categories as $category) {
                    $menu_options[] = $category->id . '.' . ' ' . $category->name . ' ' . "\n";
                }

                $menu_options[] = '0. Cancel your progress';

                $response_msg = 'Please select the category you want to vote for: ' . "\n";

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$value} \n";
                }

                $userJourney->update([
                    'step' => 3,
                    'selected_award' => $SUBSCRIBER_INPUT,
                ]);

                return response($response_msg, 200)
                        ->header('Freeflow', 'FC')
                        ->header('charge', 'N')
                        ->header('cpRefId', $this->generateUniqueString());
            }

            if($userJourney->step == 3){

                    $award = Award::find($userJourney->selected_award);
                    $category = $award->categories()->where('id', $userJourney->selected_award_category)->first();

                    $menu_options = [];

                    foreach ($category->artists as $artist) {
                        $menu_options[] = $artist->id . '.' . ' ' . $artist->name . ' ' . "\n";
                    }

                    $response_msg = 'Please select the artist you want to vote for: ' . "\n";

                    foreach ($menu_options as $key => $value) {
                        $response_msg .= "{$value} \n";
                    }

                    $userJourney->update([
                        'step' => 4,
                        'selected_award' => $SUBSCRIBER_INPUT,
                    ]);

                    return response($response_msg, 200)
                            ->header('Freeflow', 'FB')
                            ->header('charge', 'N')
                            ->header('cpRefId', $this->generateUniqueString());

            }

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
            'message' => 'Sorry there was an issue on the server. Try again later.',
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Jobs\MakeHttpRequestJob;
use App\Models\Award;
use App\Models\AwardsCategory;
use App\Models\UserJourney;
use App\Models\VoterPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

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
            $RequestType = $request->query('RequestType');
            // $amount = 2.00;
            // $currency = "ZMW";
            // $token = 'LPLSECK-99587279c3ad4b7daa20265a9da28aae'; // Replace with your actual token environment variable

            $userJourney = UserJourney::where('phone_number', $MSISDN)->first();

            if ($RequestType == "1" && $userJourney != null) {
                UserJourney::where('phone_number', '=', $MSISDN)->delete();
            }

            $userJourney = UserJourney::where('phone_number', $MSISDN)->first();

            if ($userJourney == null) {
                $userJourney = UserJourney::create([
                    'phone_number' => $MSISDN,
                    'selected_artist' => $this->generateUniqueString(),
                ]);
            }

            $userJourney = UserJourney::find($userJourney->id);

            //dd($userJourney);

            if ($SUBSCRIBER_INPUT == '0' || $SUBSCRIBER_INPUT == 'b') {

                $menu_options = [];

                $response_msg = 'Thank you for visiting Ngoma Awards.';

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$key}. {$value}\n";
                }

                if ($userJourney != null) {
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                }

                // return response()->json([
                //     'message' => 'Thank you for visiting Ngoma Awards.',
                //     'status' => 201
                // ]);

                return response($response_msg, 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
            }

            if ($userJourney->step == 1) {

                $awards = Award::all();

                $menu_options = [];

                foreach ($awards as $award) {
                    $menu_options[] = $award->id . '.' . ' ' . $award->name;
                }

                $responsesfd_msg = 'We wish to sincerely apologise for the system failure that we have encountered on our Airtel and MTN voting platforms.

Kindly be advised that this failure will not in any way affect the votes. However, the Zamtel voting platform is still active, keep the votes coming.

Be further assured that the technical team is currently working to resolve the failure. Thank for your continued co-operation and support. ';


                $response_msg = 'Welcome to the Ngoma Awards, choose award: ' . "\n";

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$value} \n";
                }

                UserJourney::where('phone_number', '=', $MSISDN)->update([
                    'step' => 2,
                ]);

                // return response()->json([
                //     'message' => $response_msg,
                // ])->header('ussd-step', 2);

                return response($response_msg, 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
            }

            //dd($userJourney);

            if ($userJourney->step == 2) {

                $award = Award::find($SUBSCRIBER_INPUT);

                //dd($award->categories);

                if ($award == null) {
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    // return response()->json([
                    //     'message' => 'You selected an invalid award option',
                    //     'status' => 201
                    // ]);
                    return response('You selected an invalid award option', 200)
                        ->header('Freeflow', 'FB')
                        ->header('charge', 'N')
                        ->header('cpRefId', $this->generateUniqueString());
                }

                if (($award->categories)->isEmpty()) {
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    // return response()->json([
                    //     'message' => 'There are currently no categories in for this award',
                    //     'status' => 201
                    // ]);
                    return response('There are currently no categories in for this award', 200)
                        ->header('Freeflow', 'FB')
                        ->header('charge', 'N')
                        ->header('cpRefId', $userJourney->selected_artist);
                }

                $menu_options = [];

                $currentIndex = 1;
                foreach ($award->categories as $category) {
                    $menu_options[] = $currentIndex . '.' . ' ' . $category->name . ' ' . "\n";

                    $currentIndex++;
                }

                $menu_options[] = '0. Cancel your progress';

                $response_msg = 'Please choose the category you want to vote for: ' . "\n";

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$value}";
                }

                UserJourney::where('phone_number', '=', $MSISDN)->update([
                    'step' => 3,
                    'selected_award' => $SUBSCRIBER_INPUT,
                ]);

                // return response()->json([
                //     'message' => $response_msg,
                // ])->header('ussd-step', 3);

                return response($response_msg, 200)
                    ->header('Freeflow', 'FC')
                    ->header('charge', 'N')
                    ->header('cpRefId', $userJourney->selected_artist);
            }

            if ($userJourney->step == 3) {

                $award = Award::find($userJourney->selected_award);
                $category = $award->categories[(int) $SUBSCRIBER_INPUT - 1];

                if ($category == null) {
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    // return response()->json([
                    //     'message' => 'You selected an invalid category option',
                    //     'status' => 201
                    // ]);
                    return response('You selected an invalid category option', 200)
                        ->header('Freeflow', 'FB')
                        ->header('charge', 'N')
                        ->header('cpRefId', $userJourney->selected_artist);
                }

                if (($category->artists)->isEmpty()) {
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    // return response()->json([
                    //     'message' => 'There are currently no artists in for this category',
                    //     'status' => 201
                    // ]);
                    return response('There are currently no artists in for this category', 200)
                        ->header('Freeflow', 'FB')
                        ->header('charge', 'N')
                        ->header('cpRefId', $userJourney->selected_artist);
                }

                $menu_options = [];

                $currentIndex = 1;
                foreach ($category->artists as $artist) {
                    $menu_options[] = $currentIndex . '.' . ' ' . $artist->name . ' ' . "\n";

                    $currentIndex++;
                }

                $menu_options[] = '0. Cancel your progress';

                $response_msg = 'Please choose the artist you want to vote for: ' . "\n";

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$value}";
                }

                UserJourney::where('phone_number', '=', $MSISDN)->update([
                    'step' => 4,
                    'selected_award_category' => $SUBSCRIBER_INPUT,
                ]);

                // return response()->json([
                //     'message' => $response_msg,
                // ])->header('ussd-step', 4);

                return response($response_msg, 200)
                    ->header('Freeflow', 'FC')
                    ->header('charge', 'N')
                    ->header('cpRefId', $userJourney->selected_artist);
            }

            if ($userJourney->step == 4) {

                $award = Award::find($userJourney->selected_award);

                //$category = $award->categories[(int)$SUBSCRIBER_INPUT - 1];

                $category = AwardsCategory::where('award_id', '=', $award->id)->first();

                // $cate = (int)$userJourney->selected_award_category;
                $artistIndex = (int) $SUBSCRIBER_INPUT - 1;

                //$artist = AwardsCategory::find($category->id)->artists[$artistIndex];
                $artist = $category->artists[$artistIndex];

                //dd($artist);

                if ($artist == null) {
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    // return response()->json([
                    //     'message' => 'You selected an invalid artist option',
                    //     'status' => 201
                    // ]);
                    return response('You selected an invalid artist option', 200)
                        ->header('Freeflow', 'FB')
                        ->header('charge', 'N')
                        ->header('cpRefId', $userJourney->selected_artist);
                }


                $data = [
                    'MSISDN' => $MSISDN,
                    'artist_id' => $artist->id,
                ];

                $response_msg = 'Thank you for your vote, you will soon receive a prompt for a pin shortly.';

                UserJourney::where('phone_number', '=', $MSISDN)->delete();

                // Create a new Response instance
                $response = new Response($response_msg, 200);

                // Set additional headers if needed
                $response->header('Freeflow', 'FB');

                //SendPinPromptEvent::dispatch($data);
                //event(new \App\Events\SendPinPromptEvent($data));
                MakeHttpRequestJob::dispatch($data)->delay(now()->addSeconds(1)); // Delay is optional

                $response->send();

                return;
            }
        } catch (\Exception $e) {
            dd($e);
            //dd('Reached here');
            $response_msg = 'Sorry there was an issue. Try again later.';
            return response($response_msg, 200)
                ->header('Freeflow', 'FB')
                ->header('charge', 'N')
                ->header('cpRefId', $this->generateUniqueString());
        }

        // return response()->json([
        //     'message' => 'Sorry there was an issue on the server. Try again later.',
        // ]);
    }

    public function payment(Request $request)
    {
        $data = $request->all();

        $amount = 2.00;
        $currency = "ZMW";
        $token = 'LPLSECK-99587279c3ad4b7daa20265a9da28aae'; // Replace with your actual token environment variable
        //$uniqueStr = Str::random(22) . now()->timestamp;

        sleep(3);

        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $token,
        //     'Content-Type' => 'application/json',
        // ])->post('https://lipila-prod.hobbiton.app/transactions/mobile-money', [
        //     'currency' => $currency,
        //     'amount' => $amount,
        //     'accountNumber' => $data['MSISDN'],
        //     'fullName' => "Ngoma Awards-{$data['MSISDN']}",
        //     'phoneNumber' => $data['MSISDN'],
        //     'email' => 'user@gmail.com',
        //     'externalId' => now()->timestamp,
        //     'narration' => 'Ngoma Awards',
        // ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->put('https://ussd-payment.onrender.com/api/ussd', [
            'MSISDN' => $data['MSISDN'],
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
                'artist_id' => $data['artist_id'],
            ]);
        }

        //dd($requestData);
        return response()->json([
            'message' => "Payment request sent successfully",
        ]);
    }
}

/* Moved the payment Function to here */

                /*

                $amount = 2.00;
                $currency = "ZMW";
                $token = 'LPLSECK-99587279c3ad4b7daa20265a9da28aae'; // Replace with your actual token environment variable
                $uniqueStr = Str::random(22) . now()->timestamp;

                //MakeHttpRequestJob::dispatch($data)->delay(now()->addSeconds(1)); // Delay is optional

                $responseAPI = Http::withHeaders([
                    'Authorization' => 'Bearer LPLSECK-99587279c3ad4b7daa20265a9da28aae',
                    'Content-Type' => 'application/json',
                ])->post('https://lipila-prod.hobbiton.app/transactions/mobile-money', [
                    'currency' => $currency,
                    'amount' => $amount,
                    'accountNumber' => $MSISDN,
                    'fullName' => "Ngoma Awards-{$MSISDN}",
                    'phoneNumber' => $MSISDN,
                    'email' => 'user@gmail.com',
                    'externalId' => now()->timestamp,
                    'narration' => 'Ngoma Awards',
                ]);
                //dd($response);
                // $response = Http::withHeaders([
                //     'Content-Type' => 'application/json',
                // ])->get('https://ussd-payment.onrender.com/api/ussd?MSISDN=' . $data['MSISDN'] . '&artist_id=' . $data['artist_id']);

                // Accessing the response body as an array
                $responseBody = $responseAPI->json();

                //dd($responseBody);

                //$responseBody['transactionId']

                if ($responseBody['status'] == 'Pending') {
                    $txn = $responseBody['transactionId'];

                    //dd($txn);

                    $vote = VoterPayment::create([
                        'txn_id' => $txn,
                        'artist_id' => $artist->id,
                    ]);
                } */
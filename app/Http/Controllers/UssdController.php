<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Award;
use App\Models\AwardsCategory;
use App\Models\UserJourney;
use App\Models\VoterPayment;
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
            $token = 'LPLSECK-99587279c3ad4b7daa20265a9da28aae'; // Replace with your actual token environment variable

            $userJourney = UserJourney::where('phone_number', $MSISDN)->first();

            if ($userJourney == null) {
                $userJourney = UserJourney::create([
                    'phone_number' => $MSISDN,
                ]);
            }

            $userJourney = UserJourney::find($userJourney->id);

            //dd($userJourney);

            if ($SUBSCRIBER_INPUT == '0') {

                $menu_options = [];

                $response_msg = 'Thank you for visiting Ngoma Awards.';

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$key}. {$value}\n";
                }

                if ($userJourney != null) {
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                }

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

                $response_msg = 'Welcome to the Ngoma Awards, choose award: ' . "\n";

                foreach ($menu_options as $key => $value) {
                    $response_msg .= "{$value} \n";
                }

                UserJourney::where('phone_number', '=', $MSISDN)->update([
                    'step' => 2,
                ]);

                return response($response_msg, 200)
                    ->header('Freeflow', 'FC')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());

            }

            //dd($userJourney);

            if ($userJourney->step == 2) {

                $award = Award::find($SUBSCRIBER_INPUT);

                //dd($award->categories);

                if($award == null){
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    return response('You selected an invalid award option', 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
                }

                if(($award->categories)->isEmpty()){
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    return response('There are currently no categories in for this award', 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
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

                return response($response_msg, 200)
                    ->header('Freeflow', 'FC')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
            }

            if ($userJourney->step == 3) {

                $award = Award::find($userJourney->selected_award);
                $category = $award->categories[$SUBSCRIBER_INPUT - 1];

                if($category == null){
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    return response('You selected an invalid category option', 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
                }

                if(($category->artists)->isEmpty()){
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    return response('There are currently no artists in for this category', 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
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

                return response($response_msg, 200)
                    ->header('Freeflow', 'FC')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());

            }

            if ($userJourney->step == 4) {

                $award = Award::find($userJourney->selected_award);
                $category = $award->categories[$SUBSCRIBER_INPUT - 1];

                // $cate = (int)$userJourney->selected_award_category;
                $artistIndex = (int)$SUBSCRIBER_INPUT - 1;

                $artist = AwardsCategory::find($category->id)->artists[$artistIndex];

                //dd($artist);

                if($artist == null){
                    UserJourney::where('phone_number', '=', $MSISDN)->delete();
                    return response('You selected an invalid artist option', 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());
                }

                //dd($artist);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
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

                // Accessing the response body as an array
                $responseBody = $response->json();

                //dd($responseBody);

                //$responseBody['transactionId']

                if ($responseBody['status'] != 'Pending') {
                    $response_msg = 'Sorry there was an issue processing your payment. Try again later.';
                    return response($response_msg, 200)
                        ->header('Freeflow', 'FB')
                        ->header('charge', 'N')
                        ->header('cpRefId', $this->generateUniqueString());
                }

                $txn = $responseBody['transactionId'];

                //dd($txn);

                $vote = VoterPayment::create([
                    'txn_id' => $txn,
                    'artist_id' => $SUBSCRIBER_INPUT,
                ]);

                //dd($vote);

                $response_msg = 'Thank you for your vote, you will soon receive a prompt for a pin shortly.';

                UserJourney::where('phone_number', '=', $MSISDN)->delete();

                return response($response_msg, 200)
                    ->header('Freeflow', 'FB')
                    ->header('charge', 'N')
                    ->header('cpRefId', $this->generateUniqueString());

            }

        } catch (\Exception $e) {
            //dd($e);
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

}

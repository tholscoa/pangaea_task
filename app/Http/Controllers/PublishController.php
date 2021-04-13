<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublishController extends Controller
{
    public function publish($topic, Request $request)
    {
        $fetch_subscriptions = Subscription::whereTopic($topic)->get();
        $success_record = [];
        $failed_record = [];

        if ($fetch_subscriptions != null) {
            foreach ($fetch_subscriptions as $subscription) {
                $url = $subscription->endpoint;
                // return response(($url));
                $response = Http::post($url, [$request['data']]);
                // return response(['fail----> '. $response->successful()]);
                ($response->successful() == true) ? $success_record[] = $url : $failed_record[] = $url;
            }
            if (empty($failed_record)) {
                return response()->json("All notifications was sent successful", 200);
            }
            return response()->json([
                'message' => 'Some of the notification could not get a successful response',
                'failed report' => $failed_record,
            ], 206);
        } else {
            return response()->json("publish was not successful. HTTP endpoint not found", 401);
        }
    }
}

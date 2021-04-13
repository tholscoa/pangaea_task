<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe($topic, Request $request){

        $url = $request->input('url');
        $check_existing_subscription = Subscription::where('topic', $topic)->where('endpoint', $url)->first();
        if(empty($check_existing_subscription)){
            $new_subscription = new Subscription();
            $new_subscription->topic = $topic;
            $new_subscription->endpoint = $url;
            $created = $new_subscription->save();
            if($created){
                return response()->json(['url'=>$url, 'topic'=>$topic], 201);
            }
            return response()->json(['error'=>'Something went wrong!'], 500);
        }
        return response()->json(['message'=>'subscriber already exit'], 302);
    }

}

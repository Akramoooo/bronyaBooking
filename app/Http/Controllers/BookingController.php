<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;
use App\Models\Referral;
use App\Models\ReferralEarning;

class BookingController extends Controller
{
    public function attach(Request $request){

    $referredId = $request->header("X-Master-Id");
    
    if($referredId == null || Master::find($referredId) == null){
        return;
    }

    $validated = $request->validate([
        "code" => "required|string|max:255",
    ]);

    $owner = Master::where("referral_code", $validated["code"])->first();

    if(!$owner->exists()){
        return;
    }

    Referral::firstOrCreate([
        "referrer_master_id" => $owner->id,
        "referred_master_id" => $referredId,
        "status" => Referral::STATUS_PENDING,
    ]);

    return response()->json([
        'success' => true,
    ], 200);

}

    public function my(Request $request){
        $myId = $request->header("X-Master-Id");
    
        if($myId == null || Master::find($myId) == null){
            return;
        }

        $myReferrals = ReferralEarning::where('referred_master_id', $myId)->get();

        return response()->json([
            'success' => true,
            'referrals' => $myReferrals,
        ], 200);
    }  

    public function earnings(Request $request){
        $myId = $request->header("X-Master-Id");

        if($myId == null || Master::find($myId) == null){
            return;
        }

        $myReferrals = ReferralEarning::where('referred_master_id', $myId)->get();
        $rewardedReferrals = Referral::where('referred_master_id', $myId)->where('status', Referral::STATUS_REWARDED)->get();

        $data = [
            'total_amount' => $myReferrals->sum('amount'),
            'pending_amount' => $myReferrals->where('status', ReferralEarning::STATUS_PENDING)->sum('amount'),
            'paid_amount' => $myReferrals->where('status', ReferralEarning::STATUS_PAID)->sum('amount'),
            'rewarded' => $rewardedReferrals->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }
}

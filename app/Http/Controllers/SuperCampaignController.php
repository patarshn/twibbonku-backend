<?php

namespace App\Http\Controllers;

use App\Models\SuperCampaign;
use DateTime;
use Illuminate\Http\Request;

class SuperCampaignController extends BaseController
{
    public function addSuperCampaigns(Request $request){
        

        $PRICE_PER_DAY = 25000;
        
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        $start_date_obj = new DateTime($start_date);
        $end_date_obj = new DateTime($end_date);

        $interval = $start_date_obj->diff($end_date_obj);
        $total_date = $interval->days;


        $superCampaign = new SuperCampaign();
        $superCampaign->twibbon_id = $request->twibbon_id;
        $superCampaign->start_date = $request->start_date;
        $superCampaign->end_date = $request->end_date;
        $superCampaign->total_date = $total_date;
        $superCampaign->price_per_day = $PRICE_PER_DAY;
        $superCampaign->total_price = $total_date * $PRICE_PER_DAY;
        $superCampaign->subscription_code = rand(1,999);
        $superCampaign->status = "PROSES";
        $superCampaign->save();

        return $this->sendSuccessResponse($superCampaign, "Success add super campaign");
    }

    public function getSuperCampaigns(Request $request){

        $superCampaign = SuperCampaign::with(['twibbon'])->paginate();
        
        return $this->sendSuccessResponse($superCampaign, "Success get super campaign");
    }

    public function getSuperCampaignsById(Request $request, $id){

        $superCampaign = SuperCampaign::with(['twibbon'])->find($id);
        
        return $this->sendSuccessResponse($superCampaign, "Success get super campaign");
    }
}

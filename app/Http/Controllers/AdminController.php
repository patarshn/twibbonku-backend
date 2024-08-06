<?php

namespace App\Http\Controllers;

use App\Models\SuperCampaign;
use App\Models\Twibbon;
use App\Models\TwibbonComment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function getMainDashboard(Request $request){
        $registerContributors = User::where("role","CONTRIBUTOR")->count();
        $activeCampaigns = Twibbon::where(["status" => 1])->count();
        $deactiveCampaignsByContributor = Twibbon::where(["status" => 0, "deleted_role" => "CONTRIBUTOR"])->count();
        $deactiveCampaignsByAdmin = Twibbon::where(["status" => 0, "deleted_role" => "ADMIN"])->count();
        $superCampaigns = SuperCampaign::count();
        $commentCampaigns = TwibbonComment::count();
        $totalCampaigns = Twibbon::count();
        $avgCommentCampaigns = $commentCampaigns/$totalCampaigns;
        $totalUseCampaigns = 123;
        $totalViewCampaigns = 234;

        $data = [
            'register_contributors' => $registerContributors,
            'active_campaings' => $activeCampaigns,
            'deactive_campaings_by_contributor' => $deactiveCampaignsByContributor,
            'deactive_campaings_by_admin' => $deactiveCampaignsByAdmin,
            'super_campaigns' => $superCampaigns,
            'avg_comment_campaigns' => $avgCommentCampaigns,
            'total_use_campaigns' => $totalUseCampaigns,
            'total_view_campaigns' => $totalViewCampaigns,
        ];

        return $this->sendSuccessResponse($data, "Success get dashboard");
    }

    public function getContributors(Request $request){
        $contributors =  User::where("role","CONTRIBUTOR")->paginate();
        return $this->sendSuccessResponse($contributors, "Success get contributors");
    }

    public function getCampaigns(Request $request){

        $deleted_role = $request->deleted_role ?? null;

        $twibbon = Twibbon::with(['image']);

        if(!$deleted_role) $twibbon = $twibbon->where(['status' => 1]);
        if($deleted_role) $twibbon = $twibbon->where(['status' => 0, 'deleted_role' => $deleted_role]);
        
        $twibbon = $twibbon->paginate();

        return $this->sendSuccessResponse($twibbon, "Success get contributors");
    }

    public function getSuperCampaigns(Request $request){
        $superCampaigns = SuperCampaign::with(['twibbon'])->paginate();
        return $this->sendSuccessResponse($superCampaigns, "Success get contributors");
    }

    public function getCampaignsComment(Request $request){
        $superCampaigns = Twibbon::withCount(['comments'])->paginate();
        return $this->sendSuccessResponse($superCampaigns, "Success get contributors");
    }


}

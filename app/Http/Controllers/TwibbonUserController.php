<?php

namespace App\Http\Controllers;

use App\Models\Twibbon;
use App\Models\User;
use Illuminate\Http\Request;

class TwibbonUserController extends BaseController
{
    public function getCampaigns(Request $request)
    {   


        $twibbon = Twibbon::with(['tags','image']);


        $contributor_username = $request->string('contributor_username') ?? null;
        if($contributor_username) {
            $contributor = User::select('id')->where('username', $contributor_username)->withTrashed()->first();
            if (!$contributor) return $this->sendErrorInternalResponse(null, "Contributor Not Found", null);

            $twibbon = $twibbon->where('created_by', $contributor->id);
        }

        $twibbon = $twibbon->paginate();
        if(!$twibbon) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        return $this->sendSuccessResponse($twibbon, "Success get twibbon");
    }

    public function getCampaignsById(Request $request, $id)
    {   
        $twibbon = Twibbon::with(['tags','twibbon_images','image'])->find($id);
        if(!$twibbon) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        return $this->sendSuccessResponse($twibbon, "Success get twibbon");
    }
}

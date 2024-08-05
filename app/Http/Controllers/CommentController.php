<?php

namespace App\Http\Controllers;

use App\Models\TwibbonComment;
use Auth;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    public function createCampaignComment(Request $request)
    {   

        $twibbonComment = new TwibbonComment();

        $twibbonComment->content = $request->content;
        $twibbonComment->twibbon_id = $request->twibbon_id;
        $twibbonComment->parent_id = $request->parent_id ?? null;
        $twibbonComment->created_by = Auth::user()->id;

        if(!$twibbonComment->save()) return $this->sendErrorInternalResponse(null, "Fail save comment", null);

        return $this->sendSuccessResponse($twibbonComment, "Success add comment twibbon");
    }

    public function deleteCampaignComment(Request $request)
    {   

        $twibbonComment = TwibbonComment::find($request->id);
        if(!$twibbonComment) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        if(!$twibbonComment->delete()) return $this->sendErrorInternalResponse(null, "Fail delete data", null);

        return $this->sendSuccessResponse($twibbonComment, "Success get twibbon");
    }

    public function getCampaignComment(Request $request, $id)
    {   

        $twibbonComment = TwibbonComment::with(["replies"])->where(["twibbon_id" => $id, "parent_id" => null])->get();
        if(!$twibbonComment) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        return $this->sendSuccessResponse($twibbonComment, "Success get twibbon");
    }
}

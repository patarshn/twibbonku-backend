<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Twibbon;
use App\Models\TwibbonImage;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Storage;
use Validator;
use Illuminate\Support\Facades\DB;

class TwibbonContributorController extends BaseController
{
    public function createCampaign(Request $request)
    {   
        $twibbon = new Twibbon();
        $twibbon->created_by = Auth::user()->id;
        $twibbon->slug = uniqid();
        if(!$twibbon->save()) return $this->sendErrorInternalResponse(null, "Error save data", null);

        return $this->sendSuccessResponse($twibbon, "Success create twibbon");
    }

    public function getCampaigns(Request $request)
    {   

        $twibbon = Twibbon::with(['tags','image', 'keywords'])->paginate();
        if(!$twibbon) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        return $this->sendSuccessResponse($twibbon, "Success get twibbon");
    }
    public function getDeletedCampaigns(Request $request)
    {   

        $twibbon = Twibbon::with(['tags','image', 'keywords'])->withTrashed()->paginate();
        if(!$twibbon) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        return $this->sendSuccessResponse($twibbon, "Success get twibbon");
    }

    public function getCampaignsById(Request $request, $id)
    {   
        $twibbon = Twibbon::with(['tags','twibbon_images','image', 'keywords'])->withTrashed()->find($id);
        if(!$twibbon) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        return $this->sendSuccessResponse($twibbon, "Success get twibbon");
    }

    public function addCampaignTwibbon(Request $request)
    {   

        $validator = Validator::make($request->all(),[
            'twibbon_id' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if($validator->fails()) return $this->sendErrorBadParamsResponse($validator->errors(), "Fail get data", null);
        
        $twibbon = Twibbon::find($request->twibbon_id);
        if(!$twibbon) return $this->sendErrorInternalResponse(null, "Fail get data", null);

        $file = $request->file('file');

        $imageName = time().'.'.$file->extension();

        // Store the image in the public storage
        $path = $file->storeAs('twibbon_images', $imageName, 'public');

        // Save file information in the database
        $twibbonImage = new TwibbonImage();
        $twibbonImage->twibbon_id = $request->twibbon_id;
        $twibbonImage->image_url = $path;
        $twibbonImage->created_by = Auth::user()->id;
        $twibbonImage->save();
        
        if (count($twibbon->twibbon_images) == 1){
            $twibbon->image_id = $twibbonImage->id;
            $twibbon->save();
        }

        return $this->sendCreatedResponse($twibbonImage, "Success add image");

    }

    public function removeCampaignTwibbon(Request $request)
    {   

        $validator = Validator::make($request->all(),[
            'twibbon_image_id' => 'required',
        ]);

        if($validator->fails()) return $this->sendErrorBadParamsResponse($validator->errors(), "Fail validate data", null);
        
        $twibbonImage = TwibbonImage::find($request->twibbon_image_id);
        if (!$twibbonImage) return $this->sendErrorBadParamsResponse(null, "Data not found", null);
        if ($twibbonImage->created_by != Auth::user()->id) return $this->sendErrorBadParamsResponse(null, "Unauthorized", null);
        

        if (Storage::disk('public')->exists($twibbonImage->image_url)) {
            Storage::disk('public')->delete($twibbonImage->image_url);
        }

        if(!$twibbonImage->delete()) return $this->sendErrorBadParamsResponse(null, "Fail delete data", null);

        return $this->sendSuccessResponse($twibbonImage, "Success delete image");

    }

    public function deleteCampaign(Request $request)
    {   
        $twibbon = Twibbon::find($request->id);
        if(!$twibbon) return $this->sendErrorBadParamsResponse(null, "Fail get data", null);

        $twibbon->deleted_at = now();
        $twibbon->deleted_by = Auth::user()->id;
        $twibbon->deleted_role = Auth::user()->role;

        if(!$twibbon->save()) return $this->sendErrorInternalResponse(null, "Fail delete data", null);

        return $this->sendSuccessResponse($twibbon, "Success get twibbon");
    }

    public function publishCampaign(Request $request)
    {   
        $twibbon = Twibbon::find($request->id);
        if(!$twibbon) return $this->sendErrorBadParamsResponse(null, "Fail get data", null);

        if($twibbon->status == 1) return $this->sendSuccessResponse($twibbon, "Success publish twibbon");

        $twibbon->status = 1;

        if(!$twibbon->save()) return $this->sendErrorInternalResponse(null, "Fail update twibbon", null);
       
        return $this->sendSuccessResponse($twibbon, "Success publish twibbon");
    }

    public function updateCampaign(Request $request)
    {   
        $twibbon = Twibbon::find($request->id);
        if(!$twibbon) return $this->sendErrorBadParamsResponse(null, "Fail get data", null);
        
        
        $collectionTags = collect($request->tags);
        $tagsUnique = $collectionTags->unique();

        $collectionKeywords = collect($request->keywords);
        $keywordsUnique = $collectionKeywords->unique();

        $twibbon->title = $request->title;
        $twibbon->description = $request->description;
        // $twibbon->slug = $request->slug;
        // $twibbon->keyword = $request->keyword;
        $twibbon->twibbon_visibility_status = $request->twibbon_visibility_status;
        $twibbon->commentar_visibility_status = $request->commentar_visibility_status;
        $twibbon->viewer_visibility_status = $request->viewer_visibility_status;
        $twibbon->status = $request->status;
        
        try{
            DB::transaction(function () use ($tagsUnique, $keywordsUnique, $twibbon) {
                $tagIds = [];
                foreach($tagsUnique as $t){
                    $tag = Tag::firstOrCreate(['name' => $t]);
                    $tagIds[] = $tag->id;
                }

                $keywordIds = [];
                foreach($keywordsUnique as $t){
                    $keyword = Tag::firstOrCreate(['name' => $t]);
                    $keywordIds[] = $keyword->id;
                }

                $twibbon->tags()->sync($tagIds);
                $twibbon->keywords()->sync($keywordIds);
                $twibbon->save();
    
            }, 1);

            return $this->sendSuccessResponse($twibbon, "Success publish twibbon");
        }catch (Exception $e) {
            return $this->sendErrorInternalResponse(null, "Fail update twibbon", null);
        }
    
    }


}

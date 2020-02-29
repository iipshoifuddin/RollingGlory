<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ratings;
use App\Redeem;

class DetailGiftsController extends Controller
{
    //
    public function addRating(Request $request, $id)
    {
        if($request->rating<1 || $request->rating>5)
        {
            return $respone=array("Status"=>400, "error"=>true, "Message"=>"Rating Between 1-5");    
        }
        if(Ratings::where('id_gift', $id)->count()>0)
        {
            return $respone=array("Status"=>400, "error"=>true, "Message"=>"the Rating Button has Been Pressed !");    
        }
        $rate = new Ratings;
        $rate->id_rating = null;
        $rate->id_user = $request->id_user;
        $rate->id_gift = $id;
        $rate->rating  = $request->rating;
        $rate->save();
        $respone=array("Status"=>201, "error"=>false, "Message"=>"Input Successfully !");
     	return $respone;
    }

    public function addRedeem(Request $request, $id)
    {
        if(Redeem::where('id_gift', $id)->where('id_user', $request->id_user)->count()>0)
        {
            return $respone=array("Status"=>400, "error"=>true, "Message"=>"The Item has Been Redeem !");    
        }
        $redeem = new Redeem;
        $redeem->id_redeem = null;
        $redeem->id_user = $request->id_user;
        $redeem->id_gift = $id;
        $redeem->redeemed  = 1;
        $redeem->save();
        $respone=array("Status"=>201, "error"=>false, "Message"=>"Input Successfully !");
     	return $respone;
    }
    
    public function updateRedeem(Request $request, $id)
    {
        $redeem = Redeem::where('id_redeem', $id);
        if(Redeem::where('id_redeem', $id)->where('redeemed', 0)->count()>0)
        {
            $redeem->update(['redeemed' => 1]);
            return $respone=array("Status"=>200, "error"=>true, "Message"=>"The Item has been Redeemed !");    
        }
        $redeem->update(['redeemed' => 0]);
        $respone=array("Status"=>201, "error"=>false, "Message"=>"Input Successfully unRedeem !");
     	return $respone;
    }

    public function deleteRedeem($id)
    {
        if(Redeem::where('id_redeem', $id)->count()<1)
        {
            return abort(404);
        }
        $redeem = Redeem::where('id_redeem', $id);
        $redeem->delete(); 
        return $respone=array("Status"=>200, "error"=>true, "Message"=>"The Item has been Deleted !");    
    }
}

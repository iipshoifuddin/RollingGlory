<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gift;
use App\Ratings;

class ControllerGift extends Controller
{
    //
    public function index(Request $request)
    {
        $orderBy = $request->query('orderBy');
        $search = $request->query('search');
        $stock = $request->query('stock');
        $maxData = $request->query('maxData');
        $orderBy = $orderBy!==null ? $orderBy : 'DESC';
        $search = $search!==null ? $search : '';
        $stock = $stock!==null ? $stock : 25;
        $maxData = $maxData!==null ? $maxData : 25;

        $gifts = Gift::select('id_gift','product_name AS product_name', 'stock AS stock','image',
            'conditions','reviews','tb_gifts.create_at','tb_gifts.update_at')
        ->join('tb_product', 'tb_gifts.id_product', '=', 'tb_product.id_product')
        ->orderBy('tb_gifts.update_at', $orderBy)
        // ->orderBy('ratings', $orderBy)
        ->where('product_name', 'like', "%".$search."%")
        ->where('stock', '<', $stock)
        ->paginate($maxData);
        foreach ($gifts as $key => $value) {
            $gifts[$key]=array(
                "id_gift" => $value->id_gift,
                "product_name" => $value->product_name,
                "stock" => $value->stock,
                "image" => $value->image,
                "conditions" =>$value->conditions,
                "reviews" =>$value->reviews,
                "ratings"=>$this->ratings($value->id_gift),
                "create_at" =>$value->create_at,
                "update_at" =>$value->update_at,
            );
        }
        $respone=array("Status"=>200, "error"=>false, "result"=>$gifts);
    	return $respone;
    }
    public function ratings($value)
    {
        $rate = Ratings::where('id_gift', '=', $value);
        if($rate->count()<=0)
        {
            return 0;
        }
        $sum = $rate->sum('rating');
        $count = $rate->count();
        return number_format($sum/$count,1);
    }

    public function detailGift($id)
    {  
        $gifts = Gift::select('id_gift','product_name AS product_name','product_info',
            'stock AS stock','image','description', 'conditions','reviews',
            'tb_gifts.create_at','tb_gifts.update_at')
        ->join('tb_product', 'tb_gifts.id_product', '=', 'tb_product.id_product')
        ->where('tb_gifts.id_gift', $id)
        ->get();
        if($gifts->count()<1)
        {
            return abort(404);
        }
        
        foreach ($gifts as $key => $value) {
            $gifts[$key]=array(
                "id_gift" => $value->id_gift,
                "product_name" => $value->product_name,
                "stock" => $value->stock,
                "image" => $value->image,
                "conditions" =>$value->conditions,
                "product_info" =>$value->product_info,
                "description" =>$value->description,
                "reviews" =>$value->reviews,
                "ratings"=>$this->ratings($value->id_gift),
                "create_at" =>$value->create_at,
                "update_at" =>$value->update_at,
            );
        }

        $respone=array("Status"=>200, "error"=>false, "result"=>$gifts);
    	return $respone;
    }
    public function storeGift(Request $request)
    {	
        $gift = new Gift;
        $gift->id_gift = null;
        $gift->id_product = $request->id_product;
        $gift->points = $request->points;
        $gift->reviews = $request->reviews;
        $gift->conditions = $request->conditions;
        $gift->stock = $request->stock;
        $gift->save();
        $respone=array("Status"=>201, "error"=>false, "Message"=>"Input Successfully !");
     	return $respone;
    }
}

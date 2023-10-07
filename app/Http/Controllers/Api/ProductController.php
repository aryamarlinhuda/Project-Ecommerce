<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Detail_Order;
use App\Models\Image;
use App\Models\Product;
use App\Models\Review;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list() {
        $data = Product::all();

        foreach($data as $x => $item) {
            $image = Image::where('product_id',$item->id)->first();
            if($image) {
                $data[$x]->photo = url('storage/'.$image->image);
            } else {
                $data[$x]->photo = null;
            }

            if($item->category_id) {
                $data[$x]->category = $item->category->name;
            } else {
                $data[$x]->category = null;
            }

            $reviews = Review::where('product_id',$item->id)->first();
            if(!$reviews) {
                $data[$x]->rating = null;
            } else {
                $average_rating = Review::where('product_id',$item->id)->average('rating');
                $getRating = substr($average_rating, 0, 3);
                $formattedRating = str_replace('.', ',', $getRating);
                $data[$x]->rating = $formattedRating;
            }
        }

        return response()->json([
            "status" => 200,
            "data" => $data],
            200
        );
    }

    public function detail($id) {   
        $data = Product::find($id);
        if (!$data) {
            return response()->json([
                "status" => 404,
                "message" => "Product Not Found!"],
                404
            );
        }

        $images = Image::where('product_id',$data->id)->get();
        if($images) {
            foreach ($images as $x => $image) {
                $images[$x]->photo_url = url('storage/'.$image->image);
            }
            $data->photo_url = $images;
        } else {
            $data->photo_url = null;
        }

        if($data->category_id) {
            $data->category = $data->category->name;
        } else {
            $data->category = null;
        }

        if($data->price) {
            $data->price = 'Rp ' . number_format($data->price, 2, ',', '.');
        } else {
            $data->price = 'Rp 0';
        }

        return response()->json([
            "status" => 200,
            "data" => $data],
            200
        );
    }

    public function list_category() {
        $data = Category::orderBy('name','asc')->get();

        return response()->json([
            "status" => 200,
            "data" => $data
        ], 200);
    }

    public function filter_by_category($id) {
        $data = product::where('category_id',$id)->get();

        foreach($data as $x => $item) {
            $image = Image::where('product_id',$item->id)->first();
            if($image) {
                $data[$x]->photo = url('storage/'.$image->image);
            } else {
                $data[$x]->photo = null;
            }

            if($item->category_id) {
                $data[$x]->category = $item->category->name;
            } else {
                $data[$x]->category = null;
            }

            $reviews = Review::where('product_id',$item->id)->first();
            if(!$reviews) {
                $data[$x]->rating = null;
            } else {
                $average_rating = Review::where('product_id',$item->id)->average('rating');
                $getRating = substr($average_rating, 0, 3);
                $formattedRating = str_replace('.', ',', $getRating);
                $data[$x]->rating = $formattedRating;
            }
        }

        return response()->json([
            "status" => 200,
            "data" => $data],
            200
        );
    }
}
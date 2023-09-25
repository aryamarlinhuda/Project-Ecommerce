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

            if($item->budget === 0) {
                $data[$x]->price = "Rp 0";
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
        $data = Product::findOrFail($id);

        $images = Image::where('product_id',$data->id)->get();
        if($images) {
            foreach ($images as $x => $image) {
                $images[$x]->photo = url('storage/'.$image->image);
            }
        } else {
            $images->photo = null;
        }

        if($data->category_id) {
            $data->category = $data->category_name->category;
        } else {
            $data->category = null;
        }

        $data->price = 'Rp ' . number_format($data->budget, 2, ',', '.');

        $reviews = Review::where('product_id',$data->id)->first();
        if(!$reviews) {
            $data->rating = null;
        } else {
            $average_rating = Review::where('product_id',$data->id)->average('rating');
            $getRating = substr($average_rating, 0, 3);
            $formattedRating = str_replace('.', ',', $getRating);
            $data->rating = $formattedRating;
        }

        $data->ordered = Detail_Order::where('product_id',$data->id)->count();

        $review = Review::where('product_id',$id)->get();
        foreach($review as $x => $item) {
            $review[$x]->username = $item->maker_name->name;

            $updated_at = $item->updated_at;

            if(now()->diffInSeconds($updated_at) === 0) {
                $review[$x]->last_made = "now";
            } else if(now()->diffInSeconds($updated_at) < 60) {
                $review[$x]->last_made = now()->diffInSeconds($updated_at) . " seconds ago";
            } else if(now()->diffInSeconds($updated_at) < 3600) {
                $review[$x]->last_made = now()->diffInMinutes($updated_at) . " minutes ago";
            } else if(now()->diffInSeconds($updated_at) < 86400) {
                $review[$x]->last_made = now()->diffInHours($updated_at) . " hours ago";
            } else if(now()->diffInSeconds($updated_at) < 172800) {
                $review[$x]->last_made = "yesterday";
            } else if(now()->diffInSeconds($updated_at) >= 172800) {
                $dateTime = new DateTime($updated_at);
                $formated_date = $dateTime->format('H:i d-M-Y');
                $date = Carbon::createFromFormat('H:i d-M-Y', $formated_date);
                $review[$x]->last_made = $date->format('H.i l, d F Y');
            }
        }

        return response()->json([
            "status" => 200,
            "data" => $data,
            "review" => $review],
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

            if($item->budget) {
                $data[$x]->price = 'Rp ' . number_format($item->budget, 2, ',', '.');
            } else {
                $data[$x]->price = "Rp 0";
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
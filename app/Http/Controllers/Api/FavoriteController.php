<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    public function list() {
        $user = auth()->id();

        $favorites = Favorite::where('favorite_by',$user)->get();
        foreach ($favorites as $x => $favorite) {
            $favorites[$x]->product = Product::where('id',$favorite->product_id)->first();
        }

        return response()->json([
            "status" => 200,
            "data" => $favorites],
            200
        );
    }

    public function favorite(Request $request) {
        $user = auth()->id();

        try {
            $request->validate([
                "product_id" => "required"
            ],[
                "product_id.required" => "Product ID is required!"
            ]);

            $data = Favorite::where('favorite_by',$user)->where('product_id',$request->input('product_id'))->first();
            if($data) {
                $data->delete();

                return response()->json([
                    "status" => 200,
                    "message" => "product removed to favorites",
                    "favorite" => false
                ], 200);
            } else {
                Favorite::create([
                    "product_id" => $request->input('product_id'),
                    "favorite_by" => $user
                ]);

                return response()->json([
                    "status" => 200,
                    "message" => "Product added to favorites",
                    "favorite" => true
                ], 200);
            }
        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }
    }

    public function delete(Request $request) {
        $user = auth()->id();

        try {
            $request->validate([
                "product_id" => "required"
            ],[
                "product_id.required" => "Product ID is required!"
            ]);

            $data = Favorite::where('product_id',$request->input('product_id'))->where('favorite_by',$user)->first();
            if($data) {
                $data->delete();

                return response()->json([
                    "status" => 200,
                    "message" => "product removed to favorites",
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Data not found!",
                ], 404);
            }

        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }

        
    }
}

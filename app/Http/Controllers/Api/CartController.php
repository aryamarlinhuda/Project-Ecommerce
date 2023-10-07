<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function list() {
        $user = auth()->id();

        $carts = Cart::where('carted_by',$user)->get();
        foreach ($carts as $x => $cart) {
            $carts[$x]->product = Product::where('id',$cart->product_id)->first();

            $carts[$x]->total_price = $cart->product->price * $cart->quantity;
        }

        return response()->json([
            "status" => 200,
            "data" => $carts],
            200
        );
    }

    public function add(Request $request) {
        $user = auth()->id();

        try {
            $request->validate([
                "product_id" => "required",
                "quantity" => "required | numeric"
            ],[
                "product_id.required" => "Product ID is required!",
                "quantity.required" => "Quantity product is required!",
                "quantity.numeric" => "Quantity product must be a number!"
            ]);

            $product = Product::find($request->input('product_id'));
            if (!$product) {
                return response()->json([
                    "status" => 404,
                    "message" => "Product Not Found!"],
                    404
                );
            }

            $cart = Cart::where('product_id',$request->input('product_id'))->where('carted_by',$user)->first();
            if($cart) {
                $cart->quantity = $cart->quantity + $request->input('quantity');
                $cart->save();

                return response()->json([
                    "status" => 200,
                    "message" => "Product has been successfully added to cart"],
                    200
                );
            } else {
                Cart::create([
                    "product_id" => $request->input('product_id'),
                    "quantity" => $request->input('quantity'),
                    "carted_by" => $user
                ]);

                return response()->json([
                    "status" => 200,
                    "message" => "Product has been successfully added to cart"],
                    200
                );
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }
    }

    public function edit(Request $request) {
        $user = auth()->id();

        try {
            $request->validate([
                "cart_id" => "required",
                "quantity" => "required | numeric"
            ],[
                "cart_id.required" => "Cart ID is required!",
                "quantity.required" => "Quantity Product is required!",
                "quantity.numeric" => "Quantity Product must be a number!"
            ]);

            $cart = Cart::where('id',$request->input('cart_id'))->where('carted_by',$user)->first();
            if(!$cart) {
                return response()->json([
                    "status" => 404,
                    "message" => "cart not found"],
                    404
                );
            }

            if($request->input('quantity') == 0) {
                $cart->delete();
                return response()->json([
                    "status" => 200,
                    "message" => "Cart Deleted"],
                    200
                );
            } else {
                Cart::where('id',$request->input('cart_id'))->update([
                    "quantity" => $request->input('quantity')
                ]);
            }

            $carts = Cart::find($request->input('cart_id'));
            $product = Product::where('id',$carts->product_id)->first();
            $carts->product = $product;
            $carts->price = $product->price * $request->input('quantity');

            return response()->json([
                "status" => 200,
                "message" => "Cart has been successfully edited",
                "data" => $carts
                ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }
    }

    public function remove(Request $request) {
        $user = auth()->id();

        try {
            $request->validate([
                "cart_id" => "required",
            ],[
                "cart_id.required" => "Product ID is required!",
            ]);

            $cart = Cart::where('id',$request->input('cart_id'))->where('carted_by',$user)->first();
            if($cart) {
                $cart->delete();
                return response()->json([
                    "status" => 200,
                    "message" => "Cart has been successfully removed from cart"],
                    200
                );
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Cart not found"],
                    404
                );
            }
        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }
    }
}

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
        $carts->overall_price += $carts->total_price;

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

            $cart = Cart::where('product_id',$request->input('product_id'))->where('carted_by',$user)->first();
            if($cart) {
                $cart->quantity = $cart->quantity + $request->input('quantity');
                $cart->save();
            } else {
                Cart::create([
                    "product_id" => $request->input('product_id'),
                    "quantity" => $request->input('quantity')
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

            if($request->input('quantity') === 0) {
                $cart->delete();
            } else {
                Cart::where('id',$request->input('cart_id'))->update([
                    "quantity" => $request->input('quantity')
                ]);
            }
            
            $product = Product::where('id',$cart->product_id)->first();
            $cart->price = $product->price * $request->input('quantity');

            return response()->json([
                "status" => 200,
                "message" => "Cart has been successfully edited",
                "data" => $cart
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
                "product_id" => "required",
            ],[
                "product_id.required" => "Product ID is required!",
            ]);

            $cart = Cart::where('product_id',$request->input('product_id'))->where('carted_by',$user)->first();
            if($cart) {
                $cart->delete();
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Product not found"],
                    404
                );
            }

            return response()->json([
                "status" => 200,
                "message" => "Product has been successfully removed from cart"],
                200
            );

        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }
    }
}

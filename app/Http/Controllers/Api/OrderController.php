<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Detail_Order;
use App\Models\Image;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function list() {
        $user = auth()->id();

        $orders = Order::where('ordered_by',$user)->get();
        foreach($orders as $x => $order ) {
            $dateTime = new DateTime($order->created_at);
            $formated_date = $dateTime->format('H:i d-M-Y');
            $date = Carbon::createFromFormat('H:i d-M-Y', $formated_date);
            $orders[$x]->last_made = $date->format('H.i l, d F Y');
        }

        return response()->json([
            "status" => 200,
            "data" => $orders],
            200
        );
    }

    public function detail($id) {
        $user = auth()->id();

        $orders = Detail_Order::where('order_id',$id)->get();
        foreach($orders as $x => $order) {
            $product_data = Product::where('id',$order->product_id)->first();
            $orders[$x]->product = $product_data;
            $image = Image::where('product_id',$order->product_id)->first();
            if($image) {
                $orders[$x]->product->photo = url('storage/'.$image->image);
            } else {
                $orders[$x]->product->photo = null;
            }
            $orders[$x]->total_price = $product_data->price * $order->quantity;
        }
        $orders->overall_price += $orders->total_price;

        return response()->json([
            "status" => 200,
            "data" => $orders],
            200
        );
    }

    public function order_product(Request $request) {
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

            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $shuffleCharacters = str_shuffle($characters);
            $randomString = substr($shuffleCharacters, 0, 5);
            $numbers = '1234567890';
            $shuffleNumbers = str_shuffle($numbers);
            $randomNumber = substr($shuffleNumbers, 0, 10);

            $product = Product::find($request->input('product_id'));
            $total_price = $product->price * $request->input('quantity');
            $order = Order::create([
                "order_id" => "KI" . $randomString . $randomNumber,
                "status" => "success",
                "total_product" => $request->input('quantity'),
                "total_price" => $total_price
            ]);

            Detail_Order::create([
                "order_id" => $order->id,
                "product_id" => $request->input('product_id'),
                "quantity" => $request->input('quantity')
            ]);

            $product->stock = $product->stock - $request->input('quantity');
            $product->save();

            return response()->json([
                "status" => 200,
                "message" => "Product has been successfully ordered"],
                200
            );
            
        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }
    }

    public function order_via_cart(Request $request) {
        $user = auth()->id();
        
        try {
            $request->validate([
                "cart_id" => "required"
            ],[
                "product_id.required" => "Product ID is required!"
            ]);

            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $shuffleCharacters = str_shuffle($characters);
            $randomString = substr($shuffleCharacters, 0, 5);
            $numbers = '1234567890';
            $shuffleNumbers = str_shuffle($numbers);
            $randomNumber = substr($shuffleNumbers, 0, 10);

            $product = Product::find($request->input('product_id'));
            $total_price = $product->price * $request->input('quantity');
            $order = Order::create([
                "order_id" => "KI" . $randomString . $randomNumber,
                "status" => "success",
                "total_product" => $request->input('quantity'),
                "total_price" => $total_price
            ]);

            $carts_id = $request->input('cart_id');
            foreach ($carts_id as $cart_id) {
                $cart = Cart::find($cart_id);
                $product = Product::find($cart->product_id);
                $product->stock = $product->stock - $request->input('quantity');
                $product->save();
                Detail_Order::create([
                    "order_id" => $order->id,
                    "product_id" => $cart->product_id,
                    "quantity" => $cart->quantity
                ]);
            }

            return response()->json([
                "status" => 200,
                "message" => "Product has been successfully ordered"],
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

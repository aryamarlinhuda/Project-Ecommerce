<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Image;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list(Request $request) {
        $katakunci = $request->katakunci;

        if(strlen($katakunci)) {
            $data = Product::where('name','like','%'.$katakunci.'%')->paginate(10);
        } else {
            $data = Product::paginate(10);
        }
        
        return view('product.list-product')->with('data',$data);
    }

    public function detail($id) {
        $data = product::findOrFail($id);
        $images = Image::where('product_id',$id)->get();
        $reviews = Review::where('product_id',$id)->get();

        if(!$data->price) {
            $data->price = "Free";
        } else {
            $data->price = "Rp ".number_format($data->price).",00";
        }

        $check = Review::where('product_id',$id)->first();
        if(!$check) {
            $data->rating = "No Reviews Yet";
        } else {
            $average_rating = Review::where('product_id',$id)->average('rating');
            $getRating = substr($average_rating, 0, 3);
            $formattedRating = str_replace('.', ',', $getRating);
            $data->rating = $formattedRating;
        }

        return view('product.detail-product', compact('data','images','reviews')); 
    }
    
    public function create() {
        $categories = Category::orderBy('name','asc')->get();

        return view('product.create-product')->with('categories',$categories);
    }

    public function create_process(Request $request) {
        $request->validate([
            "name" => "required",
            "description" => "required",
            "category_id" => "required",
            "stock" => "required",
            "price" => "required"
        ],[
            "name.required" => "Product Name is required!",
            "description.required" => "Description is required!",
            "category_id.required" => "Category is required!",
            "stock.required" => "Product Stock is required!",
            "price.required" => "Product Price is required!",
        ]);

        $named = product::where('name',$request->input('name'))->first();
        if($named) {
            return redirect('product/create')->with('unique','product Name already exists!');
        }

        $request->validate([
            'files.*' => 'max:3048'
        ],[
            "photo.max" => "Photo size must be less than 3MB!",
        ]);

        $files = $request->file('files');

        if($files) {
            foreach($files as $file) {
                $format = $file->getClientOriginalExtension();
                if(!strtolower($format) === 'jpg' || !strtolower($format) === 'jpeg' || !strtolower($format) === 'png') {
                    return redirect('admin/create')->with('format','The photo product format must be jpg, jpeg, or png!');
                }
            }
        }

        product::create([
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "category_id" => $request->input('category_id'),
            "stock" => $request->input('stock'),
            "price" => $request->input('price')
        ]);

        if($files) {
            foreach($files as $file) {
                $photo = $file->store('product');
                $product_id = product::where('name',$request->input('name'))->first();
                Image::create([
                    "image" => $photo,
                    "product_id" => $product_id->id
                ]);
            }
        }

        return redirect('product/list')->with('success','Product successfully created');
    }

    public function restock($id) {
        $data = Product::find($id);

        return view('product.restock-product')->with('data',$data);
    }

    public function restock_process(Request $request, $id) {
        $data = Product::find($id);

        $request->validate([
            "restock" => "required",

        ],[
            "restock.required" => "Product Restock is required!"
        ]);

        $data->stock = $data->stock + $request->input('restock');
        $data->save();

        return redirect('product/list')->with('success','Product Successfully Restocked!');
    }

    public function edit($id) {
        $data = product::find($id);
        $photos = Image::where('product_id',$id)->get();
        $categories = Category::whereNotIn('id',[$data->category_id])->orderBy('name','asc')->get();

        return view('product.edit-product', compact('data','photos','categories'));
    }

    public function edit_process($id, Request $request) {
        $data = product::find($id);
        $request->validate([
            "name" => "required",
            "description" => "required",
            "category_id" => "required",
            "stock" => "required",
            "price" => "required"
        ],[
            "name.required" => "Product Name is required!",
            "description.required" => "Description is required!",
            "category_id.required" => "Category is required!",
            "stock.required" => "Product Stock is required!",
            "price.required" => "Product Price is required!",
        ]);

        $named = product::whereNotIn('id',[$id])->where('name',$request->input('name'))->first();
        if($named) {
            return redirect('product/edit/'.$id)->with('unique','Product Name already exists!');
        }

        $request->validate([
            'files.*' => 'max:3048'
        ],[
            "photo.max" => "Photo size must be less than 3MB!",
        ]);

        $files = $request->file('files');

        if($files) {
            foreach($files as $file) {
                $format = $file->getClientOriginalExtension();
                if(!strtolower($format) === 'jpg' || !strtolower($format) === 'jpeg' || !strtolower($format) === 'png') {
                    return redirect('product/edit/'.$id)->with('format','The photo product format must be jpg, jpeg, or png!');
                }
            }
        }

        product::where('id',$id)->update([
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "category_id" => $request->input('category_id'),
            "stock" => $request->input('stock'),
            "price" => $request->input('price')
        ]);

        if($files) {
            foreach($files as $file) {
                $photo = $file->store('product');
                $product_id = product::where('name',$request->input('name'))->first();
                Image::create([
                    "image" => $photo,
                    "product_id" => $product_id->id
                ]);
            }
        }

        return redirect('product/list')->with('success','Product successfully edited');
    }

    public function del_photo($id) {
        $data = Image::where('id',$id)->first();
        Image::where('id',$id)->delete();

        return redirect('product/edit/'.$data->product_id)->with('deleted','Image successfully deleted');
    }
    public function delete($id) {
        Image::where('product_id',$id)->delete();
        Product::where('id',$id)->delete();

        return redirect('product/list')->with('success','Product successfully deleted');
    }
}

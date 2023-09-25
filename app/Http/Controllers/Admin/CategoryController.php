<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list(Request $request) {
        $katakunci = $request->katakunci;
        if(strlen($katakunci)) {
            $data = Category::where('name','like','%'.$katakunci.'%')->paginate(10);
        } else {
            $data = Category::paginate(10);
        }
        return view('category.list-category')->with('data',$data);
    }
    
    public function create() {
        return view('category.create-category');
    }

    public function create_process(Request $request) {
        $request->validate([
            "name" => "required"
        ],[
            "name.required" => "Category Name is required!"
        ]);

        $unique = Category::where('name',$request->input('name'))->first();
        if($unique) {
            return redirect('category/create')->with('unique','Category Name already exists!');
        }

        Category::create([
            "name" => $request->input('name')
        ]);

        return redirect('category/list')->with('success','Category created successfully!');
    }

    public function edit($id) {
        $data = Category::find($id);

        return view('category.edit-category')->with('data',$data);
    }

    public function edit_process(Request $request, $id) {
        $request->validate([
            "name" => "required"
        ],[
            "name.required" => "Category Name is required!"
        ]);

        $unique = Category::where('name',$request->input('category'))->whereNotIn('id',[$id])->first();
        if($unique) {
            return redirect('category/edit/'.$id)->with('unique','Category Name already exists!');
        }

        Category::where('id',$id)->update([
            "name" => $request->input('name')
        ]);

        return redirect('category/list')->with('success','Category edited successfully!');
    }

    public function delete($id) {
        Category::where('id',$id)->delete();

        return redirect('category/list')->with('success','Category successfully deleted');
    }
}

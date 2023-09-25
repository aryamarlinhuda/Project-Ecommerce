<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function profile() {
        $id = auth()->id();
        $data = User::find($id);

        if($data->photo) {
            $data->photo_profile = url("storage/".$data['photo']);
        } else {
            $data->photo_profile = null;
        }

        return response()->json([
            "status" => 200,
            "data" => $data],
            200
        );
    }

    public function edit_profile(Request $request) {
        $id = auth()->id();
        $data = User::find($id);

        try {
            $request->validate([
                "name" => "required",
                "photo" => "file | max:3048"
            ],[
                "name.required" => "Name is required!",
                "photo.file" => "Photo must be an image file",
                "photo.max" => "Photos must be less than 3 MB!",
            ]);

            $file = $request->file('photo');
            if($file) {
                $format = $file->getClientOriginalExtension();
                if(strtolower($format) === 'jpg' || strtolower($format) === 'jpeg' || strtolower($format) === 'png') {
                    $photo = $request->file('photo')->store('photo');
                } else {
                    return response()->json([
                        "status" => 400,
                        "message" => "The photo format must be jpg, jpeg, or png"
                    ], 400);
                }
            } else {
                $photo = $data->photo;
            }

            User::where('id',$id)->update([
                "name" => $request->name,
                "photo" => $photo
            ]);

            $update = User::find($id);

            if($update->photo) {
                $update->photo_profile = url("storage/".$update['photo']);
            } else {
                $update->photo_profile = null;
            }

            return response()->json([
                "status" => 200,
                "message" => "Profile edit successfully!",
                "data" => $update],
                200
            );
        } catch (ValidationException $e) {
            return response()->json([
                "status" => 400,
                "errors" => $e->errors(),
            ], 400);
        }
    }

    public function edit_password(Request $request) {
        $id = auth()->id();
        $user = User::find($id);

        try {
            $request->validate([
                "old_password" => "required | min:6",
                "new_password" => "required | min:6",
                "confirm_new_password" => "required | min:6 | same:new_password"
            ],[
                "old_password.required" => "Old Password is required!",
                "old_password.min" => "Old Password must contain 6 characters or more",
                "new_password.required" => "New Password is required!",
                "new_password.min" => "New Password must contain 6 characters or more",
                "confirm_new_password.required" => "Confirm New Password is required!",
                "confirm_new_password.min" => "Confirm New Password must contain 6 characters or more",
                "confirm_new_password.same" => "Confirm New Password doesn't match new password"
            ]);

            if(!Hash::check($request->old_password,$user->password)) {
                return response()->json([
                    "status" => 400,
                    "message" => "Old Password is wrong!"],
                    400
                );
            }

            if($request->old_password === $request->new_password) {
                return response()->json([
                    "status" => 400,
                    "message" => "The New Password is the same as The Old Password"],
                    400
                );
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'Password edited successfully'],
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
<?php

namespace App\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class UserController extends Controller
{


    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users|string',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' =>
                    $validator->errors()
            ], 422);
        }

        $user = User::whereEmail($request['email'])->firstOrFail();

        if (Hash::check($request->input('password'), $user->password)) {

            $c = true;

            do {
                $random = Str::random(100);
                $is_exist_api = User::where('api_token', $random)->first();
                if (!$is_exist_api) {
                    $c = false;
                }
            } while ($c);

            // Success
            $user->update([
                'api_token' => $random
            ]);
            return new UserResource($user);
        } else {
            // Go back on error (or do what you want)
            return response()->json([
                'data' => 'اطلاعات صحیح نیست'
            ], 403);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'data' =>
                    $validator->errors()
            ], 403);
        }

        $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

        return new UserResource($user);

    }
}

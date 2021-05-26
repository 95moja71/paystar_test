<?php

namespace App\Http\Controllers\v1\User;

use App\Http\Controllers\Controller;
use App\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{


    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'roles' =>  ['required', 'array'],
            'permissions' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' =>
                    $validator->errors()
            ], 422);
        }
        $user->permissions()->sync($request->permissions);
        $user->roles()->sync($request->roles);
        return response()->json(
            [
                'data' => [
                    "status" => true
                ]
            ],
            200);

    }
}

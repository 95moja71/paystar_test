<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-roles')->only(['index']);
        $this->middleware('can:create-role')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::query();
        $roles = $roles->latest()->paginate(20);

        return $roles->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'label' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' =>
                    $validator->errors()
            ], 422);
        }




        DB::beginTransaction();
        $role = Role::create([
            'name'=>$request->name,
            'label'=>$request->label,
        ]);
        $role->permissions()->sync($request->permissions);

        DB::commit();

        return response()->json(
            [
                'data' => [
                    "id" => $role->id,
                    "status" => true
                ]
            ],
            200);

    }

}

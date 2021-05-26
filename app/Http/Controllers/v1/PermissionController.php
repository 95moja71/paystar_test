<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:show-permissions')->only(['index']);
        $this->middleware('can:create-permission')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $permissions = Permission::query();
        $permissions = $permissions->latest()->paginate(20);

        return $permissions->toJson(JSON_PRETTY_PRINT);

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
            'name' => ['required', 'string', 'max:255', 'unique:permissions'],
            'label' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' =>
                    $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        $permission = Permission::create($request->all());

        DB::commit();

        return response()->json(
            [
                'data' => [
                    "id" => $permission->id,
                    "status" => true
                ]
            ],
            200);
    }


}

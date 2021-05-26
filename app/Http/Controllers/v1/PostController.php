<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Post as CourseResources;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:show-post')->only(['index']);
        $this->middleware('can:create-post')->only(['store']);
        $this->middleware('can:update-post')->only(['update']);
        $this->middleware('can:delete-post')->only(['delete']);
    }

    public function index()

    {

        $courses = Post::paginate(3);
        return CourseResources::collection($courses);


    }


    public function store(Request $request)
    {

        if ($request->isJson()) {

            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required|max:50|min:5',
                    'body' => 'required|max:500|min:10',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'data' =>
                        $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            $post = Post::create(
                [
                    'title' => $request->title,
                    'body' => $request->body,
                    'user_id' => auth()->user()->id,
                ]

            );
            DB::commit();
            return response()->json(
                [
                    'data' => [
                        "id" => $post->id,
                        "status" => true
                    ]
                ],
                200);


        } else {
            return response()->json([
                'data' => "not json"
            ], 422);
        }


    }

    public function update(Request $request,Post $post)
    {

        if ($request->isJson()) {

            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required|max:50|min:5',
                    'body' => 'required|max:500|min:10',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'data' =>
                        $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            $post->update([
                'title' => $request->title,
                'body' => $request->body,

            ]);
            DB::commit();
            return response()->json(
                [
                    'data' => [
                        "status" => "updated successfully"
                    ]
                ],
                200);


        } else {
            return response()->json([
                'data' => "not json"
            ], 422);
        }

    }

    public function destroy(Post $post)
    {
        DB::beginTransaction();
        $post->delete();
        DB::commit();
        return response()->json(
            [
                'data' => [
                    "status" => "deleted successfully"
                ]
            ],
            200);
    }


}

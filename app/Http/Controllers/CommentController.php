<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Product;
use Dotenv\Validator;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function list($product_id)
    {
        $product = Product::where('id', $product_id)->first();
        if ($product) {
            $comments=Comment::with(['user'])->where('product_id',$product_id)
            ->orderBy('id','desc')->paginate(5);


                return response()->json([
                    'message'=>'comment get',
                    'data'=>$comments,

                ],200);



        }
        else{
            return response()->json([
                'message'=>'comment not found',

            ]);
        }

    }
    public function store($product_id, Request $request)
    {
        $product = Product::where('id', $product_id)->first();
        if ($product) {
            $validator = validator($request->all(), [
                'message' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->messages()
            ], 422);
        }
        $comment = Comment::create([
            'message' => $request->message,
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
        ]);
        $comment->load('user');

        return response()->json([
            'message'=>'comment added',
            'name'=>$comment->user->name,
            'data'=>$comment['message'],

        ],200);


    }
}


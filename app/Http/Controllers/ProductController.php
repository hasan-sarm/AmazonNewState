<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BassController;
use App\Http\Resources\Product as ProductResources;
use App\Http\Resources\Product2 as ProductResources2;

use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class ProductController extends BassController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    //////////////////////////////////////////////////////////////////////////////////////////
    public function index() //فقط الصوره والاسم مع رقم المنتج للمساعده في عرضه//عرض جميع المنتجات الموجودة
    {

       $product = Product::all();

        return $this->sendResponse_2(ProductResources2::collection($product));
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////
   /* public function UserProdacts($id) //عرض المنتجات الخاصه بمستخدم معين عن طريق ال id الخاص بالمستخدم
    {
        $product = Product::where('id',$id)->get();
        return $this->sendResponse(ProductResources::collection($product),'products');
    }*/
/////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////
    public function store(Request $request)
    {
        $input= $request->all();
        $rules =[
            'name'=>'required',
            'image'=>'nullable|image',
            'Ex'=>'required',
            'type'=>'required',
            'number'=>'required|numeric',
            'qu'=>'required|numeric',
            'price'=>'required|numeric',
        ];
        $validator=validator($input,$rules);
        if ($validator->fails()) {
            return $this->sendError('validator error', $validator->errors());
        }

        $user = Auth::user();
        $input['user_id']=$user->id;
        if($request->image && $request->image->isValid())
        {
            $file_extension = $request->image->extension();
            $file_name = time() . '.' . $file_extension;
            $request->image->move(public_path('images/products'), $file_name);
            $path = "public/images/products/$file_name";
            $input['image']=$path;
        }

        $product = Product::create($input);
        return $this->sendResponse($product,'product store successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function show($id)
    {
        $product= Product::find($id);
        if (is_null($product)) {
            return $this->sendError('product not found');
        }
        return $this->sendResponse(new ProductResources($product),'product found successfully');
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    public function search(Request $request )
    {
        $query=Product::query();
        if ($s=$request->input('s')) {
            $query->whereRaw("name like '%".$s."%'")
                ->orWhereRaw("type like'%".$s."%'")
                ->orWhereRaw("Ex like'%".$s."%'");
        }
        return $query->get();
    }


    //////////////////////////////////////////////////////////////////////////////
    public function sorting(Request $request)
    {
        $product=Product::query();
        if ($sort=$request->input('sort')) {
            $product->orderBy('price', $sort);
        }
            return $product->get();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function update(Request $request, Product $product)
    {
        $errormessage=[];
        $input = $request->all();
        $validator = validator($input, [

            'qu' => 'required|numeric',

        ]);
        if ($validator->fails()) {
            return $this->sendError('validator error', $validator->errors());
        }
       if( $product->user_id != Auth::id())
       {
           return $this->sendError('you dont have rights',$errormessage);
       }
       /* $product->name= $input['name'] ;
        $product->type= $input['type'] ;
        $product->number= $input['number'] ;*/
        $product->qu= $input['qu'] ;
        //$product->price= $input['price'] ;*/
        $product->save();
        return $this->sendResponse(new ProductResources($product),'product update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        $errormessage=[];
        if( $product->user_id != Auth::id())
        {
            return $this->sendError('you dont have rights', $errormessage);
        }
        $product->delete();
        return $this->sendResponse(new ProductResources($product),'product delete successfully');
    }
}

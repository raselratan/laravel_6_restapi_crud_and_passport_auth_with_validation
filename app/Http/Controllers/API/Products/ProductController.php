<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Products\Product;

class ProductController extends Controller
{
    public function index(){
        return Product::all();
    }
    public function createProduct(Request $request){

        $validator = Validator::make($request->all(),[
            'name'      =>'required|max:255',
            'category'  =>'required',
            'price'     =>'required',
        ]);



        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()],401);
        }

        $new_product            = new Product;
        $new_product->name      = $request->input('name');
        $new_product->category  = $request->input('category');
        $new_product->price     = $request->input('price');

        if($new_product->save()){
            return ['msg'=>'Success','data'=>$new_product];
        }else{
            return ['msg'=>'Failed','data'=>''];
        }
    }
    public function updateProduct(Request $request,$id){

        $product_find = Product::find($id);

        $validator = Validator::make($request->all(),[
            'name'      =>'required|max:255',
            'category'  =>'required',
            'price'     =>'required',
        ]);



        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()],401);
        }

        if($product_find ){
            $product_find->name         = $request->input('name');
            $product_find->category     = $request->input('category');
            $product_find->price        = $request->input('price');

            if($product_find->save()){
                return ['msg'=>'Updated','data'=>$product_find];
            }else{
                return ['msg'=>'Not Updated'];
            }
        }
        return ['msg'=>'Not Updated'];

    }
    public function deleteProduct($id){
        $product_find = Product::find($id);
        if($product_find->delete()){
            return ['msg'=>'Deleded'];
        }else{
            return ['msg'=>'Not Deleded'];
        }
    }
}

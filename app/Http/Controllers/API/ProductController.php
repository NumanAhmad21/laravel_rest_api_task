<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Get list of blogs
        if(Auth::guard('api')->user()){
          $products = Product::all();
          $message = 'Products retrieved successfully.';
          $status = true;
  
          //Call function for response data
          $response = $this->response($status, $products, $message);
          return $response;
        }
        return Response(['data'=>'Unauthorized'], 401);
          
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Get request data
        $input = $request->all();
        //Validate requested data
        $validator = Validator::make($input, [
            'product_name' => 'required',
            'selling_price' => 'required'
        ]);
        //store products data into db
        if(Auth::guard('api')->user()){
        
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $product = Product::create($input);
        $message = 'Product created successfully.';
        $status = true;

        //Call function for response data
        $response = $this->response($status, $product, $message);
        return $response;
        }
        return Response(['data'=>'Unauthorized'], 401);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        if(Auth::guard('api')->user()){
            $product = Product::find($id);

            //Check if the blog found or not.
            if (is_null($product)) {
                $message = 'product not found.';
                $status = false;
                $response = $this->response($status, $product, $message);
                return $response;
            }
            $message = 'Product retrieved successfully.';
            $status = true;

            //Call function for response data
            $response = $this->response($status, $product, $message);
            return $response;
        }
        return Response(['data'=>'Unauthorized'], 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
         //Get request data
         $input = $request->all();

        //Validate requested data
        $validator = Validator::make($input, [
            'product_name' => 'required',
            'selling_price' => 'required'
        ]);
        if(Auth::guard('api')->user()){
        
        if ($validator->fails()) {
            $message = $validator->errors();
            $products = [];
            $status = 'fail';
            $response = $this->response($status, $products, $message);
            return $response;
        }

        //Update blog
        $data =array();
        $data['product_name'] = $input['product_name'];
        $data['product_code'] = $input['product_code'];
        $data['product_quantity'] = $input['product_quantity'];
        $data['product_details'] = $input['product_details'];
        $data['product_color'] = $input['product_color'];
        $data['product_size'] = $input['product_size'];
        $data['selling_price'] = $input['selling_price'];
        $data['discount_price'] = $input['discount_price'];
        $data['status'] = $input['status'];
       $product = Product::find($id)->update($data);
       if($product){
        $message = 'Product updated successfully.';
        $status = true;
       }
       else{
        $message = 'Product Not updated .';
        $status = false;
       }
        //Call function for response data
        $response = $this->response($status, $product, $message);
        return $response;

        }
        return Response(['data'=>'Unauthorized'], 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        if(Auth::guard('api')->user()){
            //Delete blog
        $product = Product::findOrFail($id);
        if($product){
            $product->delete();
            $message = 'Product deleted successfully.';
            $status = true;
        }
        else{
            $product= [];
            $message = 'Product deleted successfully.';
            $status = false;
        }
        

        //Call function for response data
        $response = $this->response($status, $product, $message);
        return $response;
        }
        return Response(['data'=>'Unauthorized'], 401);
    }
    public function response($status, $product, $message)
    {
        //Response data structure
        $return['success'] = $status;
        $return['data'] = $product;
        $return['message'] = $message;
        return $return;
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
class Usercontroller extends Controller
{
    public function index()
    {
        //Get list of blogs
        if(Auth::guard('api')->user()){
          $user = User::all();
          $message = 'users retrieved successfully.';
          $status = true;
  
          //Call function for response data
          $response = $this->response($status, $user, $message);
          return $response;
        }
        return Response(['data'=>'Unauthorized'], 401);
          
    }
    //show user 
    public function showUser($id)
    {
        //
        if(Auth::guard('api')->user()){
            $user = User::find($id);

            //Check if the blog found or not.
            if (is_null($user)) {
                $message = 'user not found.';
                $status = false;
                $response = $this->response($status, $user, $message);
                return $response;
            }
            $message = 'user retrieved successfully.';
            $status = true;

            //Call function for response data
            $response = $this->response($status, $user, $message);
            return $response;
        }
        return Response(['data'=>'Unauthorized'], 401);
    }
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request):Response
    {
        //
        $input = $request->all();
        Auth::attempt($input);
        $user = Auth::user();
        $token = $user->createToken('code')->accessToken;
        return Response(['status'=> 200, 'token'=>$token], 200);    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail(Request $request):Response
    {
        //
        if(Auth::guard('api')->user()){
            $user = Auth::guard('api')->user();
            return Response(['status'=> 200, 'data'=>$user], 200);
        }
        return Response(['data'=>'Unauthorized'], 401);
    }

    /**
     * Display the specified resource.
     */
    public function userLogout():Response
    {
        //
        if(Auth::guard('api')->check()){
            $accessToken = Auth::guard('api')->user()->token();
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update(['revoked' => true]);
                $accessToken->revoke();
            return Response(['data'=>'User logout Successfully!'], 200);
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    //store user 
    public function store(Request $request)
    {
        //Get request data
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $input['password'] = bcrypt($input['password']);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        //store products data into db
        if(Auth::guard('api')->user()){
        
        
        $user = User::create($input);
        $message = 'user created successfully.';
        $status = true;
        $token = $user->createToken('code')->accessToken;
        //Call function for response data
        $response = $this->response($status, $token, $message);
        return $response;
        }
        return Response(['data'=>'Unauthorized'], 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
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

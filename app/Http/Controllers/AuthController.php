<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request){
        try{

        
        $fields= $request->validate([
            'name'=>'required|string',
            'email' => 'required|string|unique:users,email',
            'password'=>'required|string|confirmed',
            'role' => 'required|string',
            
        ]);

        $user=User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
            'role' => $fields['role'],

        ]);

        $token= $user->createToken('myapp')->plainTextToken;

        $response= [
            'user'=>$user,
            'token'=>$token,
        ];
        return Response($response, 201);
        } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred'], 500);
        }
    }


    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return [
            'message'=>'User loged out'];
    }



    public function login(Request $request)
    {
        try{
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check email
        $user=User::where('email',$fields['email'])->first();

        //Check password
        if(!$user|| !Hash::check($fields['password'], $user->password)){
            return Response(['message'=>'Wrong creds'], 401);
        }

        $token = $user->createToken('myapp')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return Response($response, 201);
         } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}

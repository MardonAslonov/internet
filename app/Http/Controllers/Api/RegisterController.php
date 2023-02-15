<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Str;

class RegisterController extends BaseController
{


    public function register(RegisterUserRequest $request)
    {
        $input = $request->all();
        $user = User::create($input);
        // $token = $user->createToken($user->name);
        // $token =  Str::random(250);
        return $user->createToken($user->name)->plainTextToken;
        // return $token;
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:4'
        ]);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth()->attempt($credentials, false)) {
            $client = new Client(['verify' => false]);
            try {
                $response = $client->post("http://auth.loc/oauth/token", [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => 2,
                        'client_secret' => "qQpwP9lfkEyqyaOY6Vyi0zK8dJYvTJtOnyYs5Jfj",
                        'username' => $request->email,
                        'password' => $request->password,
                        'scope' => '*',
                        'name'=>$request->firstName
                    ]
                ]);
                return json_decode($response->getBody());
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json(['errors' => 'password or login xato'], 401);
        }
    }

    public function salom()
    {
        return 'salom';

    }


    public function refresh(Request $request)
    {
        $validator = $request->validate( [
            'refresh_token' => 'required|string'
        ]);
        $client = new Client();
        try {
            $response = $client->post("http://auth.loc/oauth/token", [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => config('services.passport.id'),
                    'client_secret' => config('services.passport.secret'),
                    'scope' => '',
                ]
            ]);
            return $response->getBody();
        } catch (Exception $e) {
            return response()->json(['message'=>$e->getMessage()]);
        }
    }

}

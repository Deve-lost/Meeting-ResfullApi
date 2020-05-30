<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store','signin']]);
    }

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:5',
		]);

		$neko = new User([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);

		$credentials = $request->only('email', 'password');

		if ($neko->save()) {
		    if (! $token = auth()->guard('api')->attempt($credentials)) {
	            return response()->json(['error'=>true, 'message'=>'Invalid Credentials']);
		    }

			$neko->signin = [
				'href' => 'api/v1/user/sigin',
				'method' => 'POST',
				'params' => 'email,password'
			];

			$response = [
				'msg' => 'User Created!',
				'user' => $neko,
				'token' => $token
			];

			return response()->json([$response, $this->respondWithToken($token)], 201);
		}

		$response = [
			'msg' => 'An Error!'
		];

		return response()->json($response, 404);
	}

	public function signin(Request $request)
	{
		if ($user = User::where('email', $request->email)->first()) {
			$credentials = $request->only('email', 'password');

		    if (!$token = auth()->guard('api')->attempt($credentials)) {
	            return response()->json(['error'=>true, 'message'=>'Invalid Credentials']);
		    }

			$response = [
				'msg' => 'User Sigin!',
				'user' => $user,
				'token' => $token
			];

			return response()->json([$response, $this->respondWithToken($token)], 201);
		}

		$response = [
			'msg' => 'An Error!'
		];

		return response()->json($response, 404);
	}

	protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ], 200, [
            'Authorization'=> $token
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}

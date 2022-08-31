<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;

class ApiAuthController extends Controller
{
	// users Register API By Shahrookh Shaikh
	public function register (Request $request) {

		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required|string|max:255',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'required|string|min:6|confirmed',

			]);
			if ($validator->fails())
			{
				return response(['errors'=>$validator->errors()->all()], 422);
			}
			$request['password']=Hash::make($request['password']);
			$request['remember_token'] = Str::random(10);
			$request['role'] = 2;

			$user = User::create($request->toArray());
			$token = $user->createToken('Laravel Password Grant Client')->accessToken;
			$response = ['token' => $token];
			return response($response, 200);

		} catch (\Exception $ex) {
			return response()->json(['success' => false, 'error' => $ex->getMessage()], 500);
		}
	}

	// Users Login API By Shahrookh Shaikh
	public function login (Request $request) {

		try {

			$validator = Validator::make($request->all(), [
				'email' => 'required|string|email|max:255',
				'password' => 'required|string|min:6',
			]);

			if ($validator->fails())
			{
				return response(['errors'=>$validator->errors()->all()], 422);
			}

			$user = User::where('email', $request->email)->first();

			if ($user) {

				if (Hash::check($request->password, $user->password)) {

					$token = $user->createToken('Laravel Password Grant Client')->accessToken;

					$response = ['token' => $token];
					return response([
						$response
					], 200);

				} else {
					$response = ["message" => "Password mismatch"];
					return response($response, 422);
				}
			} else {
				$response = ["message" =>'User does not exist'];
				return response($response, 422);
			}
		} catch (\Exception $ex) {
			return response()->json(['success' => false, 'error' => $ex->getMessage()], 500);
		}
	}

	// Users Logout API By Shahrookh Shaikh
	public function logout (Request $request) {

		try {
			$token = $request->user()->token();
			$token->revoke();
			$response = ['message' => 'You have been successfully logged out!'];
			return response($response, 200);
			
		} catch (\Exception $ex) {
			return response()->json(['success' => false, 'error' => $ex->getMessage()], 500);
		}

	}
}

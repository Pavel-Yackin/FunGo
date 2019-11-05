<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function signup(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $rules = [
            'email'    => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ];

        $validation = Validator::make($credentials, $rules);

        if ($validation->fails()) {
            abort(400, 'api|' . $validation->errors()->first());
        }

        $token = Str::random(60);

        $user = User::create([
            'name' => $credentials['email'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'api_token' => hash('sha256', $token),
        ]);

        $user->api_token = $token;


        return response()->json(['data' => $user ? $user->toArray():[]], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function signin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = Str::random(60);

            /** @var $user User */
            $user->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();

            $user->api_token = $token;
            $user->makeVisible('api_token');

            return response()->json(['data' => $user ? $user->toArray():[]], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        } else {
            abort(400, 'api|wrong_credntials');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function updatePassword(Request $request)
    {
        $oldPassword = $request->get('old_password');
        $newPassword = $request->get('new_password');

        $user = Auth::user();

        if ($user->getAuthPassword() == Hash::make($oldPassword)) {
            $token = Str::random(60);

            $user->forceFill([
                'password' => Hash::make($newPassword),
                'api_token' => hash('sha256', $token),
            ])->save();

            $user->api_token = $token;
            $user->makeVisible('api_token');

            return response()->json(['data' => $user ? $user->toArray():[]], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        } else {
            abort(400, 'api|wrong_password');
        }
    }
}

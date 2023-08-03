<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    public function allUsers()
    {

        $users = User::all();


        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->api_token = $user->createToken("auth_token")->accessToken;

                $user->save();
            }
            return response()->json([
                'status' => 1,
                'message' => 'All users',
                'Number of user' => count($users),
                'data' => $users
            ], 200);
        }
        return response()->json([
            'status' => 0,
            'message' => 'No User Available'
        ]);
    }
    /**
     * Register
     *
     * @param  mixed $request
     * @return void
     */
    public function Register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users',
            'password' => 'required|required|confirmed',
        ]);

        $validateData['password'] = bcrypt($request->password);
        $user = User::create($validateData);
        if ($user) {
            $accessToken = $user->createToken('auth_token')->accessToken;
            ProcessUser::dispatch($user->id);

            return response()->json([
                'status' => 1,
                'message' => 'User register Successfully',
                'user' => $user,
                'access_token' => $accessToken
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'An error occurred, Please try again later',

        ]);
    }


    /**
     * Login
     *
     * @param  mixed $request
     * @return void
     */
    public function Login(Request $request)
    {

        $validateData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($validateData)) {
            $user = auth()->user();

            $accessToken = $user->createToken('authToken')->accessToken;
            $user->api_token = $accessToken;
            $user->save();

            return response()->json([
                'status' => 1,
                'message' => 'Login Successfully',
                'user' => auth()->user(),
                // 'access_token' => $accessToken
            ]);
        }
    }


    /**
     * Profile
     *
     * @return void
     */
    public function Profile()
    {

        $user = auth()->user();

        return response()->json([
            'status' => 1,
            'user' => $user,
        ]);
    }

    /**
     * Logout
     *
     * @param  mixed $request
     * @return void
     */
    public function Logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json([
            'status' => 1,
            'message' => 'You have logout successfully'
        ]);
    }
}

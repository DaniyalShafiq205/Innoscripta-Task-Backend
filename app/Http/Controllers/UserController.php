<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validate the user input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new user instance
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Save the user to the database
        $user->save();

        // Generate JWT token for the user
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        // Validate the user input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Retrieve the user from the database based on the email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        // Verify the password
        if (!password_verify($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        // Generate JWT token for the user
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token]);
    }

    public function refresh()
    {
        // dump( $token = JWTAuth::getToken());
        try {
            $token = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($token);

            return response()->json(['token' => $newToken]);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token absent'], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out']);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token absent'], 401);
        }
    }

    public function me()
    {
        // $user = Auth::user();
        // $userId = $user->id;
        $userId = 1;
        $user = User::findOrFail($userId);
        $user->load([
            'sources' => function ($query) {
                $query->select('id', 'name');
            },
            'categories' => function ($query) {
                $query->select('id', 'name');
            },

            'authors' => function ($query) {
                $query->select('id', 'name');
            },
        ]);

        // Remove pivot data from sources,categories and authors
        $user->sources->each->makeHidden('pivot');
        $user->categories->each->makeHidden('pivot');
        $user->authors->each->makeHidden('pivot');

        return response()->json(['user' => $user], 200);

    }

}

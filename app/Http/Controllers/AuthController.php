<?php
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;
//use App\Models\User;
//
//class AuthController extends Controller
//{
//
//    public function login(Request $request)
//    {
//        $credentials = $request->only('email', 'password');
//
//        if (Auth::attempt($credentials)) {
//            $user = User::where('email', $request->email)->first();
//            $token = $user->createToken('authToken')->plainTextToken;
//            return response()->json(['token' => $token], 200);
//        }
//
//        return response()->json(['error' => 'Unauthorized'], 401);
//    }
//
//    public function logout(Request $request)
//    {
//        $request->user()->currentAccessToken()->delete();
//        return response()->json(['message' => 'Logged out successfully'], 200);
//    }
//}
//

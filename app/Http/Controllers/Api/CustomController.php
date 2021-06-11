<?php



namespace App\Http\Controllers\Api;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomController extends Controller
{

    public function sendInvitation(Request $request)
    {

        $request->validate([
            'email' => 'required',
        ]);

        $registration_link = $_SERVER['HTTP_HOST'] . '/user/registration';

        User::sendMailForRegistration($request->email);

        return response()->json(['success' => 'Email Sent Successfully', 'link' => $registration_link], 200);
    }

    /*
    * Registration  API
    */
    public function registration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'avatar' => 'required'
        ]);

        $imageName = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('images'), $imageName);
        $row = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => User::ROLE_USER,
            'avatar'  => $imageName,
            'registered_at' => $request->registered_at,
            'otp' => random_int(100000, 999999)
        ]);
        User::sendMailOtp();
        $success['token'] = $row->createToken('MyApp')->accessToken;
        $success['message'] = "User registered Successfully,Please verify Otp sent on your mail to login";

        return response()->json(['success' => $success], 200);
    }

    /*
    * Login  API
    */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password'), 'otp_verified' => User::OTP_VERIFIED])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }


    /*
    * Verify Otp   API
    */
    public function verifyOtp(Request $request)
    {


        if (Auth::check()) {
            $user = User::where('email', $request->email)->first();
            $user->otp_verified = User::OTP_VERIFIED;
            $user->save();
            return response()->json(['success' => 'Otp verified successfully ,Proceed to login'], 200);
        }

        return response()->json(['error' => 'not verified,Error'], 400);
    }


    /*
    * Update profile API
    */

    public function profileedit($id, Request $request)
    {

        //validator place

        $users = user::find($id);

        $users->name = $request->name;
        if (isset($request->avatar)) {
            $imageName = time() . '.' . $request->avatar->extension();

            $request->avatar->move(public_path('images'), $imageName);
            $users->avatar = $imageName;
        }
        $users->save();

        $data[] = [
            'id' => $users->id,
            'name' => $users->name,
            'avatar' => $imageName,
            'created_at' => $users->created_at,
            'updated_at' => $users->created_at,
            'registered_at' => $users->registered_at,
            'user_name' => $users->user_name,
            'status' => 200,
        ];
        return response()->json($data);
    }

    public function logoutApi()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }
}

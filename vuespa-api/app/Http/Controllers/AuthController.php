<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Hash;
use Mail;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PasswordResetTokens;
use App\CustomFunction\CustomFunction;

class AuthController extends BaseController
{
    /**
     * Signup
     */
    public function doRegister(Request $request)
    {
        if (!isset($request->name) || $request->name == "") {
            return response()->json(['data' => 'Please enter Name', 'code' => 0]);
        }
        if (!isset($request->email) || $request->email == "") {
            return response()->json(['data' => 'Please enter Email address', 'code' => 0]);
        }
        if (!CustomFunction::validate_input($request->email, 'email')) {
            return response()->json(['data' => 'Please enter valid Email address', 'code' => 0]);
        }
        if (!isset($request->password) || $request->password == "") {
            return response()->json(['data' => 'Please enter Password', 'code' => 0]);
        }
        if (strlen($request->password) < 6) {
            return response()->json(['data' => 'Password length must be min. 6 character', 'code' => 0]);
        }

        $name = CustomFunction::filter_input($request->name);
        $email = CustomFunction::filter_input($request->email);
        $password = CustomFunction::filter_input($request->password);
        $password_hash = Hash::make($password);

        $is_found_email = User::where('email', $email)->get()->count();
        if ($is_found_email > 0) {
            return response()->json(['data' => 'This email address is already registered', 'code' => 0]);
        }

        $new_user = new User();
        $new_user->name = $name;
        $new_user->email = $email;
        $new_user->password = $password_hash;
        $new_user->email_verified_at = date('Y-m-d H:i:s');
        $new_user->created_at = date('Y-m-d H:i:s');
        $new_user->updated_at = date('Y-m-d H:i:s');

        if ($new_user->save()) {
            return response()->json(['data' => 'Signup done successfully', 'code' => 1]);
        } else {
            return response()->json(['data' => 'Signup not done, please try again', 'code' => 0]);
        }
    }

    /**
     * Login
     */
    public function doLogin(Request $request)
    {
        if (!isset($request->email) || $request->email == "") {
            return response()->json(['data' => 'Invaild request, please try again.', 'code' => 0]);
        }
        if (!CustomFunction::validate_input($request->email, 'email')) {
            return response()->json(['data' => 'Please enter valid Email', 'code' => 0]);
        }
        if (!isset($request->password) || $request->password == "") {
            return response()->json(['data' => 'Invaild request, please try again.', 'code' => 0]);
        }


        $email = CustomFunction::filter_input($request->email);
        $password = CustomFunction::filter_input($request->password);

        $userData = User::where('email', $email)->first();

        if ($userData!=null) {

            if (!$userData || !Hash::check($password, $userData->password)) {
                return response()->json(['data' => 'Incorrect Email address or Password', 'code' => 0]);
            }

            $token = $userData->createToken('web-token')->plainTextToken;

            $user_info = array();

            $user_info['name'] = $userData->name;
            $user_info['email'] = $userData->email;

            return response()->json(['data' => $user_info, 'code' => 1, 'token' => $token]);
        } else {
            return response()->json(['data' => 'Incorrect Email address or password', 'code' => 0]);
        }
    }

    /**
     * check user token is valid or not
     */
    public function checkUserAuth(Request $request)
    {
        if(isset($request->user()->id)) {
            return response()->json(['data' => 'valid', 'code' => '1']);
        }
        return response()->json(['data' => 'notvalid', 'code' => '0']);
    }


    /**
     * Logout
     */
    public function doLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['data' => 'Logout', 'code' => '1']);
    }

    /**
     * Check forgot password token is valid
     */
    public function checkForgotPasswordToken(Request $request)
    {
        if (!isset($request->token) || $request->token == "") {
            return response()->json(['data' => 'No details found', 'code' => 0]);
        }
        $token = $request->token;

        $isValid = PasswordResetTokens::where('token', $token)->count();
        if($isValid) {
            return response()->json(['data' => 'Valid', 'code' => 1]);
        } else {
            return response()->json(['data' => 'No details found', 'code' => 0]);
        }
    }


    /**
     * Forgot Password Request
     */
    public function doForgotPassword(Request $request)
    {
        if (!isset($request->email) || $request->email == "") {
            return response()->json(['data' => 'Please enter Email address', 'code' => 0]);
        }
        if (!CustomFunction::validate_input($request->email, 'email')) {
            return response()->json(['data' => 'Please enter valid Email address', 'code' => 0]);
        }

        $email = CustomFunction::filter_input($request->email);

        $userData = User::where('email', $email)->first();

        if ($userData==null) {
            return response()->json(['data' => 'No Details found for this Email', 'code' => 0]);
        }

        $token = Str::random(30);

        PasswordResetTokens::where('email', $email)->delete();

        $PasswordResetTokens = new PasswordResetTokens();
        $PasswordResetTokens->email = $email;
        $PasswordResetTokens->token = $token;
        $PasswordResetTokens->created_at = date('Y-m-d H:i:s');

        if($PasswordResetTokens->save()) {

            try {
                $name = $userData->name;
                $serverurl = config('constant.serverurl');
                $reset_link = $serverurl.'reset-password/'.$token;

                $emaildata = array(
                    'email' => $email,
                    'name' => $name,
                    'reset_link' => $reset_link,
                );

                Mail::send('email_template.password_reset', $emaildata, function ($message) use ($email, $name) {
                    $message->to($email, $name)->subject('Reset Password request');
                });
            } catch (\Exception $exc) {
                //                dd($exc->getMessage());
                return response()->json(['data' => $exc->getMessage(), 'code' => 0]);
            }

            return response()->json(['data' => 'Password reset request sent successfully', 'code' => 1]);
        }

        return response()->json(['data' => 'Password reset request not sent, please try again.', 'code' => 0]);
    }

    /**
     * Reset Password From Email Link
     */
    public function doResetPassword(Request $request)
    {
        if (!isset($request->password) || $request->password == "") {
            return response()->json(['data' => 'Please enter Password', 'code' => 0]);
        }
        if (!isset($request->token) || $request->token == "") {
            return response()->json(['data' => 'Invaild request, please try again.', 'code' => 0]);
        }

        $password = $request->password;
        $token = $request->token;

        $userData = PasswordResetTokens::where('token', $token)->first();

        if($userData!=null) {
            $email = $userData->email;
            $password_hash = Hash::make($password);

            $updatepassword = User::where('email', $email)->update(['password' => $password_hash]);
            if ($updatepassword) {
                $userData->where('email', $email)->delete();
                return response()->json(['data' => 'Password reset successfully, You can Login now', 'code' => 1]);
            }
        }

        return response()->json(['data' => 'Password not reset, please try again.', 'code' => 0]);
    }

}
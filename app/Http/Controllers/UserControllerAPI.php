<?php

namespace App\Http\Controllers;

use App\Activation;
use App\Http\Resources\UserResource;
use App\Mail\ActivateAccount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\TransportManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Swift_Mailer;
use Validator;
use Illuminate\Support\Facades\Mail;

class UserControllerAPI extends Controller
{
    //CREATE USER
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'nickname' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($request->wantsJson() && !$validator->fails()) {

            try {

                $checkEmailExists = User::where('email', $request->input('email'))->first();
                if ($checkEmailExists) {
                    return response()->json(
                        ['errorCode' => 1, 'msg' => 'Email already being Used.'], 400);
                }

                $checkNicknameExists = User::where('nickname', $request->input('nickname'))->first();
                if ($checkNicknameExists) {
                    return response()->json(
                        ['errorCode' => 2, 'msg' => 'Nickname already being used.'], 400);
                }

                $userData = array('name' => $request->name, 'nickname' => $request->nickname, 'email' => $request->email, 'password' => Hash::make($request->password), 'admin' => '0', 'blocked' => '0', 'activated' => '0');
                $user = User::create($userData);

                //CREATE A ACTIVATION LINK
                $activation = new Activation();
                $activation->user_id = $user->id;
                $activation->token = str_random(64);
                $activation->save();

                $config = DB::table('config')->first();
                $mailConfigs = json_decode($config->platform_email_properties);
                config([
                    'mail.host' => $mailConfigs->host,
                    'mail.port' => $mailConfigs->port,
                    'mail.encryption' => $mailConfigs->encryption,
                    'mail.username' => $config->platform_email,
                    'mail.password' => $mailConfigs->password
                ]);

                $app = App::getInstance();
                $app->singleton('swift.transport', function ($app) {
                    return new TransportManager($app);
                });
                $mailer = new Swift_Mailer($app['swift.transport']->driver());
                Mail::setSwiftMailer($mailer);


                Mail::to($user->email)->queue(new ActivateAccount($activation->token, $config->platform_email, $user->email));

                return response()->json(['msg' => 'User Created.']);
            } catch (\Exception $e) {
                return response()->json(['errorCode' => -1, 'msg' => 'Problem Sending the email. Try Again later.', 'exc' => $e->getMessage()], 400);
            }
        } else {
            return response()->json(['errorCode' => -1, 'msg' => 'Invalid Request.'], 400);
        }
    }

    //ACTIVATE USER
    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($request->wantsJson() && !$validator->fails()) {
            $activation = Activation::where('token', $request->token)
                ->first();

            if (empty($activation)) {
                return response()->json(['msg' => 'Token inválido.'], 400);
            }

            $user = User::find($activation->user_id);
            $user->activated = true;
            $user->save();

            $activation->delete();

            return response()->json(['msg' => 'Utilizador activado.']);
        } else {
            return response()->json(['msg' => 'Request inválido.'], 400);
        }
    }



    public function getUsers(Request $request){
        if ($request->wantsJson()){
            $users = User::where('admin',0)->get();
            return UserResource::collection($users);
        }else{
            return response()->json(['message' => 'Request inválido.'], 400);
        }
    }

    public function getBlockedUsers(Request $request){
        if ($request->wantsJson()){
            $users = User::where('admin',0)->where('blocked', 1)->get();
            return UserResource::collection($users);
        }else{
            return response()->json(['message' => 'Request inválido.'], 400);
        }
    }
    public function getNewUsers(Request $request){
        if ($request->wantsJson()){
            $users = User::where('admin',0)->where('blocked', 0)->where('activated', 0)->get();
            return UserResource::collection($users);
        }else{
            return response()->json(['message' => 'Request inválido.'], 400);
        }
    }
}

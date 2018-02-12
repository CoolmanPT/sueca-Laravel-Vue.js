<?php

namespace App\Http\Controllers;

use App\Activation;
use App\Http\Resources\UserResource;
use App\Mail\ActivateAccount;
use App\Mail\ChangeState;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\TransportManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Swift_Mailer;
use Validator;

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


				Mail::to($user->email)->send(new ActivateAccount($activation->token, $config->platform_email, $user->email));

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


	public function getUsers(Request $request)
	{
		if ($request->wantsJson()) {
			$users = User::where('admin', 0)->get();
			return UserResource::collection($users);
		} else {
			return response()->json(['message' => 'Request inválido.'], 400);
		}
	}

	public function getBlockedUsers(Request $request)
	{
		if ($request->wantsJson()) {
			$users = User::where('admin', 0)->where('blocked', 1)->get();
			return UserResource::collection($users);
		} else {
			return response()->json(['message' => 'Request inválido.'], 400);
		}
	}

	public function getNewUsers(Request $request)
	{
		if ($request->wantsJson()) {
			$users = User::where('admin', 0)->where('blocked', 0)->where('activated', 0)->get();
			return UserResource::collection($users);
		} else {
			return response()->json(['message' => 'Request inválido.'], 400);
		}
	}

	//UPDATE EMAIL
	public function updateEmail(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|email'
		]);

		if ($request->wantsJson() && !$validator->fails()) {
			$checkEmailExists = User::where('id', '<>', $request->user()->id)
			->where('email', $request->input('email'))
			->first();

			if ($checkEmailExists) {
				return response()->json(
					['errorCode' => 1, 'msg' => 'Email in Use.'], 400);
			}
			$request->user()->update($request->all());
			return response()->json(['msg' => 'Email Saved.']);
		} else {
			return response()->json(['errorCode' => -1, 'msg' => 'Invalid Request.'], 400);
		}
	}


	public function updatePassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'currentPassword' => 'required',
			'newPassword' => 'required'
		]);

		if ($request->wantsJson() && !$validator->fails()) {


			if (!Hash::check($request->input('currentPassword'), $request->user()->password)) {
				return response()->json(
					['errorCode' => 1, 'msg' => 'Password incorrecta.'], 400);
			}

			$request->user()->password = Hash::make($request->input('newPassword'));
			$request->user()->save();

			return response()->json(['msg' => 'Password alterada com sucesso.']);
		} else {
			return response()->json(['errorCode' => -1, 'msg' => 'Request inválido.'], 400);
		}
	}

	public function updateAvatar(Request $request)
	{

		try{
			$validator = Validator::make($request->all(), [
				'image' => 'required|image64:jpeg,jpg,png'
			]);
			if ($validator->fails()) {
				return response()->json(['msg' => $validator->errors()]);
			} else {
	
				$imageData = $request->get('image');
				$fileName = 'avatar.png';
				Image::make($request->get('image'))->resize(150, 150)->save(public_path('img/avatars/') . $fileName);
				$request->user()->avatar = 'img/avatars/' . $fileName;
				$request->user()->save();
				return response()->json(['error' => false]);
			}
		} catch(\Exception $e) {
			print_r($e);
			exit();
		}
		
	}

	public function changeState(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'reason' => 'required|string'
		]);

		if ($request->wantsJson() && !$validator->fails()) {
			try {
				$user = $request->get('user1');
				$u = User::findOrFail($user['id']);
				$oldState = $user['blocked'];
				$newState = 0;
				if ($oldState == 1) {

					$newState = 0;
					$u->blocked = $newState;
					$u->reason_reactivated = $request->get('reason');
					$msg = 'Account reactivated.';
					$u->reason_blocked = null;

				} else {
					$newState = 1;
					$u->blocked = $newState;
					$u->reason_blocked = $request->get('reason');
					$u->reason_reactivated = null;
					$msg = 'Account blocked.';

				}
				$reason = nl2br($request->input('reason'));
				if ($reason != null) {
					$msg .= 'Reason: ' . $reason;
				}


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

				Mail::to($u->email)->queue(new ChangeState($msg, $config->platform_email));
				$u->save();

				return response()->json(['msg' => 'User changed']);
			} catch (\Exception $e) {
				print_r($e);
				exit();
				return response()->json(['msg' => 'Problem sending email.'], 400);
			}


		} else {
			return response()->json(['msg' => 'Invalid Request.'], 400);
		}

	}

	public function deletePlayer($id, $reasonToDelete)
	{
		//var_dump($request->input('reasonToDelete'));
		try {
			$user = User::findOrFail($id);
			$msg = 'Account deleted';
			$reason = nl2br($reasonToDelete);
			if ($reason != null) {
				$msg .= ' Reason: ' . $reason;
			}

			// inicio email
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

			Mail::to($user->email)->queue(new ChangeState($msg, $config->platform_email));
			// fim email

			$user->delete();

			return response()->json(['msg' => 'User deleted']);
		} catch (\Exception $e) {
			print_r($e);
			exit();
			return response()->json(['msg' => 'Problem sending email.'], 400);
		}
	}
}

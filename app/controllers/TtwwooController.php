<?php

class TtwwooController extends BaseController {

	protected $errors;
	protected $paths;
	protected $texts;

	public function __construct()
	{
		$this->paths['uploads'] = '../uploads';
		$this->paths['firsts'] = $this->paths['uploads'] . '/first';
		$this->paths['seconds'] = $this->paths['uploads'] . '/second';
		$this->paths['ttwwoos'] = $this->paths['uploads'] . '/ttwwoo';

		$this->texts['first'] = 'Before';
		$this->texts['second'] = 'After';
	}

	public function getIndex()
	{
		$user = $this->_getUser();
		if($user)
		{
			Session::put('uid', $user['id']);
			Session::put('token', $user['access_token']);
			return View::make('ttwwoo.make')->with('user', $user);
		}

		return Redirect::to('login')->with('errors', array('Please login to use ttwwoo'));
	}

	public function postIndex()
	{
		if(!$this->_isValidInput(Input::all()))
		{
			return Redirect::to('index')->with('errors', $this->errors);
		}

		$first = Input::file('first');
		$firstText = trim(Input::file('firstText')) != '' ? trim(Input::file('firstText')) : $this->texts['first'] ;
		$second = Input::file('second');
		$secondText = trim(Input::file('secondText')) != '' ? trim(Input::file('secondText')) : $this->texts['second'] ;
		$message = Input::file('message');

		$firstName = $first->getClientOriginalName().Session::get('uid').time();
		$secondName = $second->getClientOriginalName().Session::get('uid').time();
		Input::file('first')->move($this->path['firsts'], $firstName);
		Input::file('second')->move($this->paths['seconds'], $secondName);

		$first = Image::make($this->path['firsts'].'/'.$firstName)->resize(421.5, 350);
		$second = Image::make($this->paths['seconds'].'/'.$secondName)->resize(421.5, 350);

		$ttwwooName = Session::get('uid').'_'.time().str_random(6).'jpg';
		$ttwwoo = Image::canvas(843, 403, '#ffffff')
			->insert($first, 0, 50, 'left')
			->insert($second, 421.5, 50, 'left')
			->text($firstText, 100, 50, 32, '#333333', 0, 'eagle.ttf')
			->text($secondText, 522, 50, 32, '#333333', 0, 'eagle.ttf')
			->save($ttwwoos.'/'.$ttwwooName);

		$tid = $this->_saveTtwwooToUser(Session::get('uid'), $firstName, $firstText, $secondName, $secondText, $ttwwooName, $message);
		Session::put('tid', $tid);

		return View::make('ttwwoo.share');
	}

	public function getShare()
	{
		if(!Session::get('id'))
		{
			return Redirect::to('login');
		}

		$ttwwoo = $this->_getTtwwoo(Session::get('tid'));

		$args = array(
			'message' => $ttwwoo['message']
		);

		$args[basename($this->paths['ttwwoos'].'/'.$ttwwoo['ttwwoo_name'])] = '@'.realpath($this->paths['ttwwoos'].'/'.$ttwwoo['ttwwoo_name']);

		$ch = curl_init();
		$url = 'https://graph.facebook.com/me/photos?access_token='.Session::get('token');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
		$data = curl_exec($ch);

		return Redirect::to('index')->with('success', array('Ttwwoo shared successfully. Make more now!'));
	}

	public function getLogin()
	{
		$fb = OAuth::consumer('Facebook');

		if(Input::get('code'))
		{
			$token = $fb->requestAccessToken(Input::get('code'));

			$user = json_decode($fb->request('/me'), true);
			$user['oauth_provider'] = 'facebook';
			$user['access_token'] = $token->getAccessToken();
			$uid = $this->_saveUser($user);

			return Redirect::to('index')->withCookie(Cookie::forever('uid', $uid));
		}
		else
		{
			
			$user = $this->_getUser();
			if($user)
			{
				return Redirect::to('index');
			}
			else
			{
				return View::make('ttwwoo.login')->with('loginUrl', $fb->getAuthorizationUri()->getAbsoluteUri());
			}
		}
	}

	private function _getUser()
	{
		$fb = OAuth::consumer('Facebook');

		if(Cookie::get('uid'))
		{
			$accessToken = $this->_getAccessTokenByUser(Cookie::get('uid'));
			if($accessToken)
			{
				$token = new OAuth\OAuth2\Token\StdOAuth2Token;
				$token->setAccessToken($accessToken);
				$fb->getStorage()->storeAccessToken('facebook', $token);
				$user = json_decode($fb->request('/me'), true);
				$user['access_token'] = $accessToken;
				return $user;
			}
		}
		return false;
	}

	private function _saveUser($user)
	{
		$uid = DB::table('users')->insertGetId(array(
			'email' => $user['email'],
			'oauth_uid' => $user['id'],
			'oauth_provider' => $user['oauth_provider'],
			'username' => $user['username'],
			'access_token' => $user['access_token'],
			'created_at' => DB::raw('now()'),
			'updated_at' => DB::raw('now()')
		));

		return $uid;
	}

	private function _saveTtwwooToUser($uid, $firstName, $firstText, $secondName, $secondText, $ttwwooName, $message)
	{
		$tid = DB::table('ttwwoos')->insertGetId(array(
			'user_id' => $uid,
			'first_name' => $firstName,
			'first_text' => $firstText,
			'second_name' => $secondName,
			'second_text' => $secondText,
			'ttwwoo_name' => $ttwwooName,
			'message' => $message,
			'ip' => Request::getClientIp(),
			'created_at' => DB::raw('now()'),
			'updated_at' => DB::raw('now()')
		));

		return $tid;
	}

	private function _getAccessTokenByUser($uid)
	{
		$user = DB::table('users')
			->where('id', '=', Cookie::get('uid'))
			->where('deleted_at', '=', '0000-00-00 00:00:00')
			->select()
			->first();
		if($user) return $user['access_token'];

		return false;
	}

	private function _isValidInput($input)
	{
		$rules = array(
			'first' => 'image|mimes:jpeg,jpg,png',
			'second' => 'image|mimes:jpeg,jpg,png',
			'message' => 'required|max:200'
		);

		$messages = array(
			'first' => 'First photo should be a photo with JPEG, JPG or PNG extension',
			'second' => 'Second photo should be a photo with JPEG, JPG or PNG extension',
			'message' => 'Message is required and should be below 200 characters',
		);

		$validator = Validator::make($input, $rules);

		if($validator->fails())
		{
			$this->errors = $validation->messages()->all();
			return false;
		}
		return true;
	}
}
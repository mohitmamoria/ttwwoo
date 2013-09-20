<?php

class TtwwooController extends BaseController {

	public function getIndex()
	{
		$user = $this->_getUser();
		return View::make('ttwwoo.make');
	}

	public function getLogin()
	{
		$fb = OAuth::consumer('Facebook');

		if(Input::get('code'))
		{
			$token = $fb->requestAccessToken(Input::get('code'));

			$user = json_decode($fb->request('/me'), true);
			$user['oauth_provider'] => 'facebook';
			$user['access_token'] => $token->getAccessToken();
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
		if(Cookie::get('uid'))
		{
			$accessToken = $this->_getAccessTokenByUser(Cookie::get('uid'));
			if($accessToken)
			{
				$token = new OAuth\OAuth2\Token\StdOAuth2Token;
				$token->setAccessToken($accessToken);
				$fb->getStorage()->storeAccessToken('facebook', $token);
				$user = json_decode($fb->request('/me'), true);

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

	private function _getAccessTokenByUser($uid)
	{
		$user = DB::table('users')
			->where('id', '=', Cookie::get('uid'))
			->where('deleted_at', '=', '0000-00-00 00:00:00')
			->select()
			->get();
		if($user) return $user['access_token'];

		return false;
	}

}
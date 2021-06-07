<?php

namespace Libraries\Auth;

class EnbekPassport {

	protected $api = 'https://passport.enbek.kz/api/user';
	protected $appName = null;
	protected $accessKey = null;

	public function user() {
		$user = null;
		$sid = $this->sid();

		if(!is_null($sid)) {
			if(defined('USER_PASSPORT_ENBEK')) {
				$user = json_decode(base64_decode(USER_PASSPORT_ENBEK));
			} else {
				$request = array(
					'appName' => $this->appName,
					'accessKey' => $this->accessKey,
					'sid' => array(
						'name' => isset($sid['name']) ? $sid['name'] : null,
						'value' => isset($sid['value']) ? $sid['value'] : null,
					),
				);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $this->api);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLINFO_HEADER_OUT, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
				$curlResponse = curl_exec($ch);
				$error = curl_error($ch);

				if($error == '') {
					$response = json_decode($curlResponse);
					if(isset($response->user)) {
						$user = $response->user;
						$cacheJson = base64_encode(json_encode($user));
						define('USER_PASSPORT_ENBEK', $cacheJson);
					}
				}
			}
		}

		return $user;
	}

	public function auth() {
		$user = $this->user();
		return isset($user->id);
	}

	public function init($options = array()) {
		if(isset($options['appName'])) { $this->appName = $options['appName']; }
		if(isset($options['accessKey'])) { $this->accessKey = $options['accessKey']; }
	}

	private function sid() {
		$cookies = isset($_COOKIE) ? $_COOKIE : null;
        $sid = null;
        if(is_array($cookies) and !empty($cookies)) {
            foreach($cookies as $cookieName => $cookieValue) {
                if(substr($cookieName, 0, 12) == 'sso_pasport_') {
                    $sid = ['name' => $cookieName, 'value' => $cookieValue];
                    break;
                }
            }
        }
        return $sid;
	}
}

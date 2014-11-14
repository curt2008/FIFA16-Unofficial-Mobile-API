<?php
	
	use Guzzle\Http\Client;
	use Guzzle\Common\Event;
	use Guzzle\Plugin\Cookie\CookiePlugin;
	use Guzzle\Plugin\Cookie\CookieJar\FileCookieJar;
	
	class Mobile_Connector {
		
		private $_sessionDetails = array();
		private $_loginDetails = array();
    		
   		protected $urls = array(
        		'login'         => 'https://accounts.ea.com/connect/auth?client_id=FIFA-15-MOBILE-COMPANION&response_type=code&display=mobile/login&scope=basic.identity+offline+signin&locale=en_GB&prompt=login',
        		'answer'        => 'https://accounts.ea.com/connect/token?grant_type=authorization_code&code=%s&client_id=FIFA-15-MOBILE-COMPANION&client_secret=%s',
        		'gateway'       => 'https://gateway.ea.com/proxy/identity/pids/me',
        		'auth'          => 'https://accounts.ea.com/connect/auth?client_id=FOS-SERVER&redirect_uri=nucleus:rest&response_type=code&access_token=%s',
        		'sid'           => 'https://pas.mob.v7.easfc.ea.com:8095/pow/auth?timestamp=',
        		'utasNucId'     => '/ut/game/fifa15/user/accountinfo?_=',
        		'utasAuth'      => '/ut/auth?timestamp=',
        		'utasQuestion'  => '/ut/game/fifa15/phishing/validate?answer=%s&timestamp=',
        		'utasWatchlist' => '/ut/game/fifa15/watchlist',
    		);
    		
    		protected $clientSecret = 'kbK225TFcqQZqUv9nyQujckCMaPSjqqyNTRUNPUdQWkvjfRGXTGtwuY8d2dVELBHFwGRbbFKNhwAHgET';
    		
    		const RETRY_ON_SERVER_DOWN = 3;
		
		public function __construct($loginDetails) {
        		$this->_loginDetails = $loginDetails;
        		$this->_cookieFile = tempnam(sys_get_temp_dir(), 'FUT15');
        		switch($this->_loginDetails['platform']) {
        			case "XBOX":
        				$this->_sessionDetails['route'] = 'https://utas.fut.ea.com:443';
        			break;
        			case "PS":
        				$this->_sessionDetails['route'] = 'https://utas.s2.fut.ea.com:443';
        			break;
        		}
    		}
    		
    		public function connect($errors = 0) {
        		switch($this->_loginDetails['proxy']['enabled']) {
    				case true:
    					$settings = array('curl.options' => array('CURLOPT_PROXY' => $this->_loginDetails['proxy']['ip'].':'.$this->_loginDetails['proxy']['port'], 'CURLOPT_PROXYTYPE' => 'CURLPROXY_SOCKS5'));
    				break;
    				case false:
    					$settings = array();
    				break;
    			}
    			$client = new Client(null, $settings);
        		if(!file_exists($this->_cookieFile)) {
        			file_put_contents($this->_cookieFile, "");
        		}
        		$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        		$client->addSubscriber($cookiePlugin);
        		$client->setUserAgent("'User-Agent', 'Mozilla/5.0 (Linux; U; Android 4.2.2; de-de; GT-I9195 Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'");
        		$this->_client = $client;
        		try {
            			$url = $this->getLoginUrl();
            			$this->loginAndGetCode($url);
            			$this->enterAnswer();
            			$this->gatewayMe();
            			$this->auth();
            			$this->getSid();
            			$this->utasRefreshNucId();
            			$this->auth();
            			$this->utasAuth();
            			$this->utasQuestion();
        		} catch (\Exception $e) {
            			throw $e;
            			// server down, gotta retry
            			if ($errors < static::RETRY_ON_SERVER_DOWN && preg_match("/service unavailable/mi", $e->getMessage())) {
                			$this->connect(++$errors);
                		} else {
                			throw new \Exception('Could not connect to the mobile endpoint.');
            			}
        		}
        		return array(
				"nucleusId" => $this->nucId,
				"userAccounts" => $this->accounts,
				"sessionId" => $this->sid,
				"phishingToken" => $this->phishingToken,
				"platform" => $this->_loginDetails['platform']
			);
    		}
    		
    		private function getLoginUrl() {
        		$request = $this->_client->get($this->urls['login']);
        		$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
        		$response = $request->send();
        		return $response->getInfo('url');
    		}
    		
    		private function loginAndGetCode($url) {
    			$request = $this->_client->post($url, array(), array(
            			"email" => $this->_loginDetails['username'],
            			"password" => $this->_loginDetails['password'],
            			"_rememberMe" => "on",
            			"rememberMe" => "on",
            			"_eventId" => "submit"
        		));
        		$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
        		$response = $request->send();
        		$this->code = $response->getBody(true);
    		}
    		
    		private function enterAnswer() {
        		$url = sprintf($this->urls['answer'], $this->code, $this->clientSecret);
        		$request = $this->_client->post($url, array());
        		$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
        		$request->addHeader('Content-Type', 'application/x-www-form-urlencoded');
        		$response = $request->send();
        		$json = $response->json();
        		$this->accessToken = $json['access_token'];
    		}
    		
    		private function gatewayMe() {
    			$request = $this->_client->get($this->urls['gateway']);
    			$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
    			$request->addHeader('Authorization', 'Bearer ' . $this->accessToken);
        		$response = $request->send();
        		$json = $response->json();
        		$this->nucId = $json['pid']['pidId'];
        		return $this->nucId;
    		}
		
		private function auth() {
			$url = sprintf($this->urls['auth'], $this->accessToken);
			$request = $this->_client->get($url);
			$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
			$response = $request->send();
			$json = $response->json();
			$this->authCode = $json['code'];
			return $this->authCode;
		}
		
		private function getSid() {
        		$data_array = array(
            			'isReadOnly' => true,
                			'sku' => 'FUT15AND',
                			'clientVersion' 	=> 11,
                			'locale' => 'en-GB',
                			'method'=> 'authcode',
                			'priorityLevel' => 4,
                			'identification'	 => array(
                    			'authCode' => $this->authCode,
                    			'redirectUrl' => 'nucleus:rest'
                			)
                		);
        		$data_string = json_encode($data_array);
        		$request = $this->_client->post($this->urls['sid'], array(), $data_string);
        		$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
        		$response = $request->send();
			$json = $response->json();
			$this->sid = $json['sid'];
			$this->pid = (string) $response->getHeader('X-POW-SID');
			return array("sid" => $this->sid, "pid" => $this->pid);
    		}
    		
    		private function utasRefreshNucId() {
    			$request = $this->_client->get($this->_sessionDetails['route'].$this->urls['utasNucId']);
    			$request->addHeader('Authorization', 'Bearer ' . $this->accessToken);
    			$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
    			$request->addHeader('Easw-Session-Data-Nucleus-Id', $this->nucId);
    			$request->addHeader('X-POW-SID', $this->pid);
    			$response = $request->send();
    			$json = $response->json();
    			$this->accounts = $json;
    			$this->nucId = $json['userAccountInfo']['personas'][0]['personaId'];
    			return $this->nucId;
    		}
    		
    		private function utasAuth() {
        		$data_array = array(
            			'isReadOnly' => true,
                			'sku' => 'FUT15AND',
                			'clientVersion' 	=> 11,
                			'locale' => 'en-GB',
                			'method'=> 'authcode',
                			'priorityLevel' => 4,
                			'identification'	 => array(
                    			'authCode' => $this->authCode,
                    			'redirectUrl' => 'nucleus:rest'
                			),
                			'nucleusPersonaId' => $this->nucId
                		);
        		$data_string = json_encode($data_array);
        		$request = $this->_client->post($this->_sessionDetails['route'].$this->urls['utasAuth'], array(), $data_string);
        		$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
        		$response = $request->send();
			$json = $response->json();
			$this->sid = $json['sid'];
			return $this->sid;
    		}
    		
    		private function utasQuestion() {
    			$url = sprintf($this->_sessionDetails['route'].$this->urls['utasQuestion'], $this->_loginDetails['hash']);
        		$request = $this->_client->post($url);
        		$request->addHeader('x-wap-profile', 'http://wap.samsungmobile.com/uaprof/GT-I9195.xml');
        		$request->addHeader('Easw-Session-Data-Nucleus-Id', $this->nucId);
    			$request->addHeader('X-POW-SID', $this->pid);
    			$request->addHeader('X-UT-SID', $this->sid);
    			$response = $request->send();
    			$json = $response->json();
    			$this->phishingToken = $json['token'];
    			return $this->phishingToken;
    		}
		
	}
	
?>

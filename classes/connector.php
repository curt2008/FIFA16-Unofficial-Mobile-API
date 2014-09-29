<?php

	use Guzzle\Http\Client;
	use Guzzle\Plugin\Cookie\CookiePlugin;
	use Guzzle\Plugin\Cookie\CookieJar\FileCookieJar;

	class Connector {
    
    		private $_loginDetails = array();
    		private $_loginResponse = array();
    
    		private $_client;

    		private $urls = array(
    			"main" => "https://www.easports.com/fifa/ultimate-team/web-app",
    			"nucleus" => "https://www.easports.com/iframe/fut15/?baseShowoffUrl=https%3A%2F%2Fwww.easports.com%2Ffifa%2Fultimate-team%2Fweb-app%2Fshow-off&guest_app_uri=http%3A%2F%2Fwww.easports.com%2Ffifa%2Fultimate-team%2Fweb-app&locale=en_US",
    			"shards" => "https://www.easports.com/iframe/fut15/p/ut/shards?_=",
    			"userinfo" => "https://www.easports.com/iframe/fut15/p/ut/game/fifa15/user/accountinfo?sku=FUT15WEB&_=",
    			"session" => "https://www.easports.com/iframe/fut15/p/ut/auth",
    			"validate" => "https://www.easports.com/iframe/fut15/p/ut/game/fifa15/phishing/validate",
    			"phishing" => "https://www.easports.com/iframe/fut15/p/ut/game/fifa14/phishing/question?_="
    		);

    		public function __construct($loginDetails, $cookie = null) {
        		$this->_loginDetails = $loginDetails;
        		$this->_cookieFile = tmpfile();
    		}

    		public function Connect() {
        		$client = new Client(null);
        		if(!file_exists($this->_cookieFile)) {
        			file_put_contents($this->_cookieFile, "");
        		}
        		$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        		$client->addSubscriber($cookiePlugin);
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
        		$this->_client = $client;
        		$login_url = $this->GetMainPage($this->urls['main']);
        		$this->Login($login_url);
        		$nucleusId = $this->GetNucleusId($this->urls['nucleus']);
        		$this->GetShards($nucleusId, $this->urls['shards']);
        		$userAccounts = $this->GetUserAccounts($nucleusId, $this->urls['userinfo']);
        		$sessionId = $this->GetSessionId($nucleusId, $userAccounts, $this->urls['session']);
        		$phishing = $this->Phishing($nucleusId, $sessionId, $this->urls['phishing']);
        		if(isset($phishing['debug']) && $phishing['debug'] == "Already answered question.") {
            			$phishingToken = $phishing['token'];
        		} else {
            			$phishingToken = $this->Validate($nucleusId, $sessionId, $this->urls['validate']);
        		}
        		$this->_loginResponse = array(
            			"nucleusId" => $nucleusId,
            			"userAccounts" => $userAccounts,
            			"sessionId" => $sessionId,
            			"phishingToken" => $phishingToken,
            			"platform" => $this->_loginDetails['platform']
        		);
        		return $this->_loginResponse;
    		}

    		private function GetMainPage($url) {
        		$request = $this->_client->get($url);
        		$response = $request->send();
        		return $response->getInfo('url');
    		}

    		private function Login($url) {
        		$request = $this->_client->post($url, array(), array(
            			"email" => $this->_loginDetails['username'],
            			"password" => $this->_loginDetails['password'],
            			"_rememberMe" => "on",
            			"rememberMe" => "on",
            			"_eventId" => "submit",
            			"facebookAuth" => ""
        		));
        		$response = $request->send();
    		}

    		private function GetNucleusId($url) {
        		$request = $this->_client->get($url);
        		$response = $request->send();
        		$body = $response->getBody(true);
        		$matches = array();
       	 		preg_match("/var\ EASW_ID = '(\d*)';/", $body, $matches);
        		return $matches[1];
    		}

    		private function GetShards($nucleusId, $url) {
        		$request = $this->_client->get($url . time());
        		$request->addHeader('Easw-Session-Data-Nucleus-Id', $nucleusId);
        		$request->addHeader('X-UT-Embed-Error', 'true');
        		$request->addHeader('X-UT-Route', 'https://utas.fut.ea.com');
        		$request->addHeader('X-Requested-With', 'XMLHttpRequest');
        		$request->setHeader('Accept', 'application/json, text/javascript');
        		$request->setHeader('Accept-Language', 'en-US,en;q=0.8');
        		$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/?baseShowoffUrl=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team%2Fshow-off&guest_app_uri=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team&locale=en_GB');
        		$response = $request->send();
    		}

    		private function GetUserAccounts($nucleusId, $url) {
        		switch($this->_loginDetails['platform']) {
        			case "XBOX":
        				$this->_loginDetails['route'] = 'https://utas.fut.ea.com:443';
        			break;
        			case "PS":
        				$this->_loginDetails['route'] = 'https://utas.s2.fut.ea.com:443';
        			break;
        		}
        		$request = $this->_client->get($url . time());
        		$request->addHeader('Easw-Session-Data-Nucleus-Id', $nucleusId);
        		$request->addHeader('X-UT-Embed-Error', 'true');
        		$request->addHeader('X-UT-Route', $this->_loginDetails['route']);
        		$request->addHeader('X-Requested-With', 'XMLHttpRequest');
        		$request->setHeader('Accept', 'application/json, text/javascript');
        		$request->setHeader('Accept-Language', 'en-US,en;q=0.8');
        		$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/?baseShowoffUrl=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team%2Fshow-off&guest_app_uri=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team&locale=en_GB');
        		$response = $request->send();
        		return $response->json();
    		}

    		private function GetSessionId($nucleusId, $userAccounts, $url) {
        		$persona = array();
        		$lastAccessTime = array();

        		foreach ($userAccounts['userAccountInfo']['personas'][0]['userClubList'] as $key) {
            			$persona[] = $key;
        		}

        		foreach ($persona as $key) {
            			$lastAccessTime[] = $key['lastAccessTime'];
        		}

        		$latestAccessTime = max($lastAccessTime);
        		$lastUsedPersona  = $persona[array_search($latestAccessTime, $lastAccessTime)];

        		$personaId = $userAccounts['userAccountInfo']['personas'][0]['personaId'];
        		$personaName = $userAccounts['userAccountInfo']['personas'][0]['personaName'];
        		$platform = $this->getNucleusPlatform($this->_loginDetails['platform']);

        		$data_array = array(
            			'isReadOnly' => false,
            			'sku' => 'FUT15WEB',
            			'clientVersion' => 1,
            			'nuc' => $nucleusId,
            			'nucleusPersonaId' => $personaId,
            			'nucleusPersonaDisplayName' => $personaName,
            			'nucleusPersonaPlatform' => $platform,
            			'locale' => 'en-GB',
            			'method' => 'authcode',
            			'priorityLevel' => 4,
            			'identification' => array('authCode' => '')
            		);
        		$data_string = json_encode($data_array);
        		$request = $this->_client->post($url, array(), $data_string);
        		$request->addHeader('Easw-Session-Data-Nucleus-Id', $nucleusId);
        		$request->addHeader('X-UT-Embed-Error', 'true');
        		$request->addHeader('X-UT-Route', $this->_loginDetails['route']);
        		$request->addHeader('X-Requested-With', 'XMLHttpRequest');
        		$request->setHeader('Accept', 'application/json, text/javascript');
        		$request->setHeader('Accept-Language', 'en-US,en;q=0.8');
        		$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/?baseShowoffUrl=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team%2Fshow-off&guest_app_uri=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team&locale=en_GB');
        		$request->setHeader('Content-Type', 'application/json');
        		$request->setHeader('Content-Length', strlen($data_string));
        		$response = $request->send();
        		$sessionId = $response->json();
        		return $sessionId['sid'];
    		}

    		private function Phishing($nucleusId, $sessionId, $url) {
        		$request = $this->_client->get($url . time());
        		$request->addHeader('Easw-Session-Data-Nucleus-Id', $nucleusId);
        		$request->addHeader('X-UT-Embed-Error', 'true');
        		$request->addHeader('X-UT-Route', $this->_loginDetails['route']);
        		$request->addHeader('X-UT-SID', $sessionId);
        		$request->addHeader('X-Requested-With', 'XMLHttpRequest');
        		$request->setHeader('Accept', 'application/json, text/javascript');
        		$request->setHeader('Accept-Language', 'en-US,en;q=0.8');
        		$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/?baseShowoffUrl=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team%2Fshow-off&guest_app_uri=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team&locale=en_GB');
        		$response = $request->send();
        		return $response->json();
    		}

    		private function Validate($nucleusId, $sessionId, $url) {
        		$data_string = "answer=" . $this->_loginDetails['hash'];
        		$request = $this->_client->post($url, array(), $data_string);
        		$request->addHeader('X-UT-SID', $sessionId);
        		$request->addHeader('X-UT-Route', $this->_loginDetails['route']);
        		$request->addHeader('Easw-Session-Data-Nucleus-Id', $nucleusId);
        		$request->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        		$request->setHeader('Content-Length', strlen($data_string));
        		$response = $request->send();
        		$json = $response->json();
        		return $json['token'];
    		}

    		private function getNucleusPlatform($platform) {
        		switch ($platform) {
            			case "PS":
                				return "PS3";
                			break;
            			case "XBOX":
                				return "360";
                			break;
        		}
    		}
	}

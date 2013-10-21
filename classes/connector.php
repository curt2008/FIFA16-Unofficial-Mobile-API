<?php

	use Guzzle\Http\Client;
	use Guzzle\Plugin\Cookie\CookiePlugin;
	use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

	class Connector {
    
    		private $_loginDetails = array();
    		private $_loginResponse = array();
    
    		private $_client;

    		private $MainPageURL = "http://www.easports.com/uk/fifa/football-club/ultimate-team";
    		private $LoginURL = "https://www.easports.com/services/authenticate/login";
    		private $NucleusIdURL = "http://www.easports.com/iframe/fut/?locale=en_GB&baseShowoffUrl=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team%2Fshow-off&guest_app_uri=http%3A%2F%2Fwww.easports.com%2Fuk%2Ffifa%2Ffootball-club%2Fultimate-team";
    		private $ShardsURL = "http://www.easports.com/iframe/fut/p/ut/shards?_=";
    		private $UserAccountsURL = "http://www.easports.com/iframe/fut/p/ut/game/fifa14/user/accountinfo?_=";
    		private $SessionIdURL = "http://www.easports.com/iframe/fut/p/ut/auth";
    		private $ValidateURL = "http://www.easports.com/iframe/fut/p/ut/game/fifa14/phishing/validate";
    		private $PhishingURL = "http://www.easports.com/iframe/fut/p/ut/game/fifa14/phishing/question?_=";

    		public function __construct($loginDetails) {
        		$this->_loginDetails = $loginDetails;
    		}

    		public function Connect() {
        		$client = new Client(null);
        		$cookiePlugin = new CookiePlugin(new ArrayCookieJar());
        		$client->addSubscriber($cookiePlugin);
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
        		$this->_client = $client;
        		$login_url = $this->GetMainPage($this->MainPageURL);
        		$this->Login($login_url);
        		$nucleusId = $this->GetNucleusId($this->NucleusIdURL);
        		$this->GetShards($nucleusId, $this->ShardsURL);
        		$userAccounts = $this->GetUserAccounts($nucleusId, $this->UserAccountsURL);
        		$sessionId = $this->GetSessionId($nucleusId, $userAccounts, $this->SessionIdURL);
        		$phishing = $this->Phishing($nucleusId, $sessionId, $this->PhishingURL);
        		if(isset($phishing['debug']) && $phishing['debug'] == "Already answered question.") {
            			$phishingToken = $phishing['token'];
        		} else {
            			$phishingToken = $this->Validate($nucleusId, $sessionId, $this->ValidateURL);
        		}
        		$this->_loginResponse = array(
            			"nucleusId" => $nucleusId,
            			"userAccounts" => $userAccounts,
            			"sessionId" => $sessionId,
            			"phishingToken" => $phishingToken,
            			"cookies" => $cookiePlugin,
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
        		if ($this->_loginDetails['platform'] == "xbox360") {
            			$this->_loginDetails['route'] = 'https://utas.fut.ea.com:443';
        		} else {
            			$this->_loginDetails['route'] = 'https://utas.s2.fut.ea.com:443';
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
                    'sku' => 'FUT14WEB',
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
                    case "ps3":
                        return "ps3";
                    case "xbox360":
                        return "360";
                    case "pc":
                        return "pc";
                    default:
                        return "360";
                }  
            }
            
    }
?>
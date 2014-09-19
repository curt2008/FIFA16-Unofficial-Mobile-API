<?

	use Guzzle\Http\Client;
	use Guzzle\Plugin\Cookie\CookiePlugin;
	use Guzzle\Plugin\Cookie\CookieJar\FileCookieJar;
 
	class Searchor {
  
  		private $nuc;
		private $sess;
		private $phish;
		private $_cookieFile;
	
		//initialise the class
		public function __construct($data) {
			$this->nuc 	= $data['nuc'];
			$this->sess 	= $data['sess'];
			$this->phish 	= $data['phish'];
			$this->console  = $data['console'];
			if($data['cookie']) {
				$this->_cookieFile = $data['cookie'];
			}		
		}
		
		public function getCredits() {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			switch($this->console) {
				case "XBOX":
					$request = $client->post("https://utas.fut.ea.com/ut/game/fifa15/user/credits");
				break;
				case "PS":
					$request = $client->post("https://utas.s2.fut.ea.com/ut/game/fifa15/user/credits");
				break;
			}
 			$request->addHeader('Origin', 'http://www.easports.com');
			$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
			$request->addHeader('X-HTTP-Method-Override', 'GET');
			$request->addHeader('X-UT-Embed-Error', 'true');
			$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
			$request->addHeader('X-UT-SID', $this->sess);	
			$response = $request->send();
			$json = $response->json();
			return $json;
		}
		
		public function tradeInfo($tradeId) {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			switch($this->console) {
				case "XBOX":
					$request = $client->post("https://utas.fut.ea.com/ut/game/fifa15/trade/status?tradeIds=".$tradeId);
				break;
				case "PS":
					$request = $client->post("https://utas.s2.fut.ea.com/ut/game/fifa15/trade/status?tradeIds=".$tradeId);
				break;
			}
 			$request->addHeader('Origin', 'http://www.easports.com');
			$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
			$request->addHeader('X-HTTP-Method-Override', 'GET');
			$request->addHeader('X-UT-Embed-Error', 'true');
			$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
			$request->addHeader('X-UT-SID', $this->sess);	
			$response = $request->send();
			$json = $response->json();
			return $json;
		}
		
		public function playerSearch($playerId = 0, $minbin = 0, $maxbin = 0, $start = 0, $num = 16) {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
        		switch($this->console) {
				case "XBOX":
					$url = "https://utas.fut.ea.com/ut/game/fifa15/transfermarket?";
				break;
				case "PS":
					$url = "https://utas.s2.fut.ea.com/ut/game/fifa15/transfermarket?";
				break;
			}
			$searchstring = "";
                	if ($minbin > 0){
                        	$searchstring .= "&minb=".$minbin;
                	}
                	if ($maxbin > 0){
                        	$searchstring .= "&maxb=".$maxbin;
                	}
                	if ($playerId > 0) {
                		$searchstring .= "&maskedDefId=".$playerId;
                	}
                	
                	$search = $url . "type=player&start=$start&num=$num".$searchstring;
			$request = $client->post($search);
 			$request->addHeader('Origin', 'http://www.easports.com');
			$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
			$request->addHeader('X-HTTP-Method-Override', 'GET');
			$request->addHeader('X-UT-Embed-Error', 'true');
			$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
			$request->addHeader('X-UT-SID', $this->sess);	
			$response = $request->send();
			$json = $response->json();
			return $json;
		}
							
	}
	
?>

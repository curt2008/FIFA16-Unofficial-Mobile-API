<?

	use Guzzle\Http\Client;
	use Guzzle\Plugin\Cookie\CookiePlugin;
	use Guzzle\Plugin\Cookie\CookieJar\FileCookieJar;
	
	class Trade {
		
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
		
		public function Bid($tradeid,$bid) {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			switch($this->console) {
				case "XBOX":
					$url = "https://utas.fut.ea.com/ut/game/fifa15/trade/".$tradeid."/bid";
				break;
				case "PS":
					$url = "https://utas.s2.fut.ea.com/ut/game/fifa15/trade/".$tradeid."/bid";
				break;
			}		
			$data_array = array('bid' => $bid);
        		$data_string = json_encode($data_array);
        		$request = $client->post($url, array(), $data_string);
 			$request->addHeader('Origin', 'http://www.easports.com');
			$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
			$request->addHeader('X-HTTP-Method-Override', 'PUT');
			$request->addHeader('X-UT-Embed-Error', 'true');
			$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
			$request->addHeader('X-UT-SID', $this->sess);
			$request->setHeader('Content-Type', 'application/json');
			$request->addHeader('Content-Length', strlen($data_string));				
			$response = $request->send();
			$json = $response->json();
			return $json;
		}
		
		public function listAuction($id,$bin,$startingBid,$duration) {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			switch($this->console) {
				case "XBOX":
					$url = "https://utas.fut.ea.com/ut/game/fifa15/auctionhouse";
				break;
				case "PS":
					$url = "https://utas.s2.fut.ea.com/ut/game/fifa15/auctionhouse";
				break;
			}			
			$selldata = array(
				"itemData" => array( "id" => $id), 
				"buyNowPrice" => $bin, 
				"startingBid" => $startingBid, 
				"duration" => $duration
			);	
        		$data_string = json_encode($selldata);
        		$request = $client->post($url, array(), $data_string);
 			$request->addHeader('Origin', 'http://www.easports.com');
			$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
			$request->addHeader('X-HTTP-Method-Override', 'POST');
			$request->addHeader('X-UT-Embed-Error', 'true');
			$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
			$request->addHeader('X-UT-SID', $this->sess);
			$request->setHeader('Content-Type', 'application/json');
			$request->addHeader('Content-Length', strlen($data_string));				
			$response = $request->send();
			$json = $response->json();
			return $json;
		}
		
		public function sendToTradePile($cardid) {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			switch($this->console) {
				case "XBOX":
					$url = "https://utas.fut.ea.com/ut/game/fifa15/item";
				break;
				case "PS":
					$url = "https://utas.s2.fut.ea.com/ut/game/fifa15/item";
				break;
			}			
			$data_array = array(
				"itemData" => array(
					array(
						"id" => $cardid, 
						"pile" => "trade"
					)
				)
			);
        		$data_string = json_encode($data_array);
        		$request = $client->post($url, array(), $data_string);
 			$request->addHeader('Origin', 'http://www.easports.com');
			$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
			$request->addHeader('X-HTTP-Method-Override', 'PUT');
			$request->addHeader('X-UT-Embed-Error', 'true');
			$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
			$request->addHeader('X-UT-SID', $this->sess);
			$request->setHeader('Content-Type', 'application/json');
			$request->addHeader('Content-Length', strlen($data_string));				
			$response = $request->send();
			$json = $response->json();
			return $json;
		}
		
		public function getTradePile() {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			switch($this->console) {
				case "PS":
					$request = $client->post("https://utas.s2.fut.ea.com/ut/game/fifa15/tradepile");
				break;
				case "XBOX":
					$request = $client->post("https://utas.fut.ea.com/ut/game/fifa15/tradepile");
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
		
		public function removeSold($id) {
			$client = new Client(null);
			if($this->_cookieFile) {
				$cookiePlugin = new CookiePlugin(new FileCookieJar($this->_cookieFile));
        			$client->addSubscriber($cookiePlugin);
			}
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			switch($this->console) {
				case "XBOX":
					$url = "https://utas.fut.ea.com/ut/game/fifa15/trade/".$id;
				break;
				case "PS":
					$url = "https://utas.s2.fut.ea.com/ut/game/fifa15/trade/".$id;
				break;
			}			
        		$request = $client->post($url);
 			$request->addHeader('Origin', 'http://www.easports.com');
			$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
			$request->addHeader('X-HTTP-Method-Override', 'DELETE');
			$request->addHeader('X-UT-Embed-Error', 'true');
			$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
			$request->addHeader('X-UT-SID', $this->sess);
			$request->setHeader('Content-Type', 'application/json');				
			$response = $request->send();
			$json = $response->json();
			return $json;
		}
		
	}

?>

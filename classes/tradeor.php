<?

	use Guzzle\Http\Client;
	use Guzzle\Plugin\Cookie\CookiePlugin;
	use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
	
	class Trade {
		
		private $nuc;
		private $sess;
		private $phish;
	
		//initialise the class
		public function __construct($nuc, $sess, $phish) {
			$this->nuc 	= $nuc;
			$this->sess 	= $sess;
			$this->phish 	= $phish;
						
		}
		
		public function Bid($tradeid,$bid) {
			$client = new Client(null);
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			$url = "https://utas.s2.fut.ea.com/ut/game/fifa14/trade/".$tradeid."/bid";			
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
		
	}

?>
<?

	use Guzzle\Http\Client;
	use Guzzle\Plugin\Cookie\CookiePlugin;
	use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
 
	class Searchor {
  
  		private $nuc;
		private $sess;
		private $phish;
	
		//initialise the class
		public function __construct($nuc, $sess, $phish) {
			$this->nuc 	= $nuc;
			$this->sess 	= $sess;
			$this->phish 	= $phish;
						
		}
		
		public function Playersearch($macr,$micr,$num,$cat,$playStyle,$pos,$team,$league,$nation,$start,$lev,$minb,$maxb) {
			$client = new Client(null);
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			$url = "https://utas.s2.fut.ea.com/ut/game/fifa14/transfermarket?";
			$searchstring = "";
			if ($level != "" && $level != "any"){
                        	$searchstring .= "&lev=$level";
               		}
                	if ($pos != "" && $pos != "any"){
                        	if ($pos == "defense" || $pos == "midfield" || $pos == "attacker"){
                                	$searchstring .= "&zone=$pos";
                        	}else{
                                	$searchstring .= "&pos=$pos";
                        	}
                	}
                	if ($nation > 0){
                        	$searchstring .= "&nat=$nation";
                	}
                	if ($league > 0){
                        	$searchstring .= "&leag=$league";
                	}
                	if ($team > 0){
                        	$searchstring .= "&team=$team";
                	}
                	if ($micr > 0){
                        	$searchstring .= "&micr=$micr";
                	}
                	if ($macr > 0){
                        	$searchstring .= "&macr=$macr";
                	}
                	if ($minb > 0){
                        	$searchstring .= "&minb=$minb";
                	}
                	if ($maxb > 0){
                        	$searchstring .= "&maxb=$maxb";
                	}
                	$search = $url . "type=player&start=$start&num=$num" . $searchstring;
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
		
		public function Consumables($macr,$micr,$num,$cat,$start,$lev,$minb,$maxb) {
			$client = new Client(null);
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			$url = "https://utas.s2.fut.ea.com/ut/game/fifa14/transfermarket?";
			$searchstring = "";
			if ($lev != "" && $lev != "any"){
				$searchstring .= "&lev=$lev";
			}
			if ($cat != "" && $cat != "any"){
				$searchstring .= "&cat=$cat";
			}
			if ($micr > 0){
				$searchstring .= "&micr=$micr";//min bid
			}
			if ($macr > 0){
				$searchstring .= "&macr=$macr";//max bid
			}
			if ($minb > 0){
				$searchstring .= "&minb=$minb";
			}
			if ($maxb > 0){
				$searchstring .= "&maxb=$maxb";
			}
			$search = $url . "type=development&blank=10&start=$start&num=$num" . $searchstring;
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
			
		public function Chemistry($macr,$micr,$num,$cat,$start,$lev,$minb,$maxb,$style) {
			$client = new Client(null);
        		$client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");	
			$url = "https://utas.s2.fut.ea.com/ut/game/fifa14/transfermarket?";
			$searchstring = "";
			if ($lev != "" && $lev != "any"){
				$searchstring .= "&lev=$lev";
			}
			if ($cat != "" && $cat != "any"){
				$searchstring .= "&cat=$cat";
			}
			if ($micr > 0){
				$searchstring .= "&micr=$micr";//min bid
			}
			if ($macr > 0){
				$searchstring .= "&macr=$macr";//max bid
			}
			if ($minb > 0){
				$searchstring .= "&minb=$minb";
			}
			if ($maxb > 0){
				$searchstring .= "&maxb=$maxb";
			}
			$search = $url . "type=training&playStyle=$style&start=$start&num=$num" . $searchstring;
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
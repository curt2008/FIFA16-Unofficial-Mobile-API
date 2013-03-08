<?PHP
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 24/09/12
* @version - 1.0
**/
class Tradeor {
	
	private $EASW_KEY;
	private $EASF_SESS;
	private $PHISHKEY;
	private $XSID;
	
	//initialise the class
	public function __construct($EASW_KEY, $EASF_SESS, $PHISHKEY, $XSID) {
		$this->EASW_KEY 	= $EASW_KEY;
		$this->EASF_SESS 	= $EASF_SESS;
		$this->PHISHKEY 	= $PHISHKEY;
		$this->XSID 		= $XSID;
	}
	
	//$trade is the tradeID for the item you want
	//$value is the value in FIFA coins that you want to BID
	public function bid($trade, $value){
		//URL to bid on trade items
		$bidurl = "https://utas.fut.ea.com/ut/game/fifa13/trade/". $trade . "/bid";
		
		//JSON data to send as a POST item
		$data = array("bid" => $value);
		$data_string = json_encode($data); 
		//Set the cookie data
		$cookie_string = $this->EASW_KEY ."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($bidurl);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'x-http-method-override: PUT',
			'Content-Length: ' . strlen($data_string),
			$this->XSID)                                                                       
		);
		
		//Contains the JSON file returned from EA
		$EABID = curl_exec($ch);
		curl_close($ch);
		
		unset ($ch, $cookie_string, $data_string, $data, $trade, $bidurl, $value);
		
		return $EABID;
	}
	
	public function trade($trade){
		//URL to view trade details
		$tradeurl = "https://utas.fut.ea.com/ut/game/fifa13/trade?tradeIds=". $trade;

		//Set the cookie data
		$cookie_string = $this->EASW_KEY ."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($tradeurl);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                     
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'x-http-method-override: GET',
			$this->XSID)                                                                       
		);
		
		//Contains the JSON file returned from EA
		$EATRADE = curl_exec($ch);
		curl_close($ch);
		
		unset($ch, $cookie_string, $trade, $tradeurl);
		
		return $EATRADE;
	}
}
?>

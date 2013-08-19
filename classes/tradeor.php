<?php

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
		$bidurl = "https://utas.fut.ea.com/ut/game/fifa13/trade/". $trade ."/bid";
		
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
	
	public function moveCard($item, $pile = "trade"){
		//URL to view an item 
		$url = "https://utas.fut.ea.com/ut/game/fifa13/item";

		//JSON data to send as a POST item
		$data = array("itemData" => array(array("id" => $item, "pile" => $pile)));
		$data_string = json_encode($data); 
		//Set the cookie data
		$cookie_string = $this->EASW_KEY ."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($url);                                                                      
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
		$EAFUNC = curl_exec($ch);
		curl_close($ch);

		unset ($ch, $cookie_string, $data_string, $data, $item, $url, $pile);

		return $EAFUNC;
	}
	
	public function tradepile(){
		$url = "https://utas.fut.ea.com/ut/game/fifa13/tradepile";
		
		//Set the cookie data
		$cookie_string = $this->EASW_KEY."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-http-method-override: GET', $this->XSID));
		
		//Contains the JSON file returned from EA
		$EAPILE = curl_exec($ch);
		$decoded = json_decode($EAPILE, true);
		$COUNT = count($decoded['auctionInfo']);
		
		curl_close($ch);
		
		unset ($ch, $cookie_string);
		
		//return array for simple access to JSON response and total results count
		$array = array('JSON' => $decoded, 'COUNT' => $COUNT);
		
		return $array;
	}
	
	public function quickSell($item){
		//URL to view an item 
		$url = "https://utas.fut.ea.com/ut/game/fifa13/item/".$item;
		
		//Set the cookie data
		$cookie_string = $this->EASW_KEY ."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($url);                              
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'x-http-method-override: DELETE',
			$this->XSID)                                                                       
		);
		
		//Contains the JSON file returned from EA
		$EAFUNC = curl_exec($ch);
		curl_close($ch);
		
		unset ($ch, $cookie_string, $url, $item);
		
		return $EAFUNC;
	}
	
	public function listItem($item, $startBid, $BIN, $duration){
		//URL for the auction house
		$url = "https://utas.fut.ea.com/ut/game/fifa13/auctionhouse";
		
		//JSON data to send as a POST item
		$data = array('itemData' => array('id' => $item), 'buyNowPrice' => $BIN, 'duration' => $duration, 'startingBid' => $startBid);
		$data_string = json_encode($data); 
		//Set the cookie data
		$cookie_string = $this->EASW_KEY ."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'x-http-method-override: POST',
			'Content-Length: ' . strlen($data_string),
			$this->XSID)                                                                       
		);
		
		//Contains the JSON file returned from EA
		$EAFUNC = curl_exec($ch);
		curl_close($ch);
		
		unset ($ch, $cookie_string, $data_string, $data, $url, $item, $startBid, $BIN, $duration);
		
		return $EAFUNC;
	}
	
	public function clearfrompile($trade){
		//URL to view an item 
		$url = "https://utas.fut.ea.com/ut/game/fifa13/trade/".$trade;
		
		//Set the cookie data
		$cookie_string = $this->EASW_KEY ."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($url);                              
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'x-http-method-override: DELETE',
			$this->XSID)                                                                       
		);
		
		//Contains the JSON file returned from EA
		$EAFUNC = curl_exec($ch);
		curl_close($ch);
		
		unset ($ch, $cookie_string, $url, $item);
		
		return $EAFUNC;
	}
	
	public function relistItems(){
		$tradepile = $this->tradepile();
		$i = 0;
		while ($i < $tradepile['COUNT']) {
				switch($tradepile['JSON']['auctionInfo'][$i]['tradeState']) {
					case "expired":
						$this->listItem($tradepile['JSON']['auctionInfo'][$i]['itemData']['id'], $tradepile['JSON']['auctionInfo'][$i]['startingBid'], $tradepile['JSON']['auctionInfo'][$i]['buyNowPrice']);	
					break;
					default:
						$this->clearfrompile($tradepile['JSON']['auctionInfo'][$i]['tradeId']);	
					break;
				}
			$i++;
		}
	}
}
?>

<?PHP
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 24/09/12
* @version - 1.0
**/
class Searchor {
	
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
	
	public function playersearch($start = 0,$count = 15,$level,$formation,$position,$nationality,$league,$team,$minBid,$maxBid,$minBIN,$maxBIN){
		//URL to search for items
		$searchurl = "https://utas.fut.ea.com/ut/game/fifa13/auctionhouse?";
		//String that holds our search variables
		$searchstring = "";
		
		//Add to the search string based on the variables passed
		if ($level != "" && $level != "any"){
			$searchstring .= "&lev=$level";
		}
		
		if ($formation != "" && $formation != "any"){
			$searchstring .= "&form=$formation";
		}
		
		if ($position != "" && $position != "any"){
			if ($position == "defense" || $position == "midfield" || $position == "attacker"){
				$searchstring .= "&zone=$position";
			}else{
				$searchstring .= "&pos=$position";
			}
		}
		
		if ($nationality > 0){
			$searchstring .= "&nat=$nationality";
		}
		
		if ($league > 0){
			$searchstring .= "&leag=$league";
		}
		
		if ($team > 0){
			$searchstring .= "&team=$team";
		}
		
		if ($minBid > 0){
			$searchstring .= "&micr=$minBid";
		}
		
		if ($maxBid > 0){
			$searchstring .= "&macr=$maxBid";
		}
		
		if ($minBIN > 0){
			$searchstring .= "&minb=$minBIN";
		}
		
		if ($maxBIN > 0){
			$searchstring .= "&maxb=$maxBIN";
		}
		
		
		//create the final search string
		$search = $searchurl . "type=player&start=$start&num=$count" . $searchstring;
		//Set the cookie data
		$cookie_string = $this->EASW_KEY."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($search);                                                                      
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
		$EAPSEARCH = curl_exec($ch);
		curl_close($ch);
		
		unset ($start,$count,$level,$formation,$position,$nationality,$league,$team,$minBid,$maxBid,$minBIN,$maxBIN, $ch, $cookie_string, $search, $searchstring);
		
		return $EAPSEARCH;
	}
	
	public function staffsearch($start = 0,$count = 15, $level, $cat, $minBid, $maxBid, $minBIN, $maxBIN){
		//URL to search for items
		$searchurl = "https://utas.fut.ea.com/ut/game/fifa13/auctionhouse?";
		//String that holds our search variables
		$searchstring = "";
		
		//Add to the search string based on the variables passed
		if ($level != "" && $level != "any"){
			$searchstring .= "&lev=$level";
		}
		
		if ($cat != "" && $cat != "any"){
			$searchstring .= "&cat=$cat";
		}
		
		if ($minBid > 0){
			$searchstring .= "&micr=$minBid";
		}
		
		if ($maxBid > 0){
			$searchstring .= "&macr=$maxBid";
		}
		
		if ($minBIN > 0){
			$searchstring .= "&minb=$minBid";
		}
		
		if ($maxBIN > 0){
			$searchstring .= "&maxb=$maxBid";
		}
		
		
		//create the final search string
		$search = $searchurl . "type=staff&blank=10&start=$start&num=$count" . $searchstring;
		//Set the cookie data
		$cookie_string = $this->EASW_KEY."; ".$this->EASF_SESS ."; ".$this->PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($search);                                                                      
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
		$EASSEARCH = curl_exec($ch);
		curl_close($ch);
		
		unset ($start,$count,$level,$cat,$minBid,$maxBid,$minBIN,$maxBIN, $ch, $cookie_string, $search, $searchstring);
		
		return $EASSEARCH;
	}
}
?>
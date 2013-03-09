<?PHP
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 25/09/12
* @version - 2.0
**/

class Functionor {

	//initialise the class
	public function __construct() {
	
	}

	//returns the base ID of a player from the resource ID provided
	public function baseID($resourceid){
		$rid = $resourceid;
		$version = 0;
		
		WHILE ($rid > 16777216){
			$version++;
			if ($version == 1){
				//the constant applied to all items
				$rid -= 1342177280;
			}elseif ($version == 2){
				//the value added to the first updated version
				$rid -= 50331648;
			}else{
				//the value added on all subsequent versions
				$rid -= 16777216;
			}
		}
		
		$returnable = array('baseID'=>$rid,'version'=>$version);
		return $returnable;
	}

	//returns the JSON file containing the players information
	public function playerinfo($baseID){
		$playerurl = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2013/fut/items/web/". $baseID .".json";
		$EAPLAYER = file_get_contents($playerurl, false);
		$decoded = json_decode($EAPLAYER, true);
		return $decoded;
	}

	//returns the URL of the players image
	public function playerimage($baseID){
		$EAPIMAGE = "http://fh13.fhcdn.com/static/img/players/fifa13/". $baseID .".png";
		
		return $EAPIMAGE;
	}

	//returns the JSON file containing the managers information
	public function managerinfo($assetID){
		$managerurl = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2013/fut/items/web/". $assetID .".json";
		$EAMANAGER = file_get_contents($managerurl, false);
		
		return $EAMANAGER;
	}

	//returns the URL of the managers image
	public function managerimage($assetID){
		$EAMIMAGE = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2013/fut/items/images/players/web/heads_staff_". $assetID .".png";
		
		return $EAMIMAGE;
	}

	//returns the URL of the countries flag
	public function flagimage($assetID){
		$EAMIMAGE = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2013/fut/items/images/cardflags/web/". $assetID .".png";
		
		return $EAMIMAGE;
	}

	//returns the URL of the countries flag
	public function flagswfimage($assetID){
		$EAMIMAGE = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2013/fut/items/images/cardflagssmall/web/Flag_". $assetID .".swf";
		
		return $EAMIMAGE;
	}

	//returns the URL of the clubs badge
	public function clubimage($assetID){
		$EAMIMAGE = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2013/fut/items/images/clubbadges/web/s". $assetID .".png";
		
		return $EAMIMAGE;
	}

	//returns the JSON file containing how many coins your account has to spend	
	public function credits($EASW_KEY, $EASF_SESS, $PHISHKEY, $XSID){
		//URL to retrieve credits
		$creditsurl = "https://utas.fut.ea.com/ut/game/fifa13/user/credits";
		
		//Set the cookie data
		$cookie_string = $EASW_KEY."; ".$EASF_SESS ."; ".$PHISHKEY;                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($creditsurl);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                     
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'x-http-method-override: GET',
			$XSID)                                                                       
		);
		
		//Contains the JSON file returned from EA
		$EACREDITS = curl_exec($ch);
		curl_close($ch);
		
		unset ($ch, $cookie_string, $trade, $tradeurl, $value);
		
		return $EACREDITS;
	}
	
	public function tradepile(){
		$url = "https://utas.fut.ea.com/ut/game/fifa13/tradepile";
		
		//Set the cookie data
		$cookie_string = $_SESSION['EASW_KEY']."; ".$_SESSION['EASF_SESS'] ."; ".$_SESSION['PHISHKEY'];                                                                                       
		//Setup cURL HTTP request
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-http-method-override: GET', $_SESSION['XSID']));
		
		//Contains the JSON file returned from EA
		$EAPILE = curl_exec($ch);
		
		curl_close($ch);
		
		unset ($ch, $cookie_string);
		
		return $EAPILE;
	}

	//Return the type of card we have
	public function cardtype($rating, $rare){

		$type = "Gold";
		//Set the Colour based on the player rating
		if ($rating <= 64){
			$type = "Bronze";
		}elseif ($rating <= 74){
			$type = "Silver";
		}
		
		//Set the Catagory based on the player rareity
		switch($rare):
			case 1:
				$type .= " Shiny";
				break;
			case 3:
				$type .= " InForm";
				break;
			case 5:
				$type .= " TOTY";
				break;
			case 6:
				$type .= " TOTY";
				break;
			case 8:
				$type .= " MOTM";
		endswitch;
		
		//Return the Card Type
		return $type;
	}
	
	public function eatax($bought, $sold) {
		return $sold * 0.95 - $bought;
	}
}
?>

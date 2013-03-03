<?PHP

class Connector {
	
	private $user;
	private $password;
	private $hash;
	
	//initialise the class
	public function __construct($user, $password, $hash) {
		$this->user 	= $user;
		$this->password = $password;
		$this->hash 	= $hash;
	}
	
	public function connect(){
		$ulocal = "en_GB";
		//displayname for auth string
		$dispname = "bot";
		//locale for auth string
		$locale = "en-GB";
		//Time now in milliseconds
		$time = time();

		//The first 2 EA URLs we need to call in this order
		$login	= "https://www.ea.com/uk/football/services/authenticate/login";
		$shard  = "http://www.ea.com/p/fut/a/card/l/". $ulocal ."/s/p/ut/shards?timestamp=". $time;
		
		
		//POST data to send
		$data_string = "email=".$this->user."&password=".$this->password;
		//Setup cURL HTTP request
		$ch = curl_init($login);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/x-www-form-urlencoded',                                                                                
			'Content-Length: ' . strlen($data_string),
			'Referer: http://www.ea.com/uk/football/')                                                                   
		);                                                                                                                   

		$response = curl_exec($ch);
		
		curl_close($ch);
		//Split the HEADERS and BODY 
		list($h, $EALOGIN) = explode("\r\n\r\n", $response, 2);
		$r = explode("\r\n", $h);
		
		//EASW Key
		$s = explode(":", $r[10]);
		$t = explode(";", $s[1]);
		$EASW_KEY = $t[0]; 
		//Session Key
		$m = explode(":", $r[11]);
		$n = explode(";", $m[1]);
		$EASF_SESS = $n[0];
		//nuc
		$a = explode("<nucleusId>", $EALOGIN);
		$b = explode("</nucleusId>", $a[1]);
		$NUC = $b[0];

		//display the keys that we've found
		//echo $EASW_KEY.   "<br />";
		//echo $EASF_SESS.  "<br />";
		//echo "NUC: ".$NUC."<br />";

		//unset the variables used in this section as we will use them again
		unset($EALOGIN, $ch, $r, $s, $t, $m, $n, $a, $b, $data_string, $response);
		
		//Cookie Data includes the two keys from above
		$cookie_string = $EASW_KEY."; ".$EASF_SESS;
		//Setup cURL HTTP request
		$ch = curl_init($shard);                                                                                                                                      
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/x-www-form-urlencoded')                                                                   
		);                                                                                                                   

		$EAACCOUNT = curl_exec($ch);
		curl_close($ch);
		
		//Get machine type
		$d = json_decode($EAACCOUNT);
		$machine = $d->shardInfo[0]->customdata1[0];
		
		//display the variables we've got
		//echo "machine: ".$machine."<br />";
		
		unset($EAACCOUNT, $d, $ch, $cookie_string);
		
		//Having gotten the data from the SHARD URL here are the 3 remaining EA URLs we will use
		$account= "http://www.ea.com/p/fut/a/" . $machine . "/l/". $ulocal ."/s/p/ut/game/fifa13/user/accountinfo?timestamp=". $time;
		$auth	= "http://www.ea.com/p/fut/a/" . $machine . "/l/". $ulocal ."/s/p/ut/auth";
		$quest	= "http://www.ea.com/p/fut/a/" . $machine . "/l/". $ulocal ."/s/p/ut/game/fifa13/phishing/validate";
		
		
		//Cookie Data includes the two keys from above
		$cookie_string = $EASW_KEY."; ".$EASF_SESS;
		//Setup cURL HTTP request
		$ch = curl_init($account);                                                                                                                                      
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/x-www-form-urlencoded')                                                                   
		);                                                                                                                   

		$EAACCOUNT = curl_exec($ch);
		curl_close($ch);
		
		//Get personaID and Platform
		$d = json_decode($EAACCOUNT);
		$personaID = $d->userAccountInfo->personas[0]->personaId;
		$platform  = $d->userAccountInfo->personas[0]->userClubList[0]->platform;

		//display the variables we've got
		//echo "personaId: ".$personaID."<br />";
		//echo "platform: " .$platform. "<br />";

		//unset the variables used in this section as we will use them again
		unset($EAACCOUNT, $d, $ch, $cookie_string);
		
		//Cookie Data includes the two keys from above
		$cookie_string = $EASW_KEY."; ".$EASF_SESS;
		//JSON data to send as a POST item
		$data = array("isReadOnly" => false, "sku" => "393A0001", "clientVersion" => 3, "nuc" => $NUC, "nucleusPersonaId" => $personaID, "nucleusPersonaDisplayName" => $dispname, "nucleusPersonaPlatform" => $platform, "locale" => $locale, "method" => "idm", "priorityLevel" => 4, "identification" => array( "EASW-Token" => "" ));
		$data_string = json_encode($data);                                                                          
		//Setup cURL HTTP request
		$ch = curl_init($auth);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_string))                                                                       
		);

		$EAAUTH = curl_exec($ch);
		curl_close($ch);
		
		//Split the returned HEADERs into an Array
		$r = explode("\r\n", $EAAUTH);

		//User Session ID
		$XSID = $r[3];
		//Display the User Session ID
		//echo $XSID. "<br />";

		//unset the variables used in this section as we will use them again
		unset($EAAUTH, $ch, $cookie_string, $data, $data_string, $r, $NUC, $personaID, $platform, $dispname, $locale);
		
		//Cookie Data includes the two keys from above
		$cookie_string = $EASW_KEY."; ".$EASF_SESS;
		//POST data to send
		$data_string = "answer=".$this->hash;    
		//Setup cURL HTTP request
		$ch = curl_init($quest);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie_string); 
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/x-www-form-urlencoded',                                                                                
			'Content-Length: ' . strlen($data_string),
			$XSID)                                                                       
		);

		$EAVALIDATE = curl_exec($ch);
		curl_close($ch);
		
		//Split the returned HEADERs into an Array
		$r = explode("\r\n", $EAVALIDATE);
		
		//Phishing Key
		$s = explode(":", $r[11]);
		$t = explode(";", $s[1]);
		$PHISHKEY = $t[0];
		//Display the Phishing Key 
		//echo $PHISHKEY. "<br />";

		unset($EAVALIDATE, $ch, $cookie_string, $data_string, $r, $s, $t);
		
		//Build the array of items to return
		$returnitems = array('EASW_KEY' => $EASW_KEY, 'EASF_SESS' => $EASF_SESS, 'XSID' => $XSID, 'PHISHKEY' => $PHISHKEY);
		
		//Return the array
		return $returnitems;
	}
}
?>

<?php
  	require "./secure/core.php";
	
	//Sample search for Dimitar Berbatov
	$search = $s->playersearch('0','1','gold','f442','attacker','9','13','144','','','','');
 	$results = json_decode($search, true);
 	
 	//Sample search for player formation cards
 	$search1 = $s->trainingsearch('0','1',"gold", "playerFormation", "", "", "", "");
 	$results1 = json_decode($search1, true);
 	
 	//Clean results for viewing pleasure
 	$EASW_KEY = explode("=",$info['EASW_KEY']);
	$EASF_SESS = explode("=",$info['EASF_SESS']);
	$PHISH_KEY = explode("-",$info['PHISHKEY']);
	$XSID = explode(":",$info['XSID']);
 	
 	$orig_formation = $results['auctionInfo'][0]['itemData']['formation'];
 	$remove_f = explode("f",$orig_formation);
 	$new_formation = wordwrap($remove_f[1],1,'-',true);
?>
<html>
	<head>
		<title>Sample FUT13 Page</title>
	</head>

	<body>
		<?php
			
			//display the connection info on the screen
 			echo "<b>Your EA Connection Details</b>:<br />";
 			echo "EASW Key : " . $EASW_KEY[1] . "<br />";
 			echo "EASF Session : " . $EASF_SESS[1] . "<br />";
			echo "Phishing Key : " . $PHISH_KEY[1] . "<br />";
 			echo "Session ID : " . $XSID[1] . "<br /><br />";
 			
 			//display your marketplace data
 			echo "<b>Your Marketplace Data</b>:<br />";
 			echo "Total Coins : ". $results['credits'] ."<br />";
 			echo "Total Bid Tokens : ". $results['bidTokens']['count'] ."<br /><br />";
 			
 			// generate the real resource id required for the JSON request
			$rid = $results['auctionInfo'][0]['itemData']['resourceId'];
			$l = 0;
			while($rid > 16777216)
				{
					$l++;
					if ($l == 1)
						{
			 				$rid -= 1342177280;
			 			}
					elseif ($l == 2)
						{
			 				$rid -= 50331648;
			 			}
					else
						{
			 				$rid -= 16777216;
			 			}
			 	}
			
			// receive player information using the resource id
			$playerinfo = file_get_contents("http://cdn.content.easports.com/fifa/fltOnlineAssets/2013/fut/items/web/" . $rid . ".json");
			$playerinforesults = json_decode($playerinfo, true);
			
 			//sample marketplace search
 			echo "<b>Sample Marketplace Search</b>:<br />";
			echo "Player Name : ".$playerinforesults['Item']['FirstName']." ".$playerinforesults['Item']['LastName']."<br />";
 			echo "Real Resource ID : ".$rid."<br />";
			echo "Trade ID : ".$results['auctionInfo'][0]['tradeId']."<br />";
 			echo "Buy now price : ".$results['auctionInfo'][0]['buyNowPrice']."<br />";
 			echo "Seller Name : ".$results['auctionInfo'][0]['sellerName']."<br />";
			echo "Rating: " . $results['auctionInfo'][0]['itemData']['rating'] ."<br />";
			echo "Rare Flag: " . $results['auctionInfo'][0]['itemData']['rareflag'] ."<br />";
			echo "Starting Bid: ". $results['auctionInfo'][0]['startingBid'] ."<br />";
			echo "Current Bid: ". $results['auctionInfo'][0]['currentBid'] ."<br />";
			echo "BIN Price: ". $results['auctionInfo'][0]['buyNowPrice'] ."<br />";
 			echo "Formation : ".$new_formation."<br /><br />";
 			
 			//sample formation search
 			echo "<b>Sample Formation Search</b>:<br />";
 			echo "Trade ID : ".$results1['auctionInfo'][0]['tradeId']."<br />";
 			echo "Buy now price : ".$results1['auctionInfo'][0]['buyNowPrice']."<br />";
 			echo "Seller Name : ".$results1['auctionInfo'][0]['sellerName']."<br />";
 			echo "ResourceID : ".$results1['auctionInfo'][0]['itemData']['resourceId']."<br />";
		?>
	</body>
	
	<footer>
		<h3>All Credits go to <a href="https://github.com/curt2008/Fifa13-Ultimate-Team-PHP-App">curt2008</a></h3>
	</footer>
</html>

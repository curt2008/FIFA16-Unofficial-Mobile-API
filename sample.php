<?php
	$datos = array(
		"username" => "email@email.com",
        "password" => "password",
        "platform" => "360", // 360, pc, ps3
        "hash" => "md5hash",  // answer in hash
	);
	
	$connector = new Connector($datos);
	$con = $connector->Connect();

	echo "NUCLEUS ID: ".$con["nucleusId"];

	echo "<br><br>";

 	echo "SID: ".$con["sessionId"];

 	echo "<br><br>";

 	echo "TOKEN: ".$con["phishingToken"];
?>
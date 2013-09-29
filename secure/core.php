<?php
	require "./secure/config.php";
	require "./classes/connector.php";
	require "./classes/eahashor.php";
	
	//initialise the classes
	$eahashor = new EAHashor();
	$hash = $eahashor->eaEncode($secret);
	$c = new Connector($user, $password, $hash);
	$info = $c->connect();
?>

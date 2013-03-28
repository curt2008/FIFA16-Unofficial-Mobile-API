<?php
	require "./secure/config.php";
	require "./classes/connector.php";
	require "./classes/tradeor.php";
	require "./classes/eahashor.php";
	require "./classes/searchor.php";
	require "./classes/functionor.php";
	
	//initialise the classes
	$eahashor = new EAHashor();
	$f = new Functionor();
	$hash = $eahashor->eaEncode($secret);
	$c = new Connector($user, $password, $hash);
	$info = $c->connect();
	$s = new Searchor($info['EASW_KEY'], $info['EASF_SESS'], $info['PHISHKEY'], $info['XSID']);
	$t = new Tradeor($info['EASW_KEY'], $info['EASF_SESS'], $info['PHISHKEY'], $info['XSID']);
?>

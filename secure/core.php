<?php
	require $_SERVER['DOCUMENT_ROOT']."/secure/config.php";
	require $_SERVER['DOCUMENT_ROOT']."/classes/connector.php";
	require $_SERVER['DOCUMENT_ROOT']."/classes/tradeor.php";
	require $_SERVER['DOCUMENT_ROOT']."/classes/eahashor.php";
	require $_SERVER['DOCUMENT_ROOT']."/classes/searchor.php";
	require $_SERVER['DOCUMENT_ROOT']."/classes/functionor.php";
	
	//initialise the classes
	$eahashor = new EAHashor();
	$f = new Functionor();
	$hash = $eahashor->eaEncode($secret);
	$c = new Connector($user, $password, $hash);
	$info = $c->connect();
	$s = new Searchor($info['EASW_KEY'], $info['EASF_SESS'], $info['PHISHKEY'], $info['XSID']);
	$t = new Tradeor($info['EASW_KEY'], $info['EASF_SESS'], $info['PHISHKEY'], $info['XSID']);
?>
<?php
	require 'classes/connect.php';
	require 'classes/eahashor.php';
	
	$hash = new EAHashor();
	
	$email = "email@email.com";
	$password = "password";
	$answer = $hash->eaEncode("secret");
	
	$con = new Connector($email, $password, $answer);
	$connection = $con->connect();
	echo var_dump($connection);
?>

<?php
  
  /*
    Twitter : @CCrewe2
    Email : curtis@budgetwebsitesolutions.co.uk
    Skype : Paid4Upload

    # Instructions #
    1. Edit the "email", "password" and "secret" string to your information
    2. Set up a cron job to run this file every hour,
      2. If you're using cPanel you should do the following command : php -q /home/username/public_html/cron.php  
  */

  require "classes/connector.php";
	require "classes/tradeor.php";
	require "classes/eahashor.php";
	
	$email = "email@email.com";
	$password = "password";
	$secret = "secret
	
	$eahashor = new EAHashor();
	$hash = $eahashor->eaEncode($secret);
	
	$c = new Connector($email, $password, $hash);
	$info = $c->connect();
	
	if($info != "invalid") {
		$t = new Tradeor($info['EASW_KEY'], $info['EASF_SESS'], $info['PHISHKEY'], $info['XSID']);
		@$t->relistItems();
	} else {
    //failed to run file your details are incorrect
	}
?>

FIFA16-Unofficial-Mobile-API
=================

### Products
Interested in a tool that allows you to transfer coins between your accounts automatically? be sure to check the tool here : http://curtiscrewe.co.uk/fifa-16-coin-transfer-tool/

Interested in a FIFA 16 Autobuyer that works on any console and developed solely in PHP so allows you edit it perfectly to your needs? check it out here : http://curtiscrewe.co.uk/fifa-16-autobuyer/

### Hire me
If you're interested in any website development work then be sure to drop me an email at curtis_199513@hotmail.co.uk or add me on skype : bws-curtis

### Starting
```php
define("CLASSES", $_SERVER['DOCUMENT_ROOT']."/classes");
require CLASSES."/Guzzle/guzzle.phar";
require CLASSES."/connector.php";
require CLASSES."/eahashor.php";
require CLASSES."/searchor.php";

$loginDetails = array(
    "username" => $email,
    "password" => $password,
    "hash" => $hash,
    "platform" => "XBOX", //XBOX or PS
);

$con = new Mobile_Connector($loginDetails);
$connection = $con->connect();
```

This will return the session and token information used to make Search and Bid calls. It will be returned in an array with the following layout:

```php
$loginResponse = array(
    "nucleusId" => $nucleusId,
    "userAccounts" => $userAccounts,
    "sessionId" => $sessionId,
    "phishingToken" => $phishingToken,
    "cookies" => $cookiePlugin,
    "platform" => $this->_loginDetails['platform']
);
```

These can be used by grabbing the array key like so: 
```php
$nucID = $loginResponse['nucleusId']; 
```


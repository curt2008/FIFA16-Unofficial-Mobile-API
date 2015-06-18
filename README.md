FIFA15-Unofficial-API
=================

### Functionality
This system no longer works as of the most recent updates by EA.

### Hire me
If you're interested in any website development work then be sure to drop me an email at admin@curtiscrewe.co.uk or add me on skype : bws-curtis

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
    "hash" => $hash, //must be a value hashed using EAHashor
    "platform" => "XBOX", //XBOX or PS
);

$con = new Connector($loginDetails);
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


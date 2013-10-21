FIFA14-Unofficial-API
=================

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
    "platform" => "xbox360",
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


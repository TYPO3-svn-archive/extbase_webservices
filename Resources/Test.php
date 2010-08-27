<?php

try {
	$soapClient = new SoapClient('http://ttn.local.dfau.de/index.php?id=847&type=5177');
	var_dump($soapClient->ping());
} catch (Exception $e) {
	var_dump($soapClient->__getLastRequest());
	var_dump($e);
}

?>
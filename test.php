<?php
echo phpinfo();
$proxy = new SoapClient('http://kengkeng.de/api/v2_soap/?wsdl'); // TODO : change url
$sessionId = $proxy->login('123123', '123123'); // TODO : change login and pwd if necessary

$result = $proxy->catalogProductList($sessionId);
var_dump($result);
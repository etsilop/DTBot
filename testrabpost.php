#!/usr/bin/env php
<?php
echo "running\n";

$rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
$rabbit->connect();

$testChannel = new AMQPChannel($rabbit);
$testExchange = new AMQPExchange($testChannel);

$testExchange->setName('amq.direct');
$testExchange->publish('Hello buddy!', 'route_to_everybody');

$rabbit->disconnect();
?>

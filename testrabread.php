#!/usr/bin/env php
<?php
echo "running\n";

$rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
$rabbit->connect();

$channel = new AMQPChannel($rabbit);

$q = new AMQPQueue($channel);
$q->setName('direct_messages');
$q->declare();
$q->bind('amq.direct', 'route_to_everybody');

$envelope = $q->get();
if ($envelope) {
	print_r($envelope->getBody());
	print_r("\n");
	$q->ack($envelope->getDeliveryTag());
}

$rabbit->disconnect();
?>

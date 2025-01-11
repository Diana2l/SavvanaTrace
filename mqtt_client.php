<?php

require  '/vendor/autoload.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;

$server   = 'broker.emqx.io';
$port     = 1883;
$clientId = 'php_mqtt_client_' . uniqid();
$topic    = 'lora/ip_address';

$mqtt = new MqttClient($server, $port, $clientId);

try {
    $mqtt->connect();
    echo "Connected to broker...\n";

    $ipAddress = getHostByName(getHostName());
    $mqtt->publish($topic, $ipAddress, 1);
    echo "Published IP address: $ipAddress to topic: $topic\n";

    $mqtt->disconnect();
} catch (MqttClientException $e) {
    echo "Could not connect to the broker: {$e->getMessage()}\n";
    sleep(5);
    echo "Retrying...\n";
    exec('php mqtt_client.php');
}

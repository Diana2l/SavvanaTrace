<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require('config.php');

function getWifiIP() {
    $ip = $_SERVER['SERVER_ADDR'];
    if (empty($ip)) {
        $ip = gethostbyname(gethostname());
    }
    return $ip;
}

require('vendor/autoload.php');

$mqtt = new \PhpMqtt\Client\MqttClient(MQTT_BROKER, MQTT_PORT, MQTT_CLIENT_ID);

try {
    $mqtt->connect();
    $ip = getWifiIP();
    
    $mqtt->publish(MQTT_TOPIC, json_encode(['ip' => $ip]), 0);
    $mqtt->disconnect();
    
    echo json_encode(['success' => true, 'ip' => $ip]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

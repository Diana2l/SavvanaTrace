<?php
define('MQTT_BROKER', 'broker.emqx.io');
define('MQTT_PORT', 1883);
define('MQTT_CLIENT_ID', 'php_subscriber_'.rand());
define('MQTT_TOPIC', 'ip_address_updates');
<?php

namespace App\Providers;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;
use Illuminate\Support\ServiceProvider;

class MqttServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MqttClient::class, function () {
            $host = 'broker.emqx.io'; // Replace with your broker's host
            $port = 1883;             // Default MQTT port
            $clientId = 'laravel-mqtt-' . uniqid();

            try {
                $mqtt = new MqttClient($host, $port, $clientId);
                $mqtt->connect();
                return $mqtt;
            } catch (MqttClientException $e) {
                throw new \RuntimeException('Unable to connect to MQTT broker: ' . $e->getMessage());
            }
        });
    }
}

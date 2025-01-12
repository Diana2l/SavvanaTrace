<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SavannaTrace Task</title>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: teal;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        button:hover {
            background-color: darkcyan;
        }

        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
<!-- Vue Root -->
<div id="app" class="container">
    <h1>SavannaTrace Task</h1>
    <button @click="connectToMqtt">Connect & Fetch IP</button>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            mqttConnected: false,
            ipAddress: ''
        },
        methods: {
            connectToMqtt() {
                const mqttClient = mqtt.connect('ws://test.mosquitto.org:8080');
                mqttClient.on('connect', () => {
                    this.mqttConnected = true;
                    mqttClient.subscribe('savannatrace/ip');
                });
                mqttClient.on('message', (topic, message) => {
                    if (topic === 'savannatrace/ip') {
                        this.ipAddress = message.toString();
                    }
                });
            }
        }
    });
</script>   
    </div>
</body>
</html>

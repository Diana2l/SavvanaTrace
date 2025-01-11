const app = new Vue({
    el: '#app',
    data: {
        ipAddress: null,
        mqttClient: null
    },
    methods: {
        connectToMqtt() {
            // Connect to MQTT broker
            this.mqttClient = mqtt.connect('wss://your-mqtt-broker-url'); // Replace with your MQTT broker URL

            this.mqttClient.on('connect', () => {
                console.log('Connected to MQTT broker');
                this.fetchIpAddress();
            });

            this.mqttClient.on('error', (error) => {
                console.error('MQTT connection error:', error);
            });
        },
        fetchIpAddress() {
            // Fetch IP address using an external API
            fetch('https://api.ipify.org?format=json')
                .then(response => response.json())
                .then(data => {
                    this.ipAddress = data.ip;
                    console.log('Fetched IP:', this.ipAddress);

                    // Publish the IP address to an MQTT topic (optional)
                    if (this.mqttClient) {
                        this.mqttClient.publish('ip-address-topic', this.ipAddress);
                    }
                })
                .catch(error => {
                    console.error('Error fetching IP:', error);
                });
        }
    }
});
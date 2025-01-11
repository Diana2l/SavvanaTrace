<template>
  <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-6 text-blue-600">IP Address Monitor</h1>
    
    <div class="mb-6">
      <div class="flex items-center mb-2">
        <div class="w-3 h-3 rounded-full mr-2" 
             :class="{'bg-green-500': isConnected, 'bg-red-500': !isConnected}">
        </div>
        <span class="text-gray-700">
          {{ isConnected ? 'Connected to MQTT Broker' : 'Disconnected' }}
        </span>
      </div>
    </div>

    <div class="bg-gray-100 p-4 rounded mb-6">
      <h2 class="text-lg font-semibold mb-2">Current IP Address</h2>
      <p class="text-xl font-mono">{{ currentIP || 'Waiting for IP...' }}</p>
    </div>

    <button @click="requestIP" 
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
            :disabled="!isConnected">
      Request IP Address
    </button>

    <div v-if="error" class="mt-4 p-4 bg-red-100 text-red-700 rounded">
      {{ error }}
    </div>
  </div>
</template>

<script>
import mqtt from 'mqtt'

export default {
  name: 'IPMonitor',
  data() {
    return {
      client: null,
      isConnected: false,
      currentIP: '',
      error: '',
      topic: 'ip/address',
      retryCount: 0,
      maxRetries: 5
    }
  },
  methods: {
    setupMQTT() {
      const options = {
        clientId: 'savanna_trace_' + Math.random().toString(16).substr(2, 8),
        clean: true,
        connectTimeout: 4000,
        reconnectPeriod: 5000,
      }

      try {
        this.client = mqtt.connect('ws://broker.emqx.io:1883/mqtt', options)

        this.client.on('connect', () => {
          this.isConnected = true
          this.error = ''
          this.client.subscribe(this.topic)
        })

        this.client.on('message', (topic, message) => {
          if (topic === this.topic) {
            this.currentIP = message.toString()
          }
        })

        this.client.on('error', (err) => {
          this.error = `Connection error: ${err.message}`
          this.isConnected = false
          this.retryConnection()
        })

        this.client.on('close', () => {
          this.isConnected = false
          this.retryConnection()
        })
      } catch (err) {
        this.error = `Failed to connect: ${err.message}`
      }
    },
    retryConnection() {
      if (this.retryCount < this.maxRetries) {
        setTimeout(() => {
          this.retryCount++
          this.setupMQTT()
        }, 5000)
      } else {
        this.error = 'Maximum retry attempts reached. Please refresh the page.'
      }
    },
    requestIP() {
      if (this.isConnected) {
        this.client.publish('ip/request', 'request')
      }
    }
  },
  mounted() {
    this.setupMQTT()
  },
  beforeUnmount() {
    if (this.client) {
      this.client.end()
    }
  }
}
</script>

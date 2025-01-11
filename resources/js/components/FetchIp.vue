<template>
    <div class="fetch-ip">
      <h1>Fetch LoRa Gateway IP Address</h1>
      <button @click="fetchIp">Get IP Address</button>
      <p v-if="ipAddress">IP Address: {{ ipAddress }}</p>
      <p v-if="error" style="color: red;">Error: {{ error }}</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        ipAddress: null,
        error: null,
      };
    },
    methods: {
      async fetchIp() {
        this.error = null;
        this.ipAddress = null;
        try {
          const response = await axios.get('http://127.0.0.1:8000/fetch-ip');
          this.ipAddress = response.data.message;
        } catch (err) {
          this.error = 'Failed to fetch IP. Please try again.';
        }
      },
    },
  };
  </script>
  
  <style>
  .fetch-ip {
    text-align: center;
    margin-top: 50px;
  }
  button {
    padding: 10px 20px;
    font-size: 16px;
  }
  </style>
  
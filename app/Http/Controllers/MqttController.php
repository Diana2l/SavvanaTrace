<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MqttController extends Controller
{
    public function publishIp()
    {
        exec('php ../mqtt_client.php');
        return response()->json(['message' => 'IP published successfully!']);
}
}
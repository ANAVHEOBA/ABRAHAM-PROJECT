<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Events\DeliveryLocationUpdated;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::where('status', 'in_progress')->with('user')->get();
        return response()->json($deliveries);
    }

    public function show(Delivery $delivery)
    {
        return response()->json($delivery->load('user'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $request->validate([
            'current_latitude' => 'required|numeric',
            'current_longitude' => 'required|numeric',
            'status' => 'required|in:in_progress,completed',
        ]);

        $delivery->update($request->all());

        broadcast(new DeliveryLocationUpdated($delivery))->toOthers();

        return response()->json($delivery);
    }
}
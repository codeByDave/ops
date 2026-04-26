<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'make' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'vin' => ['nullable', 'string', 'size:17'],
            'tag_state' => ['nullable', 'string', 'size:2'],
            'tag_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['company_id'] = $customer->company_id;
        $data['customer_id'] = $customer->id;

        $data['vin'] = !empty($data['vin']) ? strtoupper($data['vin']) : null;
        $data['tag_state'] = !empty($data['tag_state']) ? strtoupper($data['tag_state']) : null;
        $data['tag_number'] = !empty($data['tag_number']) ? strtoupper($data['tag_number']) : null;

        Vehicle::create($data);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Vehicle added successfully.');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'make' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'vin' => ['nullable', 'string', 'size:17'],
            'tag_state' => ['nullable', 'string', 'size:2'],
            'tag_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['vin'] = !empty($data['vin']) ? strtoupper($data['vin']) : null;
        $data['tag_state'] = !empty($data['tag_state']) ? strtoupper($data['tag_state']) : null;
        $data['tag_number'] = !empty($data['tag_number']) ? strtoupper($data['tag_number']) : null;

        $vehicle->update($data);

        return back()->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return back()->with('success', 'Vehicle archived successfully.');
    }
}
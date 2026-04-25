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
            'make' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'vin' => ['nullable', 'string', 'size:17'],
            'tag_state' => ['nullable', 'string', 'size:2'],
            'tag_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['company_id'] = $customer->company_id;
        $data['tag_state'] = $data['tag_state'] ? strtoupper($data['tag_state']) : null;
        $data['tag_number'] = $data['tag_number'] ? strtoupper($data['tag_number']) : null;
        $data['vin'] = $data['vin'] ? strtoupper($data['vin']) : null;
        $data['is_active'] = true;

        $vehicle = Vehicle::create($data);

        $customer->vehicles()->attach($vehicle->id, [
            'company_id' => $customer->company_id,
        ]);

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
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['vin'] = $data['vin'] ? strtoupper($data['vin']) : null;
        $data['tag_state'] = $data['tag_state'] ? strtoupper($data['tag_state']) : null;
        $data['tag_number'] = $data['tag_number'] ? strtoupper($data['tag_number']) : null;
        $data['is_active'] = $request->boolean('is_active');

        $vehicle->update($data);

        return back()->with('success', 'Vehicle updated successfully.');
    }

    public function archive(Vehicle $vehicle)
    {
        $vehicle->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Vehicle archived successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return back()->with('success', 'Vehicle archived successfully.');
    }
}
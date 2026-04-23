<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LookupValue;
use App\Models\ServiceCall;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ServiceCallController extends Controller
{
    public function index()
    {
        $serviceCalls = ServiceCall::with([
            'customer',
            'vehicle',
            'status',
            'serviceType',
            'companyVehicle',
            'assignedUser',
        ])
            ->latest()
            ->get();

        return view('content.pages.service-calls.index', compact('serviceCalls'));
    }

    public function create()
    {
        $customers = Customer::orderBy('first_name')->get();

        $serviceTypes = LookupValue::whereHas('type', function ($q) {

            $q->where('code', 'service_type');

        })->orderBy('sort_order')->get();

        $statuses = LookupValue::whereHas('type', function ($q) {

            $q->where('code', 'service_call_status');

        })->orderBy('sort_order')->get();

        return view('content.pages.service-calls.create', compact(

            'customers',

            'serviceTypes',

            'statuses'

        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'customer_id' => 'required|exists:customers,id',

            'vehicle_id' => 'required|exists:vehicles,id',

            'service_type_id' => 'nullable|exists:lookup_values,id',

            'status_id' => 'nullable|exists:lookup_values,id',

            'customer_mobile_phone' => 'nullable|string',

            'address_1' => 'nullable|string',

            'city' => 'nullable|string',

            'state' => 'nullable|string',

        ]);

        $customer = Customer::find($data['customer_id']);

        $vehicle = Vehicle::find($data['vehicle_id']);

        $data['company_id'] = $customer->company_id;

        // Snapshot fields

        $data['customer_name'] = trim($customer->first_name . ' ' . $customer->last_name);

        $data['vehicle_label'] = trim("{$vehicle->year} {$vehicle->make} {$vehicle->model}");

        \App\Models\ServiceCall::create($data);

        return redirect()->route('service-calls.index')->with('success', 'Service call created successfully.');
    }
}
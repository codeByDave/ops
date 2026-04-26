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

    public function storeFromCustomer(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_type_id' => ['required', 'exists:lookup_values,id'],
            'customer_mobile_phone' => ['nullable', 'string', 'max:50'],
            'scheduled_for' => ['nullable', 'date'],
            'address_1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);

        $statusCode = !empty($data['scheduled_for']) ? 'scheduled' : 'new';

        $status = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'service_call_status');
        })->where('code', $statusCode)->firstOrFail();

        $customerName = $customer->company_name
            ?: trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));

        $vehicleLabel = trim(
            ($vehicle->year ?? '') . ' ' .
            ($vehicle->make ?? '') . ' ' .
            ($vehicle->model ?? '')
        );

        ServiceCall::create([
            'company_id' => $customer->company_id,
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status_id' => $status->id,
            'service_type_id' => $data['service_type_id'],

            'customer_name' => $customerName ?: null,
            'customer_mobile_phone' => $this->normalizePhone($data['customer_mobile_phone'] ?? null),
            'vehicle_label' => $vehicleLabel ?: null,

            'scheduled_for' => $data['scheduled_for'] ?? null,
            'address_1' => $data['address_1'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => !empty($data['state']) ? strtoupper($data['state']) : null,
            'postal_code' => $data['postal_code'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Service call created successfully.');
    }

    public function update(Request $request, ServiceCall $serviceCall)
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_type_id' => ['required', 'exists:lookup_values,id'],
            'status_id' => ['required', 'exists:lookup_values,id'],
            'customer_mobile_phone' => ['nullable', 'string', 'max:50'],

            'created_at' => ['required', 'date'],
            'scheduled_for' => ['nullable', 'date'],
            'dispatched_at' => ['nullable', 'date'],
            'enroute_at' => ['nullable', 'date'],
            'arrived_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],

            'address_1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->validateTimelineOrder($data);

        $data['customer_mobile_phone'] = $this->normalizePhone($data['customer_mobile_phone'] ?? null);
        $data['state'] = !empty($data['state']) ? strtoupper($data['state']) : null;

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);

        $data['vehicle_label'] = trim(
            ($vehicle->year ?? '') . ' ' .
            ($vehicle->make ?? '') . ' ' .
            ($vehicle->model ?? '')
        ) ?: null;

        $status = LookupValue::findOrFail($data['status_id']);

        if ($status->code === 'dispatched' && empty($data['dispatched_at'])) {
            $data['dispatched_at'] = now();
        }

        if ($status->code === 'en_route' && empty($data['enroute_at'])) {
            $data['enroute_at'] = now();
        }

        if ($status->code === 'on_scene' && empty($data['arrived_at'])) {
            $data['arrived_at'] = now();
        }

        if (
            in_array($status->code, ['completed', 'goa', 'cancelled'], true)
            && empty($data['completed_at'])
        ) {
            $data['completed_at'] = now();
        }

        $targetStatusCode = null;
        
        if (!empty($data['completed_at'])) {
            $targetStatusCode = 'completed';
        } elseif (!empty($data['arrived_at'])) {
            $targetStatusCode = 'on_scene';
        } elseif (!empty($data['enroute_at'])) {
            $targetStatusCode = 'en_route';
        } elseif (!empty($data['dispatched_at'])) {
            $targetStatusCode = 'dispatched';
        } elseif (!empty($data['scheduled_for'])) {
            $targetStatusCode = 'scheduled';
        }

        $statusOrder = [
            'new' => 1,
            'scheduled' => 2,
            'dispatched' => 3,
            'en_route' => 4,
            'on_scene' => 5,
            'completed' => 6,
            'goa' => 6,
            'cancelled' => 6,
        ];

        if (
            $targetStatusCode &&
            ($statusOrder[$targetStatusCode] ?? 0) > ($statusOrder[$status->code] ?? 0)
        ) {
            $newStatus = LookupValue::whereHas('type', function ($q) {
                $q->where('code', 'service_call_status');
            })
                ->where('code', $targetStatusCode)
                ->first();

            if ($newStatus) {
                $data['status_id'] = $newStatus->id;
            }
        }

        $serviceCall->update($data);

        return back()->with('success', 'Service call updated.');
    }

    private function validateTimelineOrder(array $data): void
    {
        $createdAt = !empty($data['created_at']) ? strtotime($data['created_at']) : null;
        $scheduledFor = !empty($data['scheduled_for']) ? strtotime($data['scheduled_for']) : null;
        $dispatchedAt = !empty($data['dispatched_at']) ? strtotime($data['dispatched_at']) : null;
        $enrouteAt = !empty($data['enroute_at']) ? strtotime($data['enroute_at']) : null;
        $arrivedAt = !empty($data['arrived_at']) ? strtotime($data['arrived_at']) : null;
        $completedAt = !empty($data['completed_at']) ? strtotime($data['completed_at']) : null;

        if ($scheduledFor && $createdAt && $scheduledFor < $createdAt) {
            abort(422, 'Scheduled For cannot be before Call Received.');
        }

        if ($dispatchedAt && $createdAt && $dispatchedAt < $createdAt) {
            abort(422, 'Dispatched cannot be before Call Received.');
        }

        if ($enrouteAt && $dispatchedAt && $enrouteAt < $dispatchedAt) {
            abort(422, 'En Route cannot be before Dispatched.');
        }

        if ($arrivedAt && $enrouteAt && $arrivedAt < $enrouteAt) {
            abort(422, 'On Scene cannot be before En Route.');
        }

        if ($completedAt) {
            foreach ([$createdAt, $scheduledFor, $dispatchedAt, $enrouteAt, $arrivedAt] as $priorTime) {
                if ($priorTime && $completedAt < $priorTime) {
                    abort(422, 'Closed Out cannot be before earlier timeline events.');
                }
            }
        }
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        return $digits ? substr($digits, 0, 10) : null;
    }
}
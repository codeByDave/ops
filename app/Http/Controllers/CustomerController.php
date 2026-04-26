<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\LookupValue;
use App\Helpers\AddressHelper;
use App\Helpers\VehicleHelper;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();

        return view('content.pages.customers.index', compact('customers'));
    }

    public function create()
    {
        $customerTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'customer_type');
        })->orderBy('sort_order')->get();

        $states = AddressHelper::states();

        return view('content.pages.customers.form', compact('customerTypes', 'states'));
    }

    public function edit(Customer $customer)
    {
        $customerTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'customer_type');
        })->orderBy('sort_order')->get();

        $states = AddressHelper::states();

        return view('content.pages.customers.form', compact('customer', 'customerTypes', 'states'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'customer_type_id' => ['required', 'exists:lookup_values,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'home_phone' => ['nullable', 'string', 'max:50'],
            'mobile_phone' => ['nullable', 'string', 'max:50'],
            'address_1' => ['nullable', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $customerType = LookupValue::find($data['customer_type_id']);

        if (!$customerType) {
            return back()
                ->withErrors([
                    'customer_type_id' => 'Invalid customer type selected.',
                ])
                ->withInput();
        }

        if ($customerType->code === 'consumer') {
            if (empty($data['first_name']) || empty($data['last_name'])) {
                return back()
                    ->withErrors([
                        'first_name' => 'Consumer customers require both first and last name.',
                    ])
                    ->withInput();
            }

            $data['company_name'] = null;
        }

        if (in_array($customerType->code, ['business', 'motor_club', 'insurance'])) {
            if (empty($data['company_name'])) {
                return back()
                    ->withErrors([
                        'company_name' => 'This customer type requires a company name.',
                    ])
                    ->withInput();
            }

            $data['first_name'] = null;
            $data['last_name'] = null;
        }

        $data['mobile_phone'] = $this->normalizePhone($data['mobile_phone'] ?? null);
        $data['home_phone'] = $this->normalizePhone($data['home_phone'] ?? null);
        $data['email'] = $this->normalizeEmail($data['email'] ?? null);

        $data['company_id'] = 1;

        Customer::create($data);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function profile(Customer $customer)
    {
        $customerTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'customer_type');
        })->orderBy('sort_order')->get();

        $states = AddressHelper::states();

        $vehicleMakes = VehicleHelper::makes();
        $vehicleColors = VehicleHelper::colors();

        $serviceTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'service_type');
        })->where('is_active', true)->orderBy('sort_order')->get();

        $serviceCallStatuses = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'service_call_status');
        })->where('is_active', true)->orderBy('sort_order')->get();

        return view('content.pages.customers.show', compact(
            'customer',
            'customerTypes',
            'states',
            'vehicleMakes',
            'vehicleColors',
            'serviceTypes',
            'serviceCallStatuses'
        ));
    }

    public function show(Customer $customer)
    {
        $customerTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'customer_type');
        })->orderBy('sort_order')->get();

        $states = AddressHelper::states();

        $vehicleMakes = VehicleHelper::makes();
        $vehicleColors = VehicleHelper::colors();

        $serviceTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'service_type');
        })->where('is_active', true)->orderBy('sort_order')->get();

        $serviceCallStatuses = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'service_call_status');
        })->where('is_active', true)->orderBy('sort_order')->get();

        return view('content.pages.customers.show', compact(
            'customer',
            'customerTypes',
            'states',
            'vehicleMakes',
            'vehicleColors',
            'serviceTypes',
            'serviceCallStatuses'
        ));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Profile deleted successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'customer_type_id' => ['required', 'exists:lookup_values,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'home_phone' => ['nullable', 'string', 'max:50'],
            'mobile_phone' => ['nullable', 'string', 'max:50'],
            'address_1' => ['nullable', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['mobile_phone'] = $this->normalizePhone($data['mobile_phone'] ?? null);
        $data['home_phone'] = $this->normalizePhone($data['home_phone'] ?? null);
        $data['email'] = $this->normalizeEmail($data['email'] ?? null);

        $customer->update($data);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Profile updated successfully.');
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        return $digits ? substr($digits, 0, 10) : null;
    }

    private function normalizeEmail(?string $email): ?string
    {
        return $email ? strtolower($email) : null;
    }
}
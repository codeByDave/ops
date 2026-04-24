<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\LookupValue;
use App\Helpers\AddressHelper;

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

        // Strip everything except digits
        $data['mobile_phone'] = preg_replace('/\D/', '', $data['mobile_phone'] ?? '');
        $data['home_phone'] = preg_replace('/\D/', '', $data['home_phone'] ?? '');

        // Limit to 10 digits
        $data['mobile_phone'] = substr($data['mobile_phone'], 0, 10);
        $data['home_phone'] = substr($data['home_phone'], 0, 10);

        // Convert empty strings back to null
        $data['mobile_phone'] = $data['mobile_phone'] ?: null;
        $data['home_phone'] = $data['home_phone'] ?: null;

        $data['company_id'] = 1;

        Customer::create($data);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function profile($public_id)
    {
        $customer = Customer::where('public_id', $public_id)->firstOrFail();

        $customerTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'customer_type');
        })->orderBy('sort_order')->get();

        $states = AddressHelper::states();

        return view('content.pages.customers.show', compact('customer', 'customerTypes', 'states'));
    }

    public function show(Customer $customer)
    {
        $customerTypes = LookupValue::whereHas('type', function ($q) {
            $q->where('code', 'customer_type');
        })->orderBy('sort_order')->get();

        $states = AddressHelper::states();

        return view('content.pages.customers.show', compact('customer', 'customerTypes', 'states'));
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

        // Strip phone formatting
        $data['mobile_phone'] = preg_replace('/\D/', '', $data['mobile_phone'] ?? '');
        $data['home_phone'] = preg_replace('/\D/', '', $data['home_phone'] ?? '');

        $customer->update($data);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Profile updated successfully.');
    }
}
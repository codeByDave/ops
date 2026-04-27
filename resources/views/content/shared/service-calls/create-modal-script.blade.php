<script>
  function setupCreateServiceCallScheduling() {
    const toggle = document.getElementById('service_call_is_scheduled');
    const wrap = document.getElementById('service_call_scheduled_for_wrapper');
    const input = document.getElementById('service_call_scheduled_for');

    if (!toggle || !wrap || !input) {
      return;
    }

    function refresh() {
      if (toggle.checked) {
        wrap.classList.remove('d-none');
        input.disabled = false;
      } else {
        wrap.classList.add('d-none');
        input.disabled = true;
        input.value = '';
      }
    }

    toggle.addEventListener('change', refresh);
    refresh();
  }

  function setupCreateServiceCallCustomerVehicleFilter() {
    const customerSelect = document.getElementById('service_call_customer_id');
    const lockedCustomerInput = document.getElementById('service_call_locked_customer_id');
    const vehicleSelect = document.getElementById('service_call_vehicle_id');

    if (!vehicleSelect) {
      return;
    }

    const originalVehicleOptions = Array.from(vehicleSelect.options)
      .filter(function(option) {
        return option.value;
      })
      .map(function(option) {
        return {
          value: option.value,
          text: option.text,
          customerId: option.dataset.customerId
        };
      });

    function getSelectedCustomerId() {
      if (lockedCustomerInput) {
        return lockedCustomerInput.value;
      }

      if (customerSelect) {
        return customerSelect.value;
      }

      return '';
    }

    function refreshVehicles() {
      const selectedCustomerId = getSelectedCustomerId();

      vehicleSelect.innerHTML = '';

      const placeholderOption = document.createElement('option');
      placeholderOption.value = '';
      placeholderOption.textContent = selectedCustomerId ? 'Select Vehicle' : 'Select customer first';
      vehicleSelect.appendChild(placeholderOption);

      if (!selectedCustomerId) {
        vehicleSelect.value = '';
        return;
      }

      const matchingVehicles = originalVehicleOptions.filter(function(option) {
        return option.customerId === selectedCustomerId;
      });

      matchingVehicles.forEach(function(vehicle) {
        const option = document.createElement('option');
        option.value = vehicle.value;
        option.textContent = vehicle.text;
        option.dataset.customerId = vehicle.customerId;

        vehicleSelect.appendChild(option);
      });

      if (matchingVehicles.length === 0) {
        const noVehicleOption = document.createElement('option');
        noVehicleOption.value = '';
        noVehicleOption.textContent = 'No vehicles found for this customer';
        noVehicleOption.disabled = true;

        vehicleSelect.appendChild(noVehicleOption);
      }

      vehicleSelect.value = '';
    }

    if (customerSelect && !customerSelect.disabled) {
      customerSelect.addEventListener('change', refreshVehicles);
    }

    refreshVehicles();
  }

  function setupCreateServiceCallCustomerAutofill() {
    const customerSelect = document.getElementById('service_call_customer_id');

    if (!customerSelect || customerSelect.disabled) {
      return;
    }

    const phoneInput = document.getElementById('service_call_customer_mobile_phone');
    const addressInput = document.getElementById('service_call_address_1');
    const cityInput = document.getElementById('service_call_city');
    const stateInput = document.getElementById('service_call_state');
    const postalCodeInput = document.getElementById('service_call_postal_code');

    customerSelect.addEventListener('change', function() {
      const selectedOption = customerSelect.options[customerSelect.selectedIndex];

      if (!selectedOption) {
        return;
      }

      if (phoneInput) {
        phoneInput.value = selectedOption.dataset.phone || '';
        formatPhoneNumber(phoneInput);
      }

      if (addressInput) {
        addressInput.value = selectedOption.dataset.address1 || '';
      }

      if (cityInput) {
        cityInput.value = selectedOption.dataset.city || '';
      }

      if (stateInput) {
        stateInput.value = selectedOption.dataset.state || '';
      }

      if (postalCodeInput) {
        postalCodeInput.value = selectedOption.dataset.postalCode || '';
      }
    });
  }

  function setupCreateServiceCallModal() {
    setupCreateServiceCallScheduling();
    setupCreateServiceCallCustomerVehicleFilter();
    setupCreateServiceCallCustomerAutofill();
  }
</script>

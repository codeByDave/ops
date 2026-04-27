<script>
  function formatPhoneNumber(input) {
    let digits = input.value.replace(/\D/g, '').substring(0, 10);
    let formatted = digits;

    if (digits.length > 6) {
      formatted = `(${digits.substring(0, 3)}) ${digits.substring(3, 6)}-${digits.substring(6, 10)}`;
    } else if (digits.length > 3) {
      formatted = `(${digits.substring(0, 3)}) ${digits.substring(3, 6)}`;
    } else if (digits.length > 0) {
      formatted = `(${digits.substring(0, 3)}`;
    }

    input.value = formatted;
  }

  function formatTimelineLabel(value) {
    if (!value) {
      return '—';
    }

    const date = new Date(value);

    return date.toLocaleString([], {
      month: '2-digit',
      day: '2-digit',
      year: 'numeric',
      hour: 'numeric',
      minute: '2-digit'
    });
  }

  function getTimelineValue(field) {
    const input = document.getElementById('input_' + field);
    return input ? input.value : '';
  }

  function validateTimelineOrder() {
    const createdAt = getTimelineValue('created_at');
    const scheduledFor = getTimelineValue('scheduled_for');
    const dispatchedAt = getTimelineValue('dispatched_at');
    const enrouteAt = getTimelineValue('enroute_at');
    const arrivedAt = getTimelineValue('arrived_at');
    const completedAt = getTimelineValue('completed_at');

    const checks = [{
        field: 'scheduled_for',
        value: scheduledFor,
        compareValue: createdAt,
        message: 'Scheduled For cannot be before Call Received.'
      },
      {
        field: 'dispatched_at',
        value: dispatchedAt,
        compareValue: createdAt,
        message: 'Dispatched cannot be before Call Received.'
      },
      {
        field: 'enroute_at',
        value: enrouteAt,
        compareValue: dispatchedAt,
        message: 'En Route cannot be before Dispatched.'
      },
      {
        field: 'arrived_at',
        value: arrivedAt,
        compareValue: enrouteAt,
        message: 'On Scene cannot be before En Route.'
      }
    ];

    for (const check of checks) {
      if (!check.value || !check.compareValue) {
        continue;
      }

      if (new Date(check.value) < new Date(check.compareValue)) {
        alert(check.message);

        const input = document.getElementById('input_' + check.field);

        if (input) {
          input.focus();
        }

        return false;
      }
    }

    if (completedAt) {
      const priorFields = [arrivedAt, enrouteAt, dispatchedAt, scheduledFor, createdAt];

      for (const priorValue of priorFields) {
        if (!priorValue) {
          continue;
        }

        if (new Date(completedAt) < new Date(priorValue)) {
          alert('Closed Out cannot be before earlier timeline events.');

          const input = document.getElementById('input_completed_at');

          if (input) {
            input.focus();
          }

          return false;
        }
      }
    }

    return true;
  }

  function setupEditServiceCallModal() {
    const timelineFields = [
      'created_at',
      'scheduled_for',
      'dispatched_at',
      'enroute_at',
      'arrived_at',
      'completed_at'
    ];

    function getDatasetValue(button, field) {
      const datasetKey = field.replace(/_([a-z])/g, function(_, letter) {
        return letter.toUpperCase();
      });

      return button.dataset[datasetKey] || '';
    }

    function loadTimelineField(button, field) {
      const value = getDatasetValue(button, field);

      const input = document.getElementById('input_' + field);
      const label = document.getElementById('label_' + field);
      const row = document.getElementById('row_' + field);

      if (input) {
        input.value = value;
      }

      if (label) {
        label.innerText = formatTimelineLabel(value);
      }

      if (row) {
        row.classList.add('d-none');
      }
    }

    document.querySelectorAll('.js-edit-service-call').forEach(function(button) {
      button.addEventListener('click', function() {
        const form = document.getElementById('editServiceCallForm');

        if (form) {
          form.action = button.dataset.updateUrl || '';
        }

        document.getElementById('edit_service_vehicle_id').value = button.dataset.vehicleId || '';
        document.getElementById('edit_service_service_type_id').value = button.dataset.serviceTypeId || '';
        document.getElementById('edit_service_status_id').value = button.dataset.statusId || '';
        document.getElementById('edit_service_assigned_user_id').value = button.dataset.assignedUserId || '';
        document.getElementById('edit_service_assigned_company_vehicle_id').value = button.dataset
          .assignedCompanyVehicleId || '';
        document.getElementById('edit_service_address_1').value = button.dataset.address1 || '';
        document.getElementById('edit_service_city').value = button.dataset.city || '';
        document.getElementById('edit_service_state').value = button.dataset.state || '';
        document.getElementById('edit_service_postal_code').value = button.dataset.postalCode || '';
        document.getElementById('edit_service_notes').value = button.dataset.notes || '';

        const phone = document.getElementById('edit_service_phone');

        if (phone) {
          phone.value = button.dataset.phone || '';
          formatPhoneNumber(phone);
        }

        timelineFields.forEach(function(field) {
          loadTimelineField(button, field);
        });
      });
    });
  }

  function setupTimelineEditors() {
    document.querySelectorAll('.timeline-edit-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const row = document.getElementById('row_' + btn.dataset.target);

        if (row) {
          row.classList.remove('d-none');
        }
      });
    });

    document.querySelectorAll('.timeline-cancel-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const row = document.getElementById('row_' + btn.dataset.target);

        if (row) {
          row.classList.add('d-none');
        }
      });
    });

    document.querySelectorAll('.timeline-save-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const target = btn.dataset.target;
        const row = document.getElementById('row_' + target);
        const input = document.getElementById('input_' + target);
        const label = document.getElementById('label_' + target);

        if (!input || !label) {
          return;
        }

        if (!validateTimelineOrder()) {
          return;
        }

        label.innerText = formatTimelineLabel(input.value);

        if (row) {
          row.classList.add('d-none');
        }
      });
    });
  }

  function setupEditServiceCallFormValidation() {
    const form = document.getElementById('editServiceCallForm');

    if (!form) {
      return;
    }

    form.addEventListener('submit', function(event) {
      if (!validateTimelineOrder()) {
        event.preventDefault();
      }
    });
  }

  function reopenEditServiceCallModalAfterValidationError() {
    const serviceCallToReopen = @json(session('open_edit_service_call_id'));
    const oldServiceCallInput = @json(old());

    if (!serviceCallToReopen) {
      return;
    }

    const reopenButton = document.querySelector(
      `.js-edit-service-call[data-service-call-id="${serviceCallToReopen}"]`
    );

    if (!reopenButton) {
      return;
    }

    reopenButton.click();

    setTimeout(function() {
      const modal = new bootstrap.Modal(document.getElementById('editServiceCallModal'));
      modal.show();

      const fieldMap = {
        vehicle_id: 'edit_service_vehicle_id',
        service_type_id: 'edit_service_service_type_id',
        status_id: 'edit_service_status_id',
        assigned_user_id: 'edit_service_assigned_user_id',
        assigned_company_vehicle_id: 'edit_service_assigned_company_vehicle_id',
        customer_mobile_phone: 'edit_service_phone',
        address_1: 'edit_service_address_1',
        city: 'edit_service_city',
        state: 'edit_service_state',
        postal_code: 'edit_service_postal_code',
        notes: 'edit_service_notes'
      };

      Object.keys(fieldMap).forEach(function(inputName) {
        const element = document.getElementById(fieldMap[inputName]);

        if (element && oldServiceCallInput[inputName] !== undefined) {
          element.value = oldServiceCallInput[inputName] || '';
        }
      });

      if (oldServiceCallInput.customer_mobile_phone) {
        const phoneInput = document.getElementById('edit_service_phone');

        if (phoneInput) {
          formatPhoneNumber(phoneInput);
        }
      }
    }, 100);
  }
</script>

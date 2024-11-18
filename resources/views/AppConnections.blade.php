@extends('layouts.master')
@section('title')
    @lang('translation.App-Connection')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.App-Connection')
        @endslot
    @endcomponent

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <div class="card-body">
    <h4 class="card-title">CRM APP </h4>
    <p class="card-title-desc"></p>

    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link mb-2 active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Close</a>
                <a class="nav-link mb-2" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false" tabindex="-1"> Zapier </a>
                <a class="nav-link mb-2" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" tabindex="-1">Sales Force</a>
                <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false" tabindex="-1">Pipedrive</a>
            
             <!-- SMS Tab -->
             <a class="nav-link" id="v-pills-sms-tab" data-bs-toggle="pill" href="#v-pills-sms" role="tab" aria-controls="v-pills-sms" aria-selected="false">SMS</a>
           
               <!-- New Sales SMS Tab 
               <a class="nav-link" id="v-pills-sales-sms-tab" data-bs-toggle="pill" href="#v-pills-sales-sms" role="tab" aria-controls="v-pills-sales-sms" aria-selected="false">
                   Sales SMS</a>
        -->


            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">

  <!-- SMS Tab Content -->
<div class="tab-pane fade" id="v-pills-sms" role="tabpanel" aria-labelledby="v-pills-sms-tab">
    
    <!-- First Box for SMS Toggle -->
    <div class="sms-box" style="background: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <form id="sms-api-form" method="POST" action="{{ route('save.sms.toggle') }}">
            @csrf
            <div class="mb-3">
                <!-- SMS Toggle Switch -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="enable_sms" name="enable_sms"
                        {{ $smsEnabled ? 'checked' : '' }}>
                    <label class="form-check-label" for="enable_sms">@lang('translation.SMS-Connection')</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    
    <!-- Second Box for Sales SMS Toggle -->
    <div class="sales-sms-box" style="background: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <form id="sales-sms-api-form" method="POST" action="{{ route('save.sales.sms.toggle') }}">
            @csrf
            <div class="mb-3">
                <!-- Sales SMS Toggle Switch -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="enable_sales_sms" name="enable_sales_sms"
                        {{ $salesSmsEnabled ? 'checked' : '' }}>
                    <label class="form-check-label" for="enable_sales_sms">@lang('translation.Sales-SMS-Connection')</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

</div>



                    <!-- New Sales SMS Tab Content -->
 
 




                <!-- Close Tab Content -->
                <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                   
                <form id="close-api-form" method="POST" action="{{ route('save.api.key') }}">
    @csrf
    <input type="hidden" name="type" value="Close">

    <div class="mb-3">
        <label for="api_key" class="form-label">Close API Key</label>
        <input type="text" class="form-control" id="api_key"
               name="api_key" 
               value="{{ isset($appConnection) && isset($appConnection->api_key) ? json_decode($appConnection->api_key)->api_key : '' }}" required>
    </div>

    <!-- Bootstrap Switch for Pending Note -->
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="enable_pending" {{ isset($appConnection) && isset($appConnection->api_key) && json_decode($appConnection->api_key)->pending ? 'checked' : '' }}>
            <label class="form-check-label" for="enable_pending">Enable Pending Note</label>
        </div>
        <input type="text" class="form-control pending-note" id="pending"
               name="pending" 
               value="{{ isset($appConnection) && isset($appConnection->api_key) ? json_decode($appConnection->api_key)->pending : '' }}" 
               style="{{ isset($appConnection) && isset($appConnection->api_key) && json_decode($appConnection->api_key)->pending ? 'opacity: 1;' : 'display: none; opacity: 0.5;' }}" 
               {{ isset($appConnection) && isset($appConnection->api_key) && json_decode($appConnection->api_key)->pending ? '' : 'disabled' }}>
    </div>

    <!-- Bootstrap Switch for Signed Note -->
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="enable_signed" {{ isset($appConnection) && isset($appConnection->api_key) && json_decode($appConnection->api_key)->signed ? 'checked' : '' }}>
            <label class="form-check-label" for="enable_signed">Enable Signed Note</label>
        </div>
        <input type="text" class="form-control signed-note" id="signed"
               name="signed" 
               value="{{ isset($appConnection) && isset($appConnection->api_key) ? json_decode($appConnection->api_key)->signed : '' }}" 
               style="{{ isset($appConnection) && isset($appConnection->api_key) && json_decode($appConnection->api_key)->signed ? 'opacity: 1;' : 'display: none; opacity: 0.5;' }}" 
               {{ isset($appConnection) && isset($appConnection->api_key) && json_decode($appConnection->api_key)->signed ? '' : 'disabled' }}>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const enablePendingSwitch = document.getElementById('enable_pending');
        const pendingInput = document.getElementById('pending');

        const enableSignedSwitch = document.getElementById('enable_signed');
        const signedInput = document.getElementById('signed');

        // Initialize visibility based on existing data
        if (enablePendingSwitch.checked) {
            pendingInput.style.display = 'block';
            pendingInput.disabled = false;
            pendingInput.style.opacity = 1;
        }

        if (enableSignedSwitch.checked) {
            signedInput.style.display = 'block';
            signedInput.disabled = false;
            signedInput.style.opacity = 1;
        }

        // Toggle visibility and enable/disable the Pending Note input field
        enablePendingSwitch.addEventListener('change', function() {
            if (this.checked) {
                pendingInput.style.display = 'block';
                pendingInput.disabled = false;
                pendingInput.required = true; // Optional: make the field required when enabled
                pendingInput.style.opacity = 1;
            } else {
                pendingInput.style.display = 'none';
                pendingInput.disabled = true;
                pendingInput.required = false;
                pendingInput.style.opacity = 0.5;
            }
        });

        // Toggle visibility and enable/disable the Signed Note input field
        enableSignedSwitch.addEventListener('change', function() {
            if (this.checked) {
                signedInput.style.display = 'block';
                signedInput.disabled = false;
                signedInput.required = true; // Optional: make the field required when enabled
                signedInput.style.opacity = 1;
            } else {
                signedInput.style.display = 'none';
                signedInput.disabled = true;
                signedInput.required = false;
                signedInput.style.opacity = 0.5;
            }
        });
    });
</script>

<style>
    .pending-note, .signed-note {
        transition: opacity 0.3s ease-in-out;
    }
</style>




                </div>

                <!-- HubSpot Tab Content -->
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <form id="hubspot-api-form" method="POST" action="{{ route('save.api.key') }}">
                        @csrf
                        <input type="hidden" name="type" value="Zapier">
                        <div class="mb-3">
                            <label for="api_key" class="form-label"> Zapier API Key</label>
                            <input type="text" class="form-control" id="api_key" name="api_key" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>

                <!-- SalesForce Tab Content -->
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                    <form id="salesforce-api-form" method="POST" action="{{ route('save.api.key') }}">
                        @csrf
                        <input type="hidden" name="type" value="Salesforce">
                        <div class="mb-3">
                            <label for="api_key" class="form-label">Salesforce API Key</label>
                            <input type="text" class="form-control" id="api_key" name="api_key" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>

                <!-- Pipedrive Tab Content -->
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <form id="pipedrive-api-form" method="POST" action="{{ route('save.api.key') }}">
                        @csrf
                        <input type="hidden" name="type" value="Pipedrive">
                        <div class="mb-3">
                            <label for="api_key" class="form-label">Pipedrive API Key</label>
                            <input type="text" class="form-control" id="api_key" name="api_key" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Modal -->
<div class="modal fade" id="leadsModal" tabindex="-1" aria-labelledby="leadsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leadsModalLabel">Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search for leads...">
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table id="leadsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>ID</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Leads will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <h3>Add Comment</h3>
                    <textarea id="new-comment" class="form-control" rows="3"></textarea>
                    <button id="add-comment" class="btn btn-success mt-2">Add Comment</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

 



    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        #leadsTable tbody tr.selected {
            background-color: #d2e3f1; /* Light blue background */
        }


        #spinner-overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        #spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
        }

        .ring {
            border: 8px solid transparent;
            border-radius: 50%;
            position: absolute;
            animation: spin 1.5s linear infinite;
        }

        .ring:nth-child(1) {
            width: 120px;
            height: 120px;
            border-top: 8px solid #3498db;
            animation-delay: -0.45s;
        }

        .ring:nth-child(2) {
            width: 100px;
            height: 100px;
            border-right: 8px solid #f39c12;
            animation-delay: -0.3s;
        }

        .ring:nth-child(3) {
            width: 80px;
            height: 80px;
            border-bottom: 8px solid #e74c3c;
            animation-delay: -0.15s;
        }

        .ring:nth-child(4) {
            width: 60px;
            height: 60px;
            border-left: 8px solid #9b59b6;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Spinner Overlay -->
    <div id="spinner-overlay">
        <div id="spinner">
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="ring"></div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const spinnerOverlay = document.getElementById("spinner-overlay");

            // Show the spinner when the page is loading
            spinnerOverlay.style.display = "block";

            window.addEventListener("load", function() {
                // Hide the spinner when the page has fully loaded
                spinnerOverlay.style.display = "none";
            });

            document.addEventListener("ajaxStart", function() {
                // Show the spinner when an AJAX request starts
                spinnerOverlay.style.display = "block";
            });

            document.addEventListener("ajaxStop", function() {
                // Hide the spinner when the AJAX request completes
                spinnerOverlay.style.display = "none";
            });


            $('#sms-api-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'SMS setting saved successfully!',
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to save SMS setting. Please try again.',
                        });
                    }
                });
            });



            $('#sales-sms-api-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Sales SMS setting saved successfully!',
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to save Sales SMS setting. Please try again.',
                        });
                    }
                });
            });



            $('#close-api-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error saving the Close API key. Please try again.',
                        });
                    }
                });
            });

            // Handle the HubSpot form submission
            $('#hubspot-api-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error saving the Zapier API key. Please try again.',
                        });
                    }
                });
            });

            // Handle the Salesforce form submission
            $('#salesforce-api-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error saving the Salesforce API key. Please try again.',
                        });
                    }
                });
            });

            // Handle the Pipedrive form submission
            $('#pipedrive-api-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error saving the Pipedrive API key. Please try again.',
                        });
                    }
                });
            });

          
            // Search functionality 
            
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#leadsTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            let selectedLeadId = null; // Store the selected lead ID globally

            // Handle radio button selection
            $('#leadsTable tbody').on('change', 'input[name="lead-select"]', function() {
                selectedLeadId = $(this).val();
                console.log('Selected Lead ID:', selectedLeadId); // Debugging line
            });

            $('#fetch-leads').on('click', function() {
                let activeTab = $('.nav-pills .active').text().trim();
                let apiKey = $('#api_key').val();

                console.log('Selected CRM Type:', activeTab); // Debugging line

                $.ajax({
                    url: '/get-leads',
                    method: 'GET',
                    data: {
                        type: activeTab,
                        api_key: apiKey // Pass the API key along with the type
                    },
                    success: function(response) {
                     
                        console.log('Leads pasquale fetched successfully:', response); // Debugging line

                        let leadsTableBody = $('#leadsTable tbody');
                        leadsTableBody.empty();

                        // Assuming response.data is the array of leads
                        response.data.forEach(function(lead) {
                            leadsTableBody.append(`
                                <tr>
                                    <td><input type="radio" name="lead-select" value="${lead.id}"></td>
                                    <td>${lead.display_name || lead.name || 'N/A'}</td>
                                    <td>${lead.status_label || 'N/A'}</td>
                                    <td>${lead.id}</td>
                                    <td>${new Date(lead.date_created).toLocaleString()}</td>
                                </tr>
                            `);
                        });

                        $('#leadsModal').modal('show');
                    },
                    error: function(response) {
                        console.error('Error fetching leads:', response); // Debugging line
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch leads. Please try again.',
                        });
                    }
                });
            });

            $('#add-comment').on('click', function() {
                const note = $('#new-comment').val();

                if (!selectedLeadId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No lead selected to add a comment.',
                    });
                    return;
                }

                $.ajax({
                    url: `/add-comment/${selectedLeadId}`,
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data: JSON.stringify({ note: note }),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Comment added successfully.',
                        });
                        $('#new-comment').val('');
                    },
                    error: function(response) {
                        console.error('Error response:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add comment. Please try again.',
                        });
                    }
                });
            });

        // for adding lead  

         $('#add-lead').on('click', function() {
                
                $.ajax({
                    url: '/get-lead-statuses', // Replace with the correct route to fetch statuses
                    method: 'GET',
                    success: function(response) {
                    console.log('Fetched statuses:', response); // Log the entire response for inspection

                    if (Array.isArray(response)) {
                        console.log('Response is an array:', response);
                        let statusOptions = response.map(status => `<option value="${status.id}">${status.label}</option>`).join('');
                        
                        Swal.fire({
                            title: 'Add New Lead',
                            html:
                                '<input id="lead-name" class="swal2-input" placeholder="Lead Name">' +
                                `<select id="lead-status" class="swal2-input">${statusOptions}</select>`,
                            showCancelButton: true,
                            confirmButtonText: 'Add Lead',
                            preConfirm: () => {
                                const name = Swal.getPopup().querySelector('#lead-name').value;
                                const statusId = Swal.getPopup().querySelector('#lead-status').value;
                                if (!name || !statusId) {
                                    Swal.showValidationMessage(`Please enter lead name and select a status`);
                                }
                                return { name: name, status_id: statusId };
                            }
                        });
                    } else if (response.data && Array.isArray(response.data)) {
                        console.log('Response has data array:', response.data);
                        let statusOptions = response.data.map(status => `<option value="${status.id}">${status.label}</option>`).join('');

                        Swal.fire({
                            title: 'Add New Lead',
                            html:
                                '<input id="lead-name" class="swal2-input" placeholder="Lead Name">' +
                                `<select id="lead-status" class="swal2-input">${statusOptions}</select>`,
                            showCancelButton: true,
                            confirmButtonText: 'Add Lead',
                            preConfirm: () => {
                                const name = Swal.getPopup().querySelector('#lead-name').value;
                                const statusId = Swal.getPopup().querySelector('#lead-status').value;
                                if (!name || !statusId) {
                                    Swal.showValidationMessage(`Please enter lead name and select a status`);
                                }
                                return { name: name, status_id: statusId };
                            }
                        });
                    } else {
                        console.error('Unexpected response format:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch lead statuses. Invalid response format.',
                        });
                    }
                },

                    error: function(response) {
                        console.error('Error fetching statuses:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch lead statuses. Please try again.',
                        });
                    }
                });
            });




   

        });
    </script>
@endsection

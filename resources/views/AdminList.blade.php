@extends('layouts.master')
@section('title')
    @lang('translation.Contract-List')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.Contract-List') 
        @endslot
    @endcomponent
 
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 

    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">

    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
     <!--  Arifur change  -->
     <div class="row">
            <div class="col-sm">
                <div class="search-box me-2 d-inline-block">
                    <div class="position-relative">
                        <input type="text" class="form-control" autocomplete="off" id="searchInput" placeholder="Search...">
                        <i class="bx bx-search-alt search-icon"></i>
                    </div>  
                </div>
            </div>


            <div class="col-sm-auto">
                <div class="text-sm-end">
                    <a href="{{ route('registercompany') }}" class="btn btn-primary">
                        @lang('translation.Add New Company')
                    </a>
                </div>
            </div>

    </div>

<!-- Table content --> 
<div class="table-responsive" style="margin-top:10px;">
    <table id="ContractList" class="table">
        <!-- Table header -->
        <thead>
            <tr>
                <th style="text-align: left;">ID</th>
                <th style="text-align: left;">@lang('translation.CompanyName')</th>
                <th style="text-align: left;">@lang('translation.Name')</th>
                <th style="text-align: left;">Email</th>
                <th style="text-align: left;">Password</th>

                <th style="text-align: left; width:50px; ">@lang('translation.Total Sales')</th>

                <th style="text-align: left; width:50px; "> @lang('translation.view') </th>
                
                <th style="text-align: left;">@lang('translation.Created Date')</th>
                <th style="text-align: left; width: 18%">@lang('translation.Action')</th>
            </tr>
        </thead>

        <!-- Table body -->
        <tbody>
            @foreach($users as $user)
                <tr id="row-{{ $user->id }}">
                    <td style="text-align: left;" >{{ $user->id }}</td>
                    <td style="text-align: left;" >{{ $user->company->company_name ?? 'N/A' }}</td>
                    <td style="text-align: left;" >
                        <span class="editable" id="name-{{ $user->id }}">{{ $user->name }}</span>
                        <input type="text" class="form-control d-none" id="input-name-{{ $user->id }}" value="{{ $user->name }}">
                    </td>
                    <td style="text-align: left;" >
                        <span class="editable" id="email-{{ $user->id }}">{{ $user->email }}</span>
                        <input type="email" class="form-control d-none" id="input-email-{{ $user->id }}" value="{{ $user->email }}">
                    </td>
                    <td style="text-align: left;" >
                        <span class="editable" id="password-{{ $user->id }}">••••••</span>
                        <div class="input-group d-none" id="password-group-{{ $user->id }}">
                            <input type="password" class="form-control" id="input-password-{{ $user->id }}" placeholder="Enter new password">
                            <div class="input-group-append">
                                <button class="btn btn-light toggle-password" type="button" onclick="togglePassword({{ $user->id }})">
                                    <i class="mdi mdi-eye-outline" id="toggle-icon-{{ $user->id }}"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                 

                    <!-- <td style="text-align: left;">
                        {{ $user->numberOfSales }} / 
                        <span class="editable-num" id="max-sales-{{ $user->id }}">{{ $user->company->NumOfsales }}</span>
                  
                        <input type="number"  class="form-control form-control-sm d-inline-block d-none"
                         id="input-max-sales-{{ $user->id }}" value="{{ $user->company->NumOfsales }}">
                  
                    </td> -->
                    
                    <td style="text-align: left;">
                        <div class="d-flex align-items-center">
                            <span class="editable-num" id="numberOfSales-{{ $user->id }}">{{ $user->numberOfSales }}</span> /
                            <span class="editable-num" id="max-sales-{{ $user->id }}">{{ $user->company->NumOfsales }}</span>

                            <input type="number" class="form-control form-control-sm d-inline-block max-sales-input d-none"
                                id="input-max-sales-{{ $user->id }}" value="{{ $user->company->NumOfsales }}" style="width: 70px; margin-left: 10px;">
                        </div>
                    </td>

                  
                    <td style="text-align: left; " > 
                        <button class="btn btn-primary btn-sm" onclick="viewSalesDetails({{ $user->id }})">
                            @lang('translation.view')
                        </button> 
                    </td>

                    <td style="text-align: left;" >{{ $user->created_at }}</td>
                    <td style="text-align: left;">
                        <button class="btn btn-primary btn-sm edit-btn" id="edit-btn-{{ $user->id }}" onclick="editRow({{ $user->id }})">
                        @lang('translation.Edit')   </button>
                        <button class="btn btn-success btn-sm d-none save-btn" id="save-btn-{{ $user->id }}" onclick="saveRow({{ $user->id }})">
                        @lang('translation.Save')    </button>
                  
                  <!-- Show the appropriate button based on status  -->

                        @if($user->status === 'active')
                            <button class="btn btn-warning btn-sm" onclick="deactivateUser({{ $user->id }})"> @lang('translation.Deactivate') </button>
                        @else
                            <button class="btn btn-success btn-sm" onclick="activateUser({{ $user->id }})">   @lang('translation.Activate') </button>
                        @endif


                          <!-- Delete Button 
                        <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                        -->
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

 

<div class="modal fade" id="salesDetailsModal" tabindex="-1" aria-labelledby="salesDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="salesDetailsModalLabel">@lang('translation.Sales Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <!-- Nested History Modal with scrollable height matching salesDetailsModal -->
                <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="historyModalLabel">Sales History</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Search Box for History Table -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="historySearchInput" placeholder="Search...">
                                    </div>
                                </div>

                                <!-- History Table -->
                                <div class="table-responsive">
                                    <table class="table" id="historyTable">
                                        <thead>
                                            <tr>
                                                <th>Contract Name</th>
                                                <th>PDF Name</th>
                                                <th>Status</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data populated by AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sales Details Content -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="modalSearchInput" placeholder="Search...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="salesDetailsTable">
                        <thead>
                            <tr>
                                <th>@lang('translation.Name')</th>
                                <th>@lang('translation.Surname')</th>
                                <th>Email</th>
                                <th>@lang('translation.Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data populated by AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<style>

    #historyModal .modal-content {
        max-height: 900px;
        overflow-y: auto;
    }


    #salesDetailsModal .modal-body {
        max-height: 500px;
        overflow-y: auto;
    }

    .max-sales-input {
    display: inline-block;
    vertical-align: middle;
    width: 60px;
}

td .d-flex {
    display: flex;
    align-items: center;
}

.editable-num {
    display: inline-block;
    vertical-align: middle;
}

</style>


<!--  For script 


-->
<script>

$(document).ready(function() {
        // Search functionality for modal
        $('#modalSearchInput').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#salesDetailsTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });


        $('#modalSearchInput').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#salesDetailsTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Search functionality for historyModal
        $('#historySearchInput').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#historyTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });



    });



        function showHistoryModal(email) {
    $.ajax({
        url: '/contract-history/fetch-history',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            email: email
        },
        success: function(response) {
            // Populate the history modal with response data
            $('#historyTable tbody').empty();
            response.history.forEach(function(record) {
                var downloadButton = record.status === 'signed' 
                    ? `<a href="/download-pdf/${record.id}" class="btn btn-primary btn-sm">Download</a>` 
                    : '';

                var row = `
                    <tr>
                        <td>${record.contract_name}</td>
                        <td>${record.selected_pdf_name}</td>
                        <td>${record.status}</td>
                        <td>${downloadButton}</td>
                    </tr>`;
                    
                $('#historyTable tbody').append(row);
            });

            // Show the modal
            $('#historyModal').modal('show');
        },
        error: function() {
            Swal.fire('Error!', 'Failed to fetch history data.', 'error');
        }
    });
}



    function viewSalesDetails(userId) {
        $('#salesDetailsModal').modal('show'); // Show the modal

        // Fetch sales details using AJAX
        $.ajax({
            url: '/sales-details/' + userId,
            method: 'GET',
            success: function(response) {
                // Clear previous table rows
                $('#salesDetailsTable tbody').empty();

                // Populate the table with new data
                response.salesDetails.forEach(function(detail) {
                    // Create activate/deactivate button based on status
                    var actionButtons = `
                        ${detail.status === 'active' 
                            ? '<button class="btn btn-warning btn-sm" onclick="deactivateSales(\'' + detail.email + '\')">@lang('translation.Deactivate')</button>'
                            : '<button class="btn btn-success btn-sm" onclick="activateSales(\'' + detail.email + '\')">@lang('translation.Activate')</button>'
                        }
                        <button class="btn btn-primary btn-sm ms-2" onclick="showHistoryModal('${detail.email}')">History</button>
                    `;

                    // Construct the row with name, surname, email, and action buttons
                    var row = `
                        <tr>
                            <td>${detail.name}</td>
                            <td>${detail.surname}</td>
                            <td>${detail.email}</td>
                            <td>${actionButtons}</td>
                        </tr>`;
                    
                    $('#salesDetailsTable tbody').append(row);
                });
            },
            error: function() {
                alert('Failed to fetch sales details.');
            }
        });
    }

        


// function viewSalesDetails(userId) {
//         $('#salesDetailsModal').modal('show'); // Show the modal

//         // Fetch sales details using AJAX
//         $.ajax({
//             url: '/sales-details/' + userId,
//             method: 'GET',
//             success: function(response) {
//                 // Clear previous table rows
//                 $('#salesDetailsTable tbody').empty();

//                 // Populate the table with new data
//                 response.salesDetails.forEach(function(detail) {
//                     var row = `
//                         <tr>
//                             <td>${detail.name}</td>
//                             <td>${detail.surname}</td>
//                             <td>${detail.email}</td>
//                             <td>
//                                 ${detail.status === 'active' 
//                                     ? '<button class="btn btn-warning btn-sm" onclick="deactivateSales(\'' + detail.email + '\')"> @lang('translation.Deactivate')</button>'
//                                     : '<button class="btn btn-success btn-sm" onclick="activateSales(\'' + detail.email + '\')">   @lang('translation.Activate') </button>'
//                                 }
//                             </td>
//                         </tr>`;
//                     $('#salesDetailsTable tbody').append(row);
//                 });

//             },
//             error: function() {
//                 alert('Failed to fetch sales details.');
//             }
//         });
//     }


    function activateSales(email) {
            $.ajax({
                url: '/sales-details/activate',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', 'Sales details activated.', 'success').then(() => {
                            // Find the row that contains the activated email
                            const row = $(`#salesDetailsTable tbody tr:contains(${email})`);
                            
                            // Change the button to 'Deactivate'
                            row.find('td:last-child').html(
                                `<button class="btn btn-warning btn-sm" onclick="deactivateSales('${email}')"> @lang('translation.Deactivate')</button>`
                            );
                        });
                    } else {
                        // If the limit is exceeded, show the SweetAlert popup
                        Swal.fire({
                            title: 'Warning!',
                            text: response.message,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to activate sales details.', 'error');
                }
            });
        }

            
//     function activateSales(email) {
//     $.ajax({
//         url: '/sales-details/activate',
//         method: 'POST',
//         data: {
//             _token: '{{ csrf_token() }}',
//             email: email
//         },
//         success: function(response) {
//             if (response.success) {
//                 Swal.fire('Success!', 'Sales details activated.', 'success').then(() => {
//                     // Find the row that contains the activated email
//                     const row = $(`#salesDetailsTable tbody tr:contains(${email})`);
                    
//                     // Change the button to 'Deactivate'
//                     row.find('td:last-child').html(
//                         `<button class="btn btn-warning btn-sm" onclick="deactivateSales('${email}')"> @lang('translation.Deactivate')</button>`
//                     );
//                 });
//             } else {
//                 Swal.fire('Error!', 'Failed to activate sales details.', 'error');
//             }
//         },
//         error: function() {
//             Swal.fire('Error!', 'Failed to activate sales details.', 'error');
//         }
//     });
// }

function deactivateSales(email) {
    $.ajax({
        url: '/sales-details/deactivate',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            email: email
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('Success!', 'Sales details deactivated.', 'success').then(() => {
                    // Find the row that contains the deactivated email
                    const row = $(`#salesDetailsTable tbody tr:contains(${email})`);
                    
                    // Change the button to 'Activate'
                    row.find('td:last-child').html(
                        `<button class="btn btn-success btn-sm" onclick="activateSales('${email}')"> @lang('translation.Activate')</button>`
                    );
                });
            } else {
                Swal.fire('Error!', 'Failed to deactivate sales details.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Failed to deactivate sales details.', 'error');
        }
    });
}




function editMaxSales(id) {
    // Show input field for max sales and hide span
    document.getElementById('max-sales-' + id).classList.add('d-none');
    document.getElementById('input-max-sales-' + id).classList.remove('d-none');
    
    // Show Save button
    document.getElementById('save-num-' + id).classList.remove('d-none');
    document.getElementById('edit-max-btn-' + id).classList.add('d-none');
}



 

function saveMaxSales(id, currentMaxSales) {
    const maxSales = document.getElementById('input-max-sales-' + id).value;
    
    // Fetch the count of active salespersons
    $.ajax({
        url: '/sales-details/activeCount/' + id,
        method: 'GET',
        success: function(response) {
            const activeSalesCount = response.activeCount;

            // Check if the new max sales value is lower than the number of active salespersons
            if (maxSales < activeSalesCount) {
                Swal.fire({
                    title: 'Warning!',
                    text: `You have ${activeSalesCount} active salespersons. Please deactivate ${activeSalesCount - maxSales} before reducing the max sales number.`,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                // Proceed with saving the max sales number
                $.ajax({
                    url: '/companies/updateMaxSales/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        max_sales: maxSales
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the view with the new value
                            document.getElementById('max-sales-' + id).textContent = maxSales;

                            // Hide input field and show span again
                            document.getElementById('max-sales-' + id).classList.remove('d-none');
                            document.getElementById('input-max-sales-' + id).classList.add('d-none');
                            document.getElementById('save-btn-' + id).classList.add('d-none');
                            document.getElementById('edit-btn-' + id).classList.remove('d-none');

                            // Show SweetAlert with success message
                            Swal.fire({
                                title: 'Success!',
                                text: 'Max sales number updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to update max sales number.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to retrieve active sales count.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}



// function saveMaxSales(id) {
//     const maxSales = document.getElementById('input-max-sales-' + id).value;

//     $.ajax({
//         url: '/companies/updateMaxSales/' + id,
//         method: 'POST',
//         data: {
//             _token: '{{ csrf_token() }}',
//             max_sales: maxSales
//         },
//         success: function(response) {
//             if (response.success) {
//                 // Update the view with the new value
//                 document.getElementById('max-sales-' + id).textContent = maxSales;

//                 // Hide input field and show span again
//                 document.getElementById('max-sales-' + id).classList.remove('d-none');
//                 document.getElementById('input-max-sales-' + id).classList.add('d-none');
//                 document.getElementById('save-num-' + id).classList.add('d-none');
//                 document.getElementById('edit-max-btn-' + id).classList.remove('d-none');

//                 // Show SweetAlert with success message
//                 Swal.fire({
//                     title: 'Success!',
//                     text: 'Max sales number updated successfully.',
//                     icon: 'success',
//                     confirmButtonText: 'OK'
//                 });
//             } else {
//                 Swal.fire({
//                     title: 'Error!',
//                     text: 'Failed to update max sales number.',
//                     icon: 'error',
//                     confirmButtonText: 'OK'
//                 });
//             }
//         }
//     });
// }



function deleteUser(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this user?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/users/delete/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    });
}

function deactivateUser(id) {
    $.ajax({
        url: '/users/deactivate/' + id,
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page when user clicks "OK"
                        location.reload();
                    }
                });
                // Change button from Deactivate to Activate
                $('#row-' + id + ' td:last-child').html(`
                    <button class="btn btn-success btn-sm" onclick="activateUser(${id})">Activate</button>
                `);
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}

function activateUser(id) {
    $.ajax({
        url: '/users/activate/' + id,
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page when user clicks "OK"
                        location.reload();
                    }
                });
                // Change button from Activate to Deactivate
                $('#row-' + id + ' td:last-child').html(`
                    <button class="btn btn-warning btn-sm" onclick="deactivateUser(${id})">Deactivate</button>
                `);
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}


function editRow(id) {
    // Show input fields for user details
    document.getElementById('input-name-' + id).classList.remove('d-none');
    document.getElementById('input-email-' + id).classList.remove('d-none');
    document.getElementById('password-group-' + id).classList.remove('d-none'); // Show password group

    // Show input for max sales
    document.getElementById('max-sales-' + id).classList.add('d-none');
    document.getElementById('input-max-sales-' + id).classList.remove('d-none');
    
    // Hide text spans
    document.getElementById('name-' + id).classList.add('d-none');
    document.getElementById('email-' + id).classList.add('d-none');
    document.getElementById('password-' + id).classList.add('d-none');
    
    // Toggle buttons
    document.getElementById('edit-btn-' + id).classList.add('d-none');
    document.getElementById('save-btn-' + id).classList.remove('d-none');
}



function saveRow(id) {
    const name = document.getElementById('input-name-' + id).value;
    const email = document.getElementById('input-email-' + id).value;
    const password = document.getElementById('input-password-' + id).value;
    const maxSales = document.getElementById('input-max-sales-' + id).value;

    // Fetch the count of active salespersons
    $.ajax({
        url: '/sales-details/activeCount/' + id,
        method: 'GET',
        success: function(response) {
            const activeSalesCount = response.activeCount;

            // Check if the new max sales value is lower than the number of active salespersons
            if (maxSales < activeSalesCount) {
                Swal.fire({
                    title: 'Warning!',
                    text: `You have ${activeSalesCount} active salespersons. Please deactivate ${activeSalesCount - maxSales} salespersons before reducing the max sales number.`,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                // Proceed with saving the user details and max sales number
                $.ajax({
                    url: '/users/update/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        email: email,
                        password: password,
                        max_sales: maxSales
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the table with the new values
                            document.getElementById('name-' + id).textContent = name;
                            document.getElementById('email-' + id).textContent = email;
                            document.getElementById('max-sales-' + id).textContent = maxSales;

                            // Hide input fields and show spans again
                            document.getElementById('input-name-' + id).classList.add('d-none');
                            document.getElementById('input-email-' + id).classList.add('d-none');
                            document.getElementById('password-group-' + id).classList.add('d-none');
                            document.getElementById('input-max-sales-' + id).classList.add('d-none');
                            document.getElementById('name-' + id).classList.remove('d-none');
                            document.getElementById('email-' + id).classList.remove('d-none');
                            document.getElementById('max-sales-' + id).classList.remove('d-none');

                            // Toggle buttons
                            document.getElementById('edit-btn-' + id).classList.remove('d-none');
                            document.getElementById('save-btn-' + id).classList.add('d-none');

                            // Show SweetAlert with success message
                            Swal.fire({
                                title: 'Success!',
                                text: 'User and company sales information updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to update user.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to retrieve active sales count.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}


function togglePassword(id) {
    const passwordField = document.getElementById('input-password-' + id);
    const toggleIcon = document.getElementById('toggle-icon-' + id);

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('mdi-eye-outline');
        toggleIcon.classList.add('mdi-eye-off-outline');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('mdi-eye-off-outline');
        toggleIcon.classList.add('mdi-eye-outline');
    }
}
</script>

<!-- pagination -->

<style>

.dataTables_wrapper .dataTables_paginate {
        margin-top: 10px;
    }

    .dataTables_wrapper .dataTables_length {
        margin: 8px;
        margin-left: 8px;
    }

    .float-start {
        float: left !important;
    }

    .float-end {
        float: right !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        padding: 6px 12px;
        margin-left: 2px;
        margin-right: 2px;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #333;
        background-color: #fff;
        text-decoration: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #eee;
        border-color: #ddd;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #ddd;
    }

    
    </style>

    <!-- for delete -->
    <script>
                       
                       
        function confirmDelete(contractId) {
                            // Display SweetAlert2 confirmation popup
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "Do you want to delete this contract?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: 'No, cancel!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // If user clicks 'Yes', submit the form
                                    document.getElementById('delete-form-' + contractId).submit();
                                } else {
                                    // If user clicks 'No', do nothing
                                    return false;
                                }
                            });
        }
                
 
 $(document).ready(function() {
        let table = new DataTable('#ContractList', {
            pagingType: 'full_numbers',
            dom: '<"top"f>rt<"bottom"<"float-start"l><"float-end"p>><"clear">',
            language: {
                paginate: {
                    first: '<<',
                    last: '>>',
                    next: '@lang('translation.NEXT')',
                    previous: '@lang('translation.PREVIOUS')'
                },
                 lengthMenu: "@lang('translation.SHOW_ENTRIES', ['entries' => '_MENU_'])"
            }
        });

        $('.dt-search').hide();
        $('.dataTables_info').addClass('right-info');

        $('#searchInput').on('keyup', function() {
            table.search($(this).val()).draw();
        });
    });


 </script>

  <style>
    #exampleModalNew .modal-content {
        background-color: black;
        color: white; /* Optionally, change the text color */
    }
</style>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
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
    });
</script>

@endsection

@extends('layouts.master')
@section('title')
    @lang('translation.Product-List')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title' )
        @lang('translation.Sales List')

        @endslot
    @endcomponent

<!-- -----------------------------  --> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

   <!-- intl-tel-input CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">

<!-- intl-tel-input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>


<div class="container-fluid">
    <div class="row">
     
    <div class="col-md-6">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title"> @lang('translation.Sales Details')  </h5>
        </div>
        <div class="card-body">
        <form id="salesDetailsForm" action="/save-sales-details{{ isset($salesDetails) ? '/' . $salesDetails->id : '' }}" method="POST">
                @csrf
                @if(isset($salesDetails))
                    @method('POST')
                @endif
                <!-- Your form fields here -->
                <div class="mb-3">
                    <label for="name" class="form-label">@lang('translation.Name')</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ isset($salesDetails) ? $salesDetails->name : '' }}" required>
                </div>
                <div class="mb-3">
                    <label for="surname" class="form-label">@lang('translation.Surname')</label>
                    <input type="text" class="form-control" id="surname" name="surname" value="{{ isset($salesDetails) ? $salesDetails->surname : '' }}">
                </div>
                <div class="mb-3">
                    <label for="nickname" class="form-label">@lang('translation.Nick Name')</label>
                    <input type="text" class="form-control" id="nickname" name="nickname" value="{{ isset($salesDetails) ? $salesDetails->nickname : '' }}" required>
                </div>

                
                <!-- <div class="mb-3">
                    <label for="phone" class="form-label">@lang('translation.Phone')</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ isset($salesDetails) ? $salesDetails->phone : '' }}" required>
                </div> -->


                <div class="mb-3">
                    <label for="phone" class="form-label">@lang('translation.Phone')</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ isset($salesDetails) ? $salesDetails->phone : '' }}" required>
                </div>



                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ isset($salesDetails) ? $salesDetails->email : '' }}" required>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password : </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="newpassword" name="password" required>
                                <button class="btn btn-outline-secondary show-password" type="button">
                                    Show
                                </button>
                                <button class="btn btn-outline-secondary hide-password" type="button">
                                    Hide
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" onClick=generatePass() style="margin-top:27px;">
                        @lang('translation.Generate Password')

                        </button>
                        <script>
                            function generatePass() {
                                var pass = '';
                                var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$';

                                for (let i = 0; i < 8; i++) {
                                    var char = Math.floor(Math.random() * str.length);
                                    pass += str.charAt(char);
                                }

                                console.log('new password generated:', pass);
                                document.getElementById("newpassword").value = pass;
                            }
                        </script>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">@lang('translation.Description')</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ isset($salesDetails) ? $salesDetails->description : '' }}</textarea>
                </div>
            </form>
        </div>

           <!-- Save button for Sales Details
        <div class="text-end">
             <button  type="submit" form="salesDetailsForm" class="btn btn-primary">
                {{ isset($salesDetails) ? 'Update' : 'Save' }} Sales Details</button>
        </div> 
         -->

        <div class="text-end">
            <button type="submit" form="salesDetailsForm" class="btn btn-primary">
                {{ isset($salesDetails) ? __('translation.Update Sales Details') : __('translation.Save Sales Details') }}
            </button>
        </div>


        </div>
    </div>

<!-- Include Toastr CSS and JS   -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            initialCountry: "it",  // Set Italy as the default country
            preferredCountries: ['it'],  // Set Italy as the top preferred country
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        // When the form is submitted, get the full number and update the input value
        var form = document.querySelector("#salesDetailsForm");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            var fullNumber = iti.getNumber();  // Get the full phone number with country code
            input.value = fullNumber;  // Set the full number into the input field before submitting the form
            form.submit();  // Submit the form
        });
    });
</script>



 
    <!-- Other head content -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
    <!-- Your body content -->

    @if(Session::has('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: "{{ Session::get('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/Sales-Details/' + {{ $salesDetails->id }} ; 
                }
            });
        </script>
    @endif

    @if($errors->has('email'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Email already exists. Please choose a different one.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if($errors->has('name'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'name already exists in your company. Please choose a different one.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if($errors->has('nickname'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Nickname already exists. Please choose a different one.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
 

 

            <script>
          
                $(document).ready(function() {
                        $(".show-password").on('click', function() {
                            var passwordInput = $(this).siblings('input[type="password"]');
                            passwordInput.attr("type", "text");
                            $(this).hide();
                            $(this).siblings('.hide-password').show();
                        });

                        $(".hide-password").on('click', function() {
                            var passwordInput = $(this).siblings('input[type="text"]');
                            passwordInput.attr("type", "password");
                            $(this).hide();
                            $(this).siblings('.show-password').show();
                        });
                    

            
                    // Function to generate a random alphanumeric password
                    function generatePassword(length) {
                        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                        let password = '';
                       
                        for (let i = 0; i < length; i++) {
                            password += characters.charAt(Math.floor(Math.random() * characters.length));
                        }
                        return password;
                    }

                 
                    // Function to handle click event of the generate password button
                    $('#generatePasswordBtn').click(function() {
                        // Generate an 8-digit alphanumeric password
                        const password = generatePassword(8);
                        console.log('i  am inside the password generator',password );
                        // Set the generated password to the password input field
                        $('#password').val(password);
                    });

                  

                });
            </script>



        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('translation.Product Details')</h5>
                </div>
                <div class="card-body">
                    <div class="col-sm">
                        <div class="search-box me-2 d-inline-block" style="margin-left:8px;">
                            <div class="position-relative">
                                <input type="text" class="form-control" autocomplete="off" id="searchInput" placeholder="Search...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Table for Product Details -->
                    <!-- <table id="ContractList" class="table-responsive" style="margin-top:10px;">
                        <thead>
                            <tr>
                                <th style="text-align: left;">ID</th>
                                <th style="text-align: left;">Product Name</th>
                                <th style="text-align: left;">Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td style="text-align: left;">{{ $product->id }}</td>
                                <td style="text-align: left;">{{ $product->product_name }}</td>
                                <td style="text-align: left;">

                                 


                                    <input type="checkbox" class="product-checkbox" data-product-id="{{ $product->id }}" @if($product->isSelected) checked @endif>
                                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table> -->


                    <!-- Table for Product Details -->
                    <table id="ContractList" class="table-responsive" style="margin-top:10px;">
                        <thead>
                            <tr>
                                <th style="text-align: left;">ID</th>
                                <th style="text-align: left;">@lang('translation.Product Name')</th>
                                <th style="text-align: left;">@lang('translation.Select')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td style="text-align: left;">{{ $product->id }}</td>
                                <td style="text-align: left;">{{ $product->product_name }}</td>
                                <td style="text-align: left;">
                                    <div class="form-check form-switch">
                                        <input class="product-checkbox form-check-input" type="checkbox" data-product-id="{{ $product->id }}" @if($product->isSelected) checked @endif>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        </div>


        
</div>
</div>




 <!--for toast -->
 <!-- for toast -->
 <div id="liveToast" class="toast fade hide" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; background-color: white; color: black;">
    <div class="toast-header" style="background-color: white; color: black; border-bottom: 1px solid #e6e6e6;">
        <img src="" alt="" class="me-2" height="18">
        <strong class="me-auto">Information</strong>
        <small>Few mins ago</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        Hello, world! This is a toast message.
    </div>
</div>



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

  

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
<script>

$(document).ready(function() {
        let table = new DataTable('#ContractList', {
            pagingType: 'full_numbers',
            dom: '<"top"f>rt<"bottom"<"float-start"l><"float-end"p>><"clear">',
            language: {
                paginate: {
                    first: '<<',
                    last: '>>',
                    next: 'Next',
                    previous: 'Previous'
                },
                lengthMenu: "Show _MENU_ entries"
            }
        });

        $('.dt-search').hide();
        $('.dataTables_info').addClass('right-info');

        $('#searchInput').on('keyup', function() {
            table.search($(this).val()).draw();
        });
    });

    $(document).ready(function() {
        // Function to handle AJAX call for adding or removing products
        function updateProductStatus(productId, salesDetailsId, isChecked) {
            $.ajax({
                url: '/update-product-status',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    sales_details_id: salesDetailsId,
                    product_id: productId,
                    is_checked: isChecked ? 1 : 0
                },
                success: function(response) {
                    if (isChecked) {
                        showToast('Product added successfully');
                    } else {
                        showToast('Product removed successfully');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error updating product status');
                }
            });
        }

        // for the few second toast 
        function showToast(message) {
            // Get the toast element
            var toast = $('#liveToast');

            // Update the toast body with the message
            toast.find('.toast-body').text(message);

            // Show the toast
            toast.removeClass('hide').addClass('show');

            // Automatically hide the toast after a certain duration (e.g., 5 seconds)
            setTimeout(function() {
                toast.removeClass('show').addClass('hide');
            }, 5000); // 5000 milliseconds = 5 seconds
        }


        // Event listener for clicking on checkbox
        $('.product-checkbox').on('change', function() {
            var productId = $(this).data('product-id');
            var salesDetailsId = "{{ $salesDetails->id }}";
            var isChecked = $(this).is(':checked');

            // Call the function to update product status 
            updateProductStatus(productId, salesDetailsId, isChecked);
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Make an AJAX call to fetch product status
        var salesid = "{{ $salesDetails->id }}";
        $.ajax({
            url: '/sales.details.displayChecked',
            type: 'GET',
            dataType: 'json',
            data: {
                salesDetailsId: salesid // Replace with the actual sales ID
            },
            success: function(response) {
                // Update checkboxes based on the response
                response.products.forEach(function(product) {
                    var checkbox = $('.product-checkbox[data-product-id="' + product.id + '"]');
                    if (product.isSelected) {
                        checkbox.prop('checked', true);
                    } else {
                        checkbox.prop('checked', false);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

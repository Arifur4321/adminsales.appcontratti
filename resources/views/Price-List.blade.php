
@extends('layouts.master')
@section('title')
    @lang('translation.arifurtable')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.Price List')

        @endslot
    @endcomponent
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <!-- SweetAlert2 -->
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
                    <button type="button" class="btn btn-primary" onclick="redirectToEditPrice()"> @lang('translation.Add New Price') </button>
                </div>
            </div>
    </div>


<script>

    function redirectToEditPrice() {
        // Redirect to the Add-New-Price page
        window.location.href = 'createpricewithupdate';
    }

    function redirectToNewPricePage() {
        // Redirect to the Add-New-Price page
        window.location.href = 'Add-New-Price';
    }
</script>

<!-- Table content -->
<div class="table-responsive" style="margin-top:10px;">
<table id="PriceList" class="table">
    <!-- Table header -->
    <thead>
        <tr>
            <th style="text-align: left;">ID</th>
            <th style="text-align: left;">@lang('translation.Price Name')</th>
            <th style="text-align: left;">@lang('translation.User Name')</th>
            <th style="text-align: left;">@lang('translation.Created Date')</th>
            <th style="text-align: left;">@lang('translation.Updated Date')</th>
            <th style="text-align: left; width: 18%">@lang('translation.Action')</th>

        </tr>
    </thead>
    <!-- Table body  -->
    <tbody>
        @foreach ($priceLists as $price)
        
        <tr>
            <td  style="text-align: left;">{{ $price->id }}</td>
            <td  style="text-align: left;">{{ $price->pricename }}</td>
            <td  style="text-align: left;">{{ Auth::user()->name }}</td>
            <td  style="text-align: left;">{{ $price->created_at }}</td>
            <td  style="text-align: left;">{{ $price->updated_at }}</td>
            <td  style="text-align: left;">
     
            <div class="btn-toolbar">
                                <button class="btn btn-primary" 
                                onclick="editPrice({{ $price->id }})">  @lang('translation.Edit') </button>

                        <button type="button" style="margin-left:2px;"   class="btn btn-danger waves-effect waves-light" 
                        onclick="deletePrice({{ $price->id }})">
                        <i class="bx bx-block font-size-16 align-middle me-2"></i>   @lang('translation.Delete')
                        </button>
            
                                
                </div>


                <!-- <div class="dropdown">
                    <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-horizontal font-size-18"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
               
                        <a href="#" class="dropdown-item edit-list" onclick="editPrice({{ $price->id }})">
                            <i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit
                        </a>
                  
                        <a href="#" class="dropdown-item delete-list" onclick="deletePrice({{ $price->id }})">
                            <i class="mdi mdi-delete font-size-16 text-danger me-1"></i> Delete
                        </a>

                    </div>
                </div> -->

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>


<!-- For pagination  -->
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



<script>
//    let table = new DataTable('#ContractList');
$(document).ready(function() {
        let table = new DataTable('#PriceList', {
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


<script>
    function editPrice(id) {
        window.location.href = "/edit-price/" + id;
    }

// function deletePrice(id) {
//     if (confirm('Are you sure you want to delete this price list?')) {
//         var csrfToken = $('meta[name="csrf-token"]').attr('content');
//         $.ajax({
//             url: '/price-lists/' + id,
//             type: 'DELETE',
//             headers: {
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             success: function(response) {
//                 // Remove the row from the table upon successful deletion
//                 // $('#PriceList tr[data-id="' + id + '"]').remove();
//                 alert('Data deleted successfully from the price List!');
//                 window.location.href = "Price-List";
//             },
//             error: function(xhr, status, error) {
//                 console.error(error);
//                 alert('Error occurred while deleting the price list.');
//             }
//         });
//     }
// }


function deletePrice(id) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this price list?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/price-lists/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'The price list has been deleted.',
                            'success'
                        ).then(() => {
                            window.location.href = "Price-List";
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            'Error occurred while deleting the price list.',
                            'error'
                        );
                    }
                });
            } else {
                // If user clicks 'No', do nothing
                return false;
            }
        });
    }
    
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

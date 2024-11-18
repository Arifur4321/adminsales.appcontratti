@extends('layouts.master')
@section('title')
    @lang('translation.Contract-History')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.Contract History')
        @endslot
    @endcomponent

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/kTc/4z4a8GwH5dpQdTtnA5a6K9o5PyzT7c1w1/iUdbVZkJ5CKPxud4Up+h0mRJg4EXrENzjsuQkQA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="row">
        <div class="col-sm">
            <div class="search-box me-2 d-inline-block" style="margin-left:8px;">
                <div class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" id="searchInput" placeholder="Search...">
                    <i class="bx bx-search-alt search-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-auto" style="margin-right:8px;">
            <div class="text-sm-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                @lang('translation.All Sales')  <i class="mdi mdi-chevron-down"></i></button>

                <div class="dropdown-menu p-3"  style="width: 300px; max-height: 250px; overflow-y: auto;"  >
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="dropdownSearch" placeholder="Search sales...">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="selectAllSales">
                        <label class="form-check-label" for="selectAllSales">
                            Select All 
                        </label>
                    </div>
                    <div id="salesDropdownList" style="max-height: 300px; overflow-y: auto;">
                        @php
                            $addedSalesNames = [];
                        @endphp
                        @foreach($salesListDraft as $item)
                            @if($item->salesDetails && !in_array($item->salesDetails->name, $addedSalesNames))
                                <div class="form-check">
                                    <input class="form-check-input sales-checkbox" type="checkbox" value="{{ $item->salesDetails->name }}" id="salesCheck{{ $item->salesDetails->id }}">
                                    <label class="form-check-label" for="salesCheck{{ $item->salesDetails->id }}">
                                        {{ $item->salesDetails->name }}
                                    </label>
                                </div>
                                @php
                                    $addedSalesNames[] = $item->salesDetails->name;
                                @endphp
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
 
    </div>

    <div class="table-responsive" style="margin-top:10px;">
        <table id="ContractList" class="table">
            <thead>
                <tr>
                

                    <th style="text-align: left;">ID</th>
                    <th style="text-align: left;" class="sales-column">@lang('translation.Sales')</th>
                    <th style="text-align: left;">@lang('translation.PDF Name')</th>
                    <th style="text-align: left;">@lang('translation.Contract Name')</th>
                    <th style="text-align: left;">@lang('translation.Recipient Email')</th>
                    <th style="text-align: left;">@lang('translation.Status')</th>
                    <th style="text-align: left; width: 18%;">@lang('translation.Action')</th>

                </tr>
            </thead>
            <tbody>
                @foreach($salesListDraft as $item)
                    <tr data-sales-name="{{ $item->salesDetails->name ?? 'Unknown' }}">
                        <td style="text-align: left;">{{ $item->id }}</td>
                        <td style="text-align: left;" class="sales-column">{{ $item->salesDetails->name ?? 'Unknown' }}</td>
                        <td style="text-align: left;">{{ $item->selected_pdf_name }}</td>
                        <td style="text-align: left;">{{ $item->contract_name }}</td>
                        <td style="text-align: left;">{{ $item->recipient_email }}</td>
                        <td style="text-align: left;" class="
                            @if($item->status == 'pending') 
                                text-danger
                            @elseif($item->status == 'viewed') 
                                text-warning
                            @elseif($item->status == 'signed') 
                                text-success
                            @else 
                                text-secondary
                            @endif">
                            {{ $item->status }}
                        </td>
                        <td style="text-align: left;">
                            <div class="btn-toolbar">
                                @if($item->status == 'signed')
                                    <button onclick="openSignedPDF('{{ $item->id }}')" class="btn btn-success">PDF</button>
                                @else
                                    <!-- Comment out Edit button -->
                                @endif
                                <button type="button" style="margin-left:2px;" onclick="DeleteSalesContract('{{ $item->id }}')" class="btn btn-danger waves-effect waves-light">
                                    <i class="bx bx-block font-size-16 align-middle me-2"></i> @lang('translation.Delete')
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function openSignedPDF(id) {
            $.ajax({
                url: '/contract/get-signed-pdf-url/' + id,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        window.open(response.file_url, '_blank');
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error occurred while trying to retrieve the signed PDF URL.');
                }
            });
        }

        $(document).ready(function() {
            let table = new DataTable('#ContractList', {
                pagingType: 'full_numbers',
                dom: '<"top">rt<"bottom"<"float-start"l><"float-end"p>><"clear">',
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

            $('#searchInput').on('keyup', function() {
                table.search($(this).val()).draw();
            });

            $('#dropdownSearch').on('keyup', function() {
                let searchValue = $(this).val().toLowerCase();
                $('#salesDropdownList .form-check').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
                });
            });

            $('#selectAllSales').on('change', function() {
                let isChecked = $(this).is(':checked');
                $('.sales-checkbox').prop('checked', isChecked).trigger('change');
            });

            $('.sales-checkbox').on('change', function() {
                let selectedNames = $('.sales-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedNames.length === 0) {
                    table.column('.sales-column').search('').draw();
                } else {
                    table.column('.sales-column').search(selectedNames.join('|'), true, false).draw();
                }
            });
        });

        function DeleteSalesContract(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this record?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/sales-list-draft/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Record deleted successfully.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the record.',
                                'error'
                            );
                            console.error('Error deleting record:', error);
                        }
                    });
                }
            });
        }

        function EditSalesContract(id) {
            window.location.href = "/Edit-New-Contracts/" + id;
        }
    </script>
@endsection

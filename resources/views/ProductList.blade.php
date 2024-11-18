@extends('layouts.master')
@section('title')
    @lang('translation.Product-List')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.Product List')
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
     <div class="row"  id="firstid">
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
       
            <button type="button" class="btn btn-primary" onclick="openModalNew()">@lang('translation.Add New Product')</button>

        </div>
    </div>
</div>


<!-- product list Modal -->
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelNew">@lang('translation.New Product')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="productFormNew">
              
                    <div class="mb-3">
                        <label for="product-name-new" class="col-form-label">@lang('translation.Product Name'):</label>
                        <input type="text" class="form-control" id="product-name-new">
                    </div>
                    <div class="mb-3">
                        <label for="description-new" class="col-form-label">@lang('translation.Description'):</label>
                        <textarea class="form-control" id="description-new"></textarea>
                    </div>

                </form>
            
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('translation.Close')</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()"> @lang('translation.Save Product')  </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">  @lang('translation.Edit Product')   </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editVariableForm">
          <input type="hidden" id="variable-id">
          <div class="mb-3">
            <label for="variable-name" class="form-label"> @lang('translation.Product Name')  </label>
            <input type="text" class="form-control" id="variable-name" required>
          </div>
     
          <div class="mb-3">
            <label for="description" class="form-label">  @lang('translation.Description') </label>
            <textarea class="form-control" id="description" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> @lang('translation.Close') </button>
        <button type="button" class="btn btn-primary" onclick="editVariable()">@lang('translation.Save Product')</button>
      </div>
    </div>
  </div>
</div>


<script>
    
    function saveProduct() {
        
        // Get form data
        var productName = $('#product-name-new').val();
        var description = $('#description-new').val();

        // Basic validation
        if (!productName || !description   ) {
            console.error('All fields must be filled out.');
            return;
        }

        // Get the CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Send data to the server using AJAX
        $.ajax({
            url: '/save-product',
            type: 'POST',
            data: {
                productName: productName,
                description: description,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                // Handle success
                console.log('Data saved successfully.');
                // Optionally, close the modal or perform other actions.
                location.reload();
            },
            error: function (error) {
                // Handle error
                console.error('Error saving data:', error);
            }
        });

        $('#exampleModalNew').modal('hide');
    }


</script>

<!-- arifur for search -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Reference to the input field and the table
        var $searchInput = $('#searchInput');
        var $table = $('#ContractList');

        // Event listener for keyup on the search input
        $searchInput.on('keyup', function () {
            var searchText = $(this).val().toLowerCase();

            // Filter the table rows based on the search text
            $table.find('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });
    });


    function openModalNew() {
        // Using Bootstrap's JavaScript to open the product list modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModalNew'));
        myModal.show();
    }

    $('#exampleModalNew').modal('hide');


    function openModal(variableID, variableName,description) {
    // Using Bootstrap's JavaScript to open the modal
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();

            // Set values in the modal form
            document.getElementById('variable-id').value = variableID;
            document.getElementById('variable-name').value = variableName;
            
            document.getElementById('description').value = description;
        }

        // AJAX method to handle form submission for editing
        function editVariable() {
            var variableID = document.getElementById('variable-id').value;
            var variableName = document.getElementById('variable-name').value;
           
            var description = document.getElementById('description').value;

            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: '/edit-variable/' + variableID,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    product_name: variableName,
               
                    description: description
                },
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    location.reload();
                },
                error: function(error) {
                    // Handle error response
                    console.error('Error editing variable:', error.responseText);
                }
            });
        }


</script>

<script>
    function redirectTocreatecontract() {
        // Redirect to the route associated with createcontract.blade.php
        window.location.href = "/createcontract"; // Replace with your actual route path
    }
</script>

<!-- Modal -->
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
             
        <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">CKEditor</h4>
                    <p class="card-title-desc">CKEditor is a powerful WYSIWYG text editor.</p>

                    <form id="contractFormmodal" method="post">
                        <div class="mb-3">
                            <label for="title" class="form-label">Contract Name</label>
                            <input type="text" class="form-control" id="titlemodal" name="titlemodal">
                        </div>
                        <textarea id="editormodal" name="editormodal"></textarea>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveDatamodal()">Save Project</button>
            </div>
        </div>
    </div>
</div>

    <!-- For Main table -->
    <div class="table-responsive" style="margin-top:10px;">
    <table id="ContractList" class="table">
    <thead>
        <tr>
        <th style="text-align: left;">ID</th>
        <th style="text-align: left;">@lang('translation.Product Name')</th>
        <th style="text-align: left;">@lang('translation.Description')</th>
        <th style="text-align: left;">@lang('translation.Created Date')</th>
        <th style="text-align: left;">@lang('translation.Updated Date')</th>
        <th style="text-align: left; width: 18%">@lang('translation.Action')</th>

        </tr>
    </thead>
    <tbody>
        @foreach($products as $contract)
            <tr>
                <td style="text-align: left;" >{{ $contract->id }}</td>
                <td style="text-align: left;" >{{ $contract->product_name }}</td>
                <td style="text-align: left;">{{ $contract->description }}</td>
                <td style="text-align: left;">{{ $contract->created_at }}</td>
                <td style="text-align: left;" >{{ $contract->updated_at }}</td>
                <td style="text-align: left;">


                <div class="btn-toolbar">
                                <button class="btn btn-primary"  onclick="openModal('{{  $contract->id}}', '{{ $contract->product_name }}',
                     '{{ $contract->description }}')">   @lang('translation.Edit')  </button>


                    <form id="deleteForm-{{ $contract->id }}" action="{{ route('product.delete', $contract->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="button" style="margin-left:2px;"   class="btn btn-danger waves-effect waves-light" onclick="confirmDelete('{{ $contract->id }}');">
                        <i class="bx bx-block font-size-16 align-middle me-2"></i>  @lang('translation.Delete')
                        </button>
                    </form>
                                
                </div>

 


</td>
</tr>

<!-- Modal for Editing Contract -->
        <div class="modal fade" id="editContractModal{{ $contract->id }}" tabindex="-1" role="dialog" aria-labelledby="editContractModalLabel{{ $contract->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editContractModalLabel{{ $contract->id }}">Edit Contract</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- CKEditor for editing contract content -->
                        <textarea id="contractContent{{ $contract->id }}" name="contractContent">{{ $contract->editor_content }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveEditedContent('{{ $contract->id }}')">Save</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </tbody>
</table>
</div>

<span>
 
</span>



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



        <!-- JavaScript for confirmation popup -->
        <script>
                    function confirmDelete(contractId) {
                        // Display SweetAlert2 confirmation popup
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Do you want to delete this Product?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If user clicks 'Yes', submit the form
                                document.getElementById('deleteForm-' + contractId).submit();
                            } else {
                                // If user clicks 'No', do nothing
                                return false;
                            }
                        });
                    }
                </script>


<script>
 


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

@section('script')
    <!-- CKEditor script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.0/classic/ckeditor.js"></script>

    <script>

        function openModalNew() {
                // Using Bootstrap's JavaScript to open the modal
                var myModal = new bootstrap.Modal(document.getElementById('exampleModalNew'));
                myModal.show();
            }
        
        function saveContent(title, content) {
            // Send title and content to Laravel backend using AJAX
            $.ajax({
                url: '/save',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    title: title,
                    content: content
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


        function saveEditedContent() {
            var editedContent = CKEDITOR.instances.contractContent.getData();

            // Send the edited content to the server using AJAX
            // You can use the saveContent function you provided earlier
            saveContent(editedContent);

            // Close the modal
            $('#editContractModal').modal('hide');
        }


        // Function to save data when Save button is clicked
        function saveData() {
            const title = document.querySelector('#title').value; // Get title
            const content = editor.getData(); // Get content from CKEditor
            saveContent(title, content); // Call saveContent function to save data
        }

        function saveDatamodal() {
            const title = document.querySelector('#titlemodal').value; // Get title
            const content = editormodal.getData(); // Get content from CKEditor
           // saveContent(title, content); // Call saveContent function to save data
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
@endsection

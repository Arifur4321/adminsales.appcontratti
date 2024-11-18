
 
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
        @lang('translation.Edit Contract')
        @endslot
    @endcomponent


<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Include SweetAlert JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 

 <!-- Include MetisMenu via CDN -->
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js"></script>


<div class="modal" id="HeaderOrFooterModal" tabindex="-1" aria-labelledby="HeaderOrFooterModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="HeaderOrFooterModalLabel">Header/Footer Entries</h5>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="headerFooterForm">
                    @csrf
                    <input type="hidden" name="contractID" id="contractID" value="{{ $contract->id }}">
                    <div class="mb-3">
                        <input type="checkbox" id="checkbox1" onchange="toggleDropdowns('checkbox1', 'dropdown1', 'dropdown2')"> Header
                        <div class="row">
                            <div class="col">
                                <select name="headerID" id="dropdown1" class="form-select" style="display: none;">
                                    @foreach($headerEntries as $id => $header)
                                        <option value="{{ $id }}">{{ $header }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <select name="headerFrequency" id="dropdown2" class="form-select" style="display: none;">
                                    <option value="first">First Page</option>
                                    <option value="every">Every Page</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="checkbox" id="checkbox2" onchange="toggleDropdowns('checkbox2', 'dropdown3', 'dropdown4')"> Footer
                        <div class="row">
                            <div class="col">
                                <select name="footerID" id="dropdown3" class="form-select" style="display: none;">
                                    @foreach($footerEntries as $id => $footer)
                                        <option value="{{ $id }}">{{ $footer }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <select name="footerFrequency" id="dropdown4" class="form-select" style="display: none;">
                                    <option value="first">First Page</option>
                                    <option value="every">Every Page</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitForm()">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <div class="card">
            <div class="card-body">
                <form id="editpagenew">
                    <input type="hidden" id="contract-id" value="{{ $contract->id }}">
                    
                     <!-- For larger screens (md and above) -->
                     <div class="d-none d-md-flex align-items-center mb-3">
                        
                            <label for="title" class="form-label me-2" style="width: 125px;">@lang('translation.Contract Name')</label>

                            <input type="text" class="form-control w-75" id="title" name="contract_name" value="{{ $contract->contract_name }}">
                            
                            <div class="dropdown" style="margin-left: 3px;">
                                <button type="button" class="btn btn-primary dropdown-toggle  " style="margin-left: 13px;" data-bs-toggle="dropdown" aria-expanded="false">
                                @lang('translation.All Actions') <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                             
                                    <!-- <li><button class="dropdown-item" type="button" onclick="saveData()">Update</button></li> -->
                                    <li><button class="dropdown-item" type="button" onclick="previewPDFNew()" id="mytestButton">@lang('translation.Preview')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openHeaderOrFooterModal()">@lang('translation.Header/Footer')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openpricemodal('{{$contract->id}}')">@lang('translation.Add Price')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openproductmodal('{{$contract->id}}')">@lang('translation.Product')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openModalNew('{{$contract->id}}')">@lang('translation.Variable')</button></li>
                                    <li><button class="dropdown-item" type="button" id="signbutton">@lang('translation.Signature')</button></li>

                              
                                </ul>
                            </div>
                            
                             <button type="button" class="btn btn-success "    style="margin-left:6px;" onclick="saveData()" > @lang('translation.Update')</button>  
                        </div>
                        
                        <div class="d-md-none mb-3">
                            <!-- Insert the responsive HTML code here  lower screen-->
                            
                            <div class="d-flex flex-wrap align-items-center mb-3">
                            <label for="title" class="form-label me-2" style="flex: 0 0 125px;">Contract Name</label>
                            <input type="text" class="form-control flex-grow-1 mb-2 mb-md-0" id="title" name="contract_name" value="{{ $contract->contract_name }}">
                            
                            <div class="dropdown" style="margin-left: 3px;">
                                <button type="button" class="btn btn-primary dropdown-toggle " style="margin-left: 10px;" data-bs-toggle="dropdown" aria-expanded="false">
                                @lang('translation.All Actions')<i class="mdi mdi-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li><button class="dropdown-item" type="button" onclick="previewPDFNew()" id="mytestButton">@lang('translation.Preview')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openHeaderOrFooterModal()">@lang('translation.Header/Footer')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openpricemodal('{{$contract->id}}')">@lang('translation.Add Price')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openproductmodal('{{$contract->id}}')">@lang('translation.Product')</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="openModalNew('{{$contract->id}}')">@lang('translation.Variable')</button></li>
                                    <li><button class="dropdown-item" type="button" id="signbutton">@lang('translation.Signature')</button></li>
                                    <!-- <li><button class="dropdown-item" type="button" onclick="saveData()">Update</button></li> -->
                                </ul>
                            </div>
                        
                            <button type="button" class="btn btn-success   "    style="margin-left:6px;" onclick="saveData()">  @lang('translation.Update')</button>
                        </div>
                    </div>

           

            <textarea id="editor" name="editor" style="width: 100%; max-width: 500px;">{{ $contract->editor_content }}</textarea>
          

                    <style>
                        /*------------------------- CKEditor CSS ------------------------- */
                        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                            border-color: var(--ck-color-base-border);
                            height: 500px !important;

                            width : 100% !important;
             
                                
                                }
                                .ck.ck-editor__editable_inline>:last-child {
                                    margin-bottom: var(--ck-spacing-large);
                                    height: 500px;
                            
                                }

                                .ck-editor__editable {
                                    min-height: 500px;
                                   
                                }
                        </style>
               
                </form>
            </div>
        </div>

<!-- Preview PDF Modal -->
<div class="modal fade" id="previewPdfModal" tabindex="-1" aria-labelledby="previewPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewPdfModalLabel">Preview PDF</h5>
            </div>
            <div class="col-8"><br>
                <!-- Other content if needed -->
            </div>
            <div class="modal-body" id="previewPdfModalBody" style="height: 60vh; overflow-y: auto;">
                <!-- PDF content will be injected here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closePreviewPdfModalBtn" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary" id="sendButton" data-bs-dismiss="modal" disabled>Send</button> -->
            </div>
        </div>
    </div>
</div>


    <!-- Product Modal -->
    <div class="modal" id="exampleModalProduct" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelNew">Product  List</h5>
                    <div class="col-sm">
                        
                    </div>
                    <div class="search-box me-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" autocomplete="off" id="searchInputproduct" placeholder="Search...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <form id="productFormNew" action="/createcontract" method="POST">
                        <!-- for table -->
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <table id="ProductList" action="/createcontract" method="POST" class="table">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>
                                        <!-- Checkbox to select product -->
                                       <!-- Checkbox to select product -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $contract->id }},{{ $product->id }}" id="productCheckbox_{{ $product->id }}" name="selectedProduct">
                                        <label class="form-check-label" for="productCheckbox_{{ $product->id }}">
                                        </label>
                                    </div>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="">Save Product</button> -->
                </div>
            </div>
        </div>
    </div>

 
   <!-- price Modal -->
   <div class="modal" id="exampleModalPrice" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                
            <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelNew">Price List</h5>
                    <div class="col-sm">
                        
                    </div>

                    <div class="search-box me-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" autocomplete="off" id="searchInputprice" placeholder="Search...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <form id="priceFormNew" action="/get-price-lists" method="POST">
                        <!-- for table -->
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <table id="PriceList" action="/get-price-lists" method="POST" class="table">
                            <thead>
                                <tr>
                                    <th>Price ID</th>
                                    <th>price Name</th>
                                    <th>Currency</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($priceLists as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->pricename }}</td>
                                    <td>{{ $product->currency }}</td>
                                    <td>
                                        <!-- Checkbox to select product -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{$contract->id}},{{ $product->id }},{{ $product->pricename }}" id="priceCheckbox_{{ $product->id }}" name="selectedPrice">
                                            <!-- <label class="form-check-label" for="priceCheckbox_{{ $product->id }}">
                                                Add
                                            </label> -->
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-primary" onclick="" id="addPriceButton" data-bs-dismiss="modal" disabled>Add</button>

                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="">Save Product</button> -->
                </div>
            </div>
        </div>
    </div>



    
<!-- variable Modal  
 --><!-- variable Modal -->
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelNew">Variable List</h5>
                <div class="col-sm">
                   
                </div>

                <div class="search-box me-2 d-inline-block">
                        <div class="position-relative">
                            <input type="text" class="form-control" autocomplete="off" id="searchInputvariable" placeholder="Search...">
                            <i class="bx bx-search-alt search-icon"></i>
                        </div>
                    </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productFormNew" action="/edit-contract-list" method="POST">
                    <!-- for table -->
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <!-- Wrap the table inside a div with fixed height and auto scroll -->
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table id="ContractList" action="/edit-contract-list" method="POST" class="table">
                            <thead>
                                <tr>
                                    <th>VariableID</th>
                                    <th>Var Name</th>
                                    <th>Var Type</th>
                                    <th>Description</th>
                                    <th>CreatedDate</th>
                                    <th>Action</th>
                                    <th>Mandatory</th>
                                    <th>Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variables as $contract)
                                <tr>
                                    <td>{{ $contract->VariableID }}</td>
                                    <td>{{ $contract->VariableName }}</td>
                                    <td>{{ $contract->VariableType }}</td>
                                    <td>{{ $contract->Description }}</td>
                                    <td>{{ $contract->created_at }}</td>
                                    <td>
                                        <label class="form-check-label">
                                            <input id="variablecheckbox" class="variable-checkbox form-check-input add-checkbox" type="checkbox" onchange="checkCheckbox(this, '{{ $contract->VariableName }}', '{{ $contract->VariableID }}')">
                                        </label>
                                        <button type="button" class="btn btn-primary add-button" data-bs-dismiss="modal" onclick="insertVariable('{{ $contract->VariableName }}')" disabled>Add</button>
                                    </td>
                                    <td>
                                        <label class="form-check-label">
                                            <!-- Hidden checkbox with checked attribute -->
                                            <input id="mandatoryvariablecheckbox_{{ $contract->VariableID }}" class="mandatoryvariablecheckbox form-check-input" 
                                            onchange="MandatoryCheckbox(this, '{{ $contract->VariableName }}', '{{ $contract->VariableID }}')"
                                            type="checkbox"  disabled>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control input-number" id="variableorder" class="input-number" maxlength="2" disabled >
                                        </div>
                                    </td>
                                  
                              
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"  onclick="saveAndCheck()" >Save</button>
                <!-- <button type="button" class="btn btn-primary" onclick="">Save Product</button>   , '{{ $contract->id }}' , '{{ $contract->VariableID }}'  -->
            </div>
        </div>
    </div>
</div>


 <!----------------------  script section start here  ---------------------------------------------------->
 
@endsection

@section('script')
 

 
<!--  classic CSKEDitor custom build  -->
<script src="{{ asset('js/newckeditor/build/ckeditor.js') }}"></script>

<script>


       let editor; // Global variable for main CKEditor instance    
            
        ClassicEditor
            .create(document.querySelector('#editor'),{
          
                ckfinder: {
                        uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
                    },
 


            }
            )
            .then(createdEditor => {
                // Assign the created editor to the global variable
                editor = createdEditor;

                // Define a function to insert text into CKEditor 5
                window.insertVariable = function(variableName) {
                    // Insert variableName at the current cursor position
                    const currentPosition = editor.model.document.selection.getLastPosition();
                    if (currentPosition) {
                        editor.model.change(writer => {
                            writer.insertText("%"+variableName+"%", currentPosition);
                        });
                    }
                }

                window.insertprice = function(variableName) {
                    // Insert variableName at the current cursor position
                    const currentPosition = editor.model.document.selection.getLastPosition();
                    var priceString = 'PRICE';
                    if (currentPosition) {
                        editor.model.change(writer => {
                            writer.insertText("$"+priceString+"$", currentPosition);
                        });
                    }
                }
 

                
                  // Signature button method
                    $(document).ready(function() {
                        $('#signbutton').click(function() {
                            const imageUrl = 'https://i.ibb.co/71g553C/FIRMA-QUI.jpg';
                            const currentPosition = editor.model.document.selection.getLastPosition();
            
                            if (currentPosition) {
                                editor.model.change(writer => {
                                    const imageElement = writer.createElement('imageBlock', {
                                        src: imageUrl
                                    });
                                    editor.model.insertContent(imageElement, currentPosition);
                                });
                            }
                        });
                    });
                
            })
            .catch(error => {
                console.error(error);
            });


        // Function to delete "$PRICE$" string from CKEditor content
        function deletePriceFromCKEditor() {
            var priceRegex = /\$PRICE\$/g; // Regular expression to match all occurrences of "$PRICE$"
            var editorData = editor.getData();
            var newData = editorData.replace(priceRegex, ''); // Replace all occurrences of "$PRICE$" with an empty string
            editor.data.set(newData, { suppressErrorInCollaboration: true });
            console.log('I am here at deletePriceFromCKEditor');
        }


           
    //variable pop up search
    $(document).ready(function () {
        // Reference to the input field and the table
        var $searchInput = $('#searchInputvariable');
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

window.onbeforeunload = function() {
    var message = 'Do you want to leave this page?';
    return message;
}

function openHeaderOrFooterModal() {
        var contractID = window.location.pathname.split('/').pop();
        document.getElementById('contractID').value = contractID;
        
        // Fetch the header and footer data passed from the backend
        var headerFooterData = @json($headerFooterData);

        if (headerFooterData) {
            // Check and populate Header
            if (headerFooterData.HeaderID) {
                $('#checkbox1').prop('checked', true);
                $('#dropdown1').val(headerFooterData.HeaderID).show();
                $('#dropdown2').val(headerFooterData.HeaderPage).show();
            } else {
                $('#checkbox1').prop('checked', false);
                $('#dropdown1').hide();
                $('#dropdown2').hide();
            }

            // Check and populate Footer
            if (headerFooterData.FooterID) {
                $('#checkbox2').prop('checked', true);
                $('#dropdown3').val(headerFooterData.FooterID).show();
                $('#dropdown4').val(headerFooterData.FooterPage).show();
            } else {
                $('#checkbox2').prop('checked', false);
                $('#dropdown3').hide();
                $('#dropdown4').hide();
            }
        }

        // Show the modal after setting the values
        var myModal = new bootstrap.Modal(document.getElementById('HeaderOrFooterModal'));
        myModal.show();
    }

    // Function to toggle dropdown visibility based on checkbox state
    function toggleDropdowns(checkboxId, dropdownId1, dropdownId2) {
        var checkbox = document.getElementById(checkboxId);
        var dropdown1 = document.getElementById(dropdownId1);
        var dropdown2 = document.getElementById(dropdownId2);

        if (checkbox.checked) {
            dropdown1.style.display = 'block';
            dropdown2.style.display = 'block';
        } else {
            dropdown1.style.display = 'none';
            dropdown2.style.display = 'none';
        }
    }

     

    function submitForm() {
        var form = document.getElementById('headerFooterForm');
        var formData = new FormData(form);

        // Clear previous hidden inputs
        var previousHiddenInputs = form.querySelectorAll('input[type=hidden][name="type"], input[type=hidden][name="HeaderID"], input[type=hidden][name="HeaderPage"], input[type=hidden][name="FooterID"], input[type=hidden][name="FooterPage"]');
        previousHiddenInputs.forEach(input => input.remove());

        // Append necessary hidden inputs based on the selected checkboxes
        if (document.getElementById('checkbox1').checked) {
            formData.append('type', 'header');
            formData.append('HeaderID', document.getElementById('dropdown1').value);
            formData.append('HeaderPage', document.getElementById('dropdown2').value);
        }

        if (document.getElementById('checkbox2').checked) {
            formData.append('type', 'footer');
            formData.append('FooterID', document.getElementById('dropdown3').value);
            formData.append('FooterPage', document.getElementById('dropdown4').value);
        }

        // Send the AJAX request
        $.ajax({
            url: "{{ url('/saveHeaderFooter') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                var myModal = bootstrap.Modal.getInstance(document.getElementById('HeaderOrFooterModal'));
                myModal.hide();
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an issue saving your data. Please try again.',
                });

                console.error('Error:', error);
            }
        });
    }

    function createHiddenInput(name, value) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        return input;
    }

        document.addEventListener("DOMContentLoaded", function() {
            // Get all checkboxes within the modal content
            var checkboxes = document.querySelectorAll('#exampleModalProduct input[type="checkbox"]');
            
            // Add event listener to each checkbox
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        // Uncheck all other checkboxes
                        checkboxes.forEach(function(cb) {
                            if (cb !== checkbox) {
                                cb.checked = false;
                            }
                        });

                        // Extract contract ID and product ID from checkbox value
                        var ids = this.value.split(',');
                        var contractId = ids[0];
                        var productId = ids[1];
                        
                        // Make AJAX call to save the selected product
                        saveSelectedProduct(contractId, productId);
                    }else {
                        var ids = this.value.split(',');
                        var contractId = ids[0];
                        var productId = ids[1];
                        
                        // Make AJAX call to delete the selected product
                        deleteSelectedProduct(contractId, productId);

                    }
                });
            });

            // Function to save selected product via AJAX
            function saveSelectedProduct(contractId, productId) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');    
                // Make AJAX request
                $.ajax({
                    url: '/save-selected-product',
                    method: 'POST',
                    data: {
                        contractId: contractId,
                        productId: productId
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Handle success response (if needed)
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle error response (if needed)
                        console.error(error);
                    }
                });
            }


            // Function to delete selected product via AJAX
        function deleteSelectedProduct(contractId, productId) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');    
            // Make AJAX request
            $.ajax({
                url: '/delete-selected-product',
                method: 'POST',
                data: {
                    contractId: contractId,
                    productId: productId
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Handle success response (if needed)
                    console.log(response);
                    alert('Product ID is deleted from Database');
                },
                error: function(xhr, status, error) {
                    // Handle error response (if needed)
                    console.error(error);
                }
            });
        }

        });


 

        $(document).ready(function () {
        // Reference to the input field and the table
        var $searchInput = $('#searchInputproduct');
        var $table = $('#ProductList');

        // Event listener for keyup on the search input
        $searchInput.on('keyup', function () {
            var searchText = $(this).val().toLowerCase();

            // Filter the table rows based on the search text
            $table.find('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });
      });



      document.addEventListener("DOMContentLoaded", function() {
            var checkboxes = document.querySelectorAll('#exampleModalPrice input[type="checkbox"]');
            var addPriceButton = document.getElementById('addPriceButton');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    checkboxes.forEach(function(cb) {
                        if (cb !== checkbox) {
                            cb.checked = false;
                        }
                    });
              

                    if (checkbox.checked) {
               
                        addPriceButton.disabled = false;
                        var selectedPriceId = null;
                        // Splitting the value to get both contractId and productId
                        var ids = checkbox.value.split(',');
                        console.log('my value :' ,ids );
                        selectedPriceId = {
                            contractId: ids[0],
                            productId: ids[1],
                            pricename: ids[2]
                        };
                        console.log('selectedPriceId :', selectedPriceId);
             

                        var csrfToken = $('meta[name="csrf-token"]').attr('content');    
                        if (selectedPriceId !== null) {
                            $.ajax({
                                url: '/insert-price-id', // Replace with your route
                                method: 'POST',
                                data: {
                                    contractId: selectedPriceId.contractId,
                                    productId: selectedPriceId.productId
                                },
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success: function(response) {
                                    // Handle success response
                                    console.log(response);
                                },
                                error: function(xhr, status, error) {
                                    // Handle error
                                    console.error('Error:', error);
                                }
                            });
                        }
                    }   
                    else {
                        addPriceButton.disabled = true;
                        // If checkbox is unchecked, trigger AJAX call to delete price ID
                        var selectedPriceId = null;
                        var ids = checkbox.value.split(',');
                        selectedPriceId = {
                            contractId: ids[0],
                            productId: ids[1]
                        };

                        var csrfToken = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: '/delete-price-id', // Replace with your delete route
                            method: 'POST',
                            data: {
                                contractId: selectedPriceId.contractId
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                // Handle success response
                                console.log(response);
                                // Show confirmation dialog
                                var confirmed = window.confirm("Do you want to delete the price?");
                                if (confirmed) {
                                    deletePriceFromCKEditor();
                                } else {
                                  //  addPriceButton.disabled = false;
                                  //  checkbox.checked = true;
                                }
                                $('#exampleModalPrice').modal('hide');
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                console.error('Error:', error);
                            }
                        });
                    }

                });
            });
        });

 

        $(document).ready(function () {
        // Reference to the input field and the table
        var $searchInput = $('#searchInputprice');
        var $table = $('#PriceList');

        // Event listener for keyup on the search input
        $searchInput.on('keyup', function () {
            var searchText = $(this).val().toLowerCase();

            // Filter the table rows based on the search text
            $table.find('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });
      });







        function previewPDF() {

            // Get data from CKEditor
            var editorData = editor.getData();

            // Get selected header or footer data
            var headerDropdown = document.getElementById('dropdown1');
            var footerDropdown = document.getElementById('dropdown3');
           //var headerValue = headerDropdown.options[headerDropdown.selectedIndex].text;
           // var footerValue = footerDropdown.options[footerDropdown.selectedIndex].text;

            var headerValue = headerDropdown.selectedIndex !== -1 ? headerDropdown.options[headerDropdown.selectedIndex].text : null;
            var footerValue = footerDropdown.selectedIndex !== -1 ? footerDropdown.options[footerDropdown.selectedIndex].text : null;


            // Determine if header or footer is selected for first or every page
            var headerLocation = document.getElementById('dropdown2').value;
            var footerLocation = document.getElementById('dropdown4').value;

            // Check if header checkbox is checked
            var headerCheckbox = document.getElementById('checkbox1').checked;

            // Check if footer checkbox is checked
            var footerCheckbox = document.getElementById('checkbox2').checked;

            // Construct title text including selected header or footer values
            var titleHeader = "";
            var titleFooter = "";

            // Construct header and footer based on selection
            var headerHTML = '';
            var footerHTML = '';

            // Include header if checkbox is checked and it's selected for the first page or every page
            if (headerCheckbox) {
                if (headerLocation === 'first' || headerLocation === 'every') {
                    titleHeader += headerValue  ;
                    headerHTML = '<div style="position: fixed; top: 20px; right: 20px;">' + headerValue + '</div>';
                }
            }

            // Include footer if checkbox is checked and it's selected for the first page or every page
            if (footerCheckbox) {
                if (footerLocation === 'first' || footerLocation === 'every') {
                    titleFooter += footerValue + " - " + footerLocation;
                    footerHTML = '<div style="position: fixed; bottom: 5px; right: 20px;">' + footerValue +  '</div>';
                }
            }

            // Convert HTML content to PDF using jQuery
            var myWindow = window.open('', 'PRINT', 'height=600,width=800');

            myWindow.document.write('<html><title style="text-align:right">' + 
        "Codice 1% PDF &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+  titleHeader +'</title>');
            myWindow.document.write('<style>@page { margin: 100px; counter-reset: page-counter; }</style>'); // Reset page counter
            myWindow.document.write('</head><body>');

            // Increment page counter for each page
            myWindow.document.write('<div style="position: absolute; top: -50px; right: 50px;">Page: <span style="counter-increment: page-counter; content: counter(page-counter);"></span></div>');

            // Write editor data
            myWindow.document.write(editorData);

           
            // Include footer based on selection
            myWindow.document.write('</body><footer>' + footerHTML + '</footer></html>');

            myWindow.document.close(); // necessary for IE >= 10
            myWindow.onload = function () {
                myWindow.print();
                myWindow.close();
            };
        }
        
 $(document).ready(function() {
    
  window.previewPDFNew = function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var editorData = editor.getData();
        var contractID = window.location.pathname.split('/').pop();

        $('#spinner-overlay').show();
        $.ajax({
            url: '/get-pdf-sales-new',
            type: 'POST',
            data: {
                editorData: editorData,
                contractID: contractID,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                var pdfUrl = response.pdf_url;
                console.log('pdfUrl:', pdfUrl);

                // Inject the PDF into the modal body
                $('#previewPdfModalBody').html('<embed src="' + pdfUrl + '" type="application/pdf" style="width:100%; height:100%;">');
                
                // Show the modal
                //$('#previewPdfModal').modal('show');


                var myModal = new bootstrap.Modal(document.getElementById('previewPdfModal'));
                myModal.show();
                // now working
                $('#closePreviewPdfModalBtn').on('click', function() {
                        $.ajax({
                            url: '/delete-pdf',
                            type: 'POST',
                            data: {
                                pdfUrl: pdfUrl
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                console.log('PDF deleted successfully');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting PDF:', error);
                            }
                        });
                    });

                // Hide the spinner
                $('#spinner-overlay').hide();
            },
            error: function(xhr, status, error) {
                console.error('Error generating PDF:', error);
                $('#spinner-overlay').hide();
            }
        });
    }
    
 });


    // Function to get the contract and generate PDF
    function getTheContractmytest(selectedContract) {
        if (selectedContract) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
            var editorData = editor.getData();
            $.ajax({
                url: '/get-pdf-sales',
                type: 'POST',
                data: {
                    editorData: editorData,
                   
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    var pdfUrl = response.pdf_url;

                    console.log('pdfUrl:', pdfUrl);
                    $('.modal-body').html('<embed src="' + pdfUrl + '" type="application/pdf" style="width:100%; height:100%;">');
                    $('#myModal').modal('show');

                    $('#closeModalBtn').on('click', function() {
                        $.ajax({
                            url: '/delete-pdf',
                            type: 'POST',
                            data: {
                                pdfUrl: pdfUrl
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                console.log('PDF deleted successfully');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting PDF:', error);
                            }
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    }


        
        let arifurData = false;
        let previousContent = '';

        window.addEventListener('beforeunload', function (e) {
            if (!arifurData) {
                const confirmationMessage = 'You have unsaved changes. Are you sure you want to leave?';
                (e || window.event).returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });

        function saveData() {     
        // Check if CKEditor is initialized 
        if (!editor) {
            console.error('CKEditor is not initialized.');
            return;
        }

        // Get the contract ID from the hidden input field
        const contractId = document.querySelector('#contract-id').value;

        // Get the contract name from the input field
        const contractName = document.querySelector('#title').value;

        // Check if the contract name is empty
        if (!contractName.trim()) {
            console.error('Contract name cannot be empty.');
            return;
        }

        // Get the editor content from CKEditor
        const editorContent = editor.getData();

       

        // Prepare the form data
        const formData = {
            _token: "{{ csrf_token() }}",
            id: contractId,
            contract_name: contractName,
            editor_content: editorContent
        };

        // Call the saveContent function to save the data
          saveContent(formData);
          arifurData = true;
    }

    function saveContent(formData) {
    $.ajax({
        url: '/edit-contract-list/update', // Change the URL to the update route
        type: 'POST',
        data: formData,
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: 'Data updated successfully ',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            // Optionally, you can redirect or show a success message here
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            // Optionally, you can handle errors here
        }
    });
    }
 
        //Function to insert a variable into CKEditor
        function insertVariable(variableName) {
            // Get the current content of the editor
            const currentContent = editor.getData();
            // Append the variable to the content with % signs
            const newContent = currentContent + `%${variableName}%`;
            // Set the updated content back to the editor
            editor.setData(newContent);
        }
 
        function countOccurrences(str, searchValue) {
        var regex = new RegExp(searchValue, 'g');
        var matches = str.match(regex);
        return matches ? matches.length : 0;
        }

        // Function to delete a substring from a string
        function deleteSubstring(str, searchValue) {
            var regex = new RegExp(searchValue, 'g');
            return str.replace(regex, '');
        }
    

        // for open product modal 
        function openproductmodal(contractID) {
                  

                       // AJAX call to retrieve price_id for the given contractID
                    $.ajax({
                    url: '/get-product-id', // Replace with your route
                    method: 'GET',
                    data: {
                        contractID: contractID
                    },
                    success: function(response) {
                        // Open the modal
                        var myModal = new bootstrap.Modal(document.getElementById('exampleModalProduct'));
                        myModal.show();

                        // Select the checkbox if price_id is found
                        if (response.product_id) {
                            var checkbox = document.getElementById('productCheckbox_' + response.product_id);
                            if (checkbox) {
                                checkbox.checked = true;
     
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error:', error);
                    }
                });

           

                }
                 $('#exampleModalProduct').modal('hide');


        //to open price modal
        function openpricemodal(contractID) {
            // AJAX call to retrieve price_id for the given contractID
                $.ajax({
                    url: '/get-price-id', // Replace with your route
                    method: 'GET',
                    data: {
                        contractID: contractID
                    },
                    success: function(response) {
                        // Open the modal
                        var myModal = new bootstrap.Modal(document.getElementById('exampleModalPrice'));
                        myModal.show();

                        // Select the checkbox if price_id is found
                        if (response.price_id) {
                            var checkbox = document.getElementById('priceCheckbox_' + response.price_id);
                            if (checkbox) {
                                checkbox.checked = true;
                                var addPriceButton = document.getElementById('addPriceButton');
                                addPriceButton.disabled = false;
                                //addPriceButton.removeEventListener('click', arguments.callee);
                            

                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error:', error);
                    }
                });

                addPriceButton.addEventListener('click', function() {
                                    // for future ajax call to get pricename and  call insertprice logic when the button is clicked
                                  
                                    insertprice('price');
                                    console.log('Add button clicked!  2nd time  ' );
                                       // Remove the event listener after it has been triggered
                                    addPriceButton.removeEventListener('click', arguments.callee);
                                    
                                });
            }

 
             
         

        function openModalNew(contractID, variableIDs) {
            // AJAX request to retrieve variable IDs and order values
            $.ajax({
                url: '/checkedVariable',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    contract_id: contractID
                },
                success: function(response) {
                    console.log('response now :', response );
                    // Loop through the response data
                    response.variableIDs.forEach(function(variableID, index) {
                        // Find the corresponding row in the table based on the variableID
                        var $row = $('tbody tr').filter(function() {
                            return $(this).find('td:first').text().trim() === variableID;
                        });
                        // Check if the row exists
                        if ($row.length) {
                            // Check the checkbox corresponding to the variableID
                            $row.find('.add-checkbox').prop('checked', true);
                            // Enable the add button
                            $row.find('.add-button').prop('disabled', false);
                            // Enable the mandatory checkbox
                            //$row.find('.mandatoryvariablecheckbox').prop('disabled', false);
                            
                            // Set the mandatory checkbox based on the response
                            if (response.mandatoryStatuses[index]) {
                                $row.find('.mandatoryvariablecheckbox').prop('disabled', false).prop('checked', true);
                            } else {
                                $row.find('.mandatoryvariablecheckbox').prop('disabled', false).prop('checked', false);
                            }
                            
                            // Set the order value in the input field
                            $row.find('.input-number').val(response.orderValues[index]);
                            $row.find('.input-number').prop('disabled', false);


                        }
                    });

                    // Open the modal with the retrieved variable IDs
                    var myModal = new bootstrap.Modal(document.getElementById('exampleModalNew'));
                    myModal.show(); // Open the modal

                    // Delegate the change event handling to the tbody element
                    $('tbody').on('change', '.add-checkbox', function() {
                        // Find the parent row of the checkbox
                        var $row = $(this).closest('tr');
                        // Find the add button within the same row and enable/disable it based on the checkbox state
                        $row.find('.add-button').prop('disabled', !$(this).prop('checked'));
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                }
            });
        }
 
        $('#exampleModalNew').modal('hide');

 
 
 function saveAndCheck() {
    // Save functionality here
    // For example, you can call a save function or perform any other action
    
    // After saving, call the checkCheckbox function for all checkboxes
    $('.add-checkbox').each(function() {
        // Pass the checkbox, variable name, and variable ID to the checkCheckbox function
        var checkbox = this;
        var variableName = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
        var variableID = $(checkbox).closest('tr').find('td:first').text().trim();
        checkCheckbox(checkbox, variableName, variableID);
    });
}


    function checkCheckbox(checkbox, variableName,  variableId) {
    // var  mandatoryCheckbox = document.getElementById('mandatoryvariablecheckbox_' + variableID);
    // mandatoryCheckbox.disabled = !checkbox.checked;  

    var $row = $(checkbox).closest('tr'); // Find the parent row

    if (checkbox.checked) {
   
        $row.find('.add-button').prop('disabled', false); // Enable button if checkbox is checked
           // Send data to server for insertion
        console.log('Logged-in User:', '{{ Auth::user()->name }}');

        $row.find('.mandatoryvariablecheckbox').prop('disabled', false).prop('checked', true);

    
       // Enable the input field by its unique class name
       $row.find('.input-number').prop('disabled', false);

    // Get the value from the input field in the current row
    var orderValue = $row.find('.input-number').val();

    console.log("Input field activated and orderValue :", orderValue);

        var Mandatory = document.getElementById('mandatoryvariablecheckbox_' + variableId).checked;

        console.log('Mandator ::: ', Mandatory);
        const contractId = document.querySelector('#contract-id').value;   
        $.ajax({
                        url: '/insert-contract-variable',
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            contract_id: contractId,
                            variable_id: variableId,
                            Mandatory :Mandatory,
                            orderValue:orderValue

                        },
                        success: function(response) {
                            // Store the checked state in localStorage 
                            localStorage.setItem('isChecked_' + variableId, 'true');
                        },

                        error: function(xhr, status, error) {
                             
                        }
                    });
    } else {
    
        var editorData = editor.getData();
        var count = countOccurrences(editorData, variableName);

       // $row.find('.mandatoryvariablecheckbox').prop('disabled', true);
       $row.find('.mandatoryvariablecheckbox').prop('disabled', true).prop('checked', false);

       $row.find('.input-number').prop('disabled', true);

    // Get the value from the input field in the current row
         var orderValue = $row.find('.input-number').val();

        if (count===0){
                // Call the AJAX function to delete the contract variable
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var contractId = document.querySelector('#contract-id').value;
                    //const contractId = document.querySelector('#contract-id').value; 
                    $.ajax({
                        url: '/delete-contract-variable',
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            contract_id: contractId,
                            variable_id: variableId,
                            orderValue: orderValue 
                          
                        },
                        success: function(response) {
                            // Handle success if needed
                        },
                        error: function(xhr, status, error) {
                            // Handle error if needed
                        }
                    });

        }

        if (count > 0) {
            Swal.fire({
                title: 'Variable Found!',
                text: 'The variable ' + variableName + ' appears ' + count + ' times in the editor content. Do you want to delete it?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'No, cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var latestvar = "%" + variableName + "%";
                    editorData = deleteSubstring(editorData, latestvar);
                    editor.data.set(editorData, { suppressErrorInCollaboration: true });

                    // Call the AJAX function to delete the contract variable
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var contractId = document.querySelector('#contract-id').value;
                    //const contractId = document.querySelector('#contract-id').value; 
                    $.ajax({
                        url: '/delete-contract-variable',
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            contract_id: contractId,
                            variable_id: variableId
                        },
                        success: function(response) {
                            // Handle success if needed
                        },
                        error: function(xhr, status, error) {
                            // Handle error if needed
                        }
                    });

                } else {
                    // If user cancels deletion, recheck the checkbox
                    checkbox.checked = true;
                    $row.find('.add-button').prop('disabled', false); // Enable button as checkbox is checked
                }
            });
        } else {
            // If no occurrences found, enable button directly
            $row.find('.add-button').prop('disabled', false);
        }
    }
}

    function MandatoryCheckbox(checkbox, variableName, variableId) {
        var Mandatory = document.getElementById('mandatoryvariablecheckbox_' + variableId).checked;
        const contractId = document.querySelector('#contract-id').value;   
        if (Mandatory) {
            // Make an AJAX call to update the mandatory status in the database
            $.ajax({
                url: '/insert-mandatory-status', // Specify your route to update the mandatory status
                method: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    variable_id: variableId,
                    contractId :contractId ,
                    mandatory: true // Set the mandatory status to true
                },
                success: function(response) {
                    // Handle success
                    console.log('Mandatory status updated successfully.');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating mandatory status:', error);
                }
            });
        } else {
            // Make an AJAX call to update the mandatory status to false if needed
            $.ajax({
                url: '/insert-mandatory-status', // Specify your route to update the mandatory status
                method: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    variable_id: variableId,
                    contractId : contractId ,
                    mandatory: false // Set the mandatory status to false
                },
                success: function(response) {
                    // Handle success
                    console.log('Mandatory status updated successfully.');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating mandatory status:', error);
                }
            });
        }
    }

</script>

 
 


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

            $(document).ajaxStart(function() {
                // Show the spinner when an AJAX request starts
                spinnerOverlay.style.display = "block";
            });

            $(document).ajaxStop(function() {
                // Hide the spinner when the AJAX request completes
                spinnerOverlay.style.display = "none";
            });
        });
    </script>
 
@endsection
 



 
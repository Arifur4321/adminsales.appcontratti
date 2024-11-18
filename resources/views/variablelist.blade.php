@extends('layouts.master')
@section('title')
    @lang('translation.Variable-List')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.Variable List')

        @endslot
    @endcomponent


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
     <!--  Arifur change -->
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
            <button type="button" class="btn btn-primary" onclick="openModalNew()"> @lang('translation.Add New Variable') </button>
        </div>
    </div>
</div>



<!-- variable add  Modal -->
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelNew">@lang('translation.New Variable')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productFormNew">
              
                    <div class="mb-3">
                        <label for="product-name-new" class="col-form-label"> @lang('translation.Variable Name'):</label>
                        <input type="text" class="form-control" id="product-name-new">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="variable-type" class="col-form-label">@lang('translation.Variable Type'):</label>
                                <select class="form-select" id="variable-type">
                                                        <option value="Single Line Text">Single Line Text</option>
                                                        <option value="Multiple Line Text">Multiple Line Text</option>
                                                        <option value="Dates">Dates</option>
                                                        <option value="Multiple Box">Multiple Box</option>
                                                        <option value="Single Box">Single Box</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6" id="dynamic-fields-container" ondrop="drop(event)" ondragover="allowDrop(event)">
                            <!-- Dynamic fields will be added here -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description-new" class="col-form-label">@lang('translation.Description'):</label>
                        <textarea class="form-control" id="description-new"></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('translation.Close')</button>

                <button type="button" class="btn btn-primary" onclick="saveVariable()"> @lang('translation.Save Variable') </button>

            </div>
        </div>
    </div>
</div>

<!-- ---------------------------------------------------------- end  --->

<!-- CKEditor Modal -->
<div class="modal" id="ckeditorModal" tabindex="-1" aria-labelledby="ckeditorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ckeditorModalLabel">Edit Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="ckeditorContent"></textarea>

                <style>
                        
                        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                            border-color: var(--ck-color-base-border);
                            height: 235px !important;

                            width : 100% !important;
             
                                
                                }
                                .ck.ck-editor__editable_inline>:last-child {
                                    margin-bottom: var(--ck-spacing-large);
                                    height: 235px;
                            
                                }

                                .ck-editor__editable {
                                    min-height: 235px;
                                   
                                }
                        </style>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCkeditorContent">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- ---------------------------------------------------------- end  --->

<script src="{{ asset('js/newckeditor/build/ckeditor.js') }}"></script>

<script>


var inputFields = []; // Array to store input fields
var editorInstances = {}; // Object to keep track of CKEditor instances by unique IDs
 

document.addEventListener("DOMContentLoaded", function() {
 
    var variableTypeSelect = document.getElementById('variable-type');
    var dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
    var addButton = null;

    // Initialize CKEditor for the modal once document is ready
    ClassicEditor
        .create(document.querySelector('#ckeditorContent'), {
            ckfinder: {
                uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            }
        })
        .then(newEditor => {
            editorInstance = newEditor;
            console.log('CKEditor initialized and editor assigned:', editorInstance);
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });

    variableTypeSelect.addEventListener('change', function() {
        clearInputFields();
        dynamicFieldsContainer.innerHTML = '';
        var selectedOption = variableTypeSelect.value;

        if (selectedOption === 'Multiple Box' || selectedOption === 'Single Box') {
            addButton = createAddButton();
            dynamicFieldsContainer.appendChild(addButton);
        }
    });

    function createAddButton() {
        addInputFields();
        var addButton = document.createElement('button');
        addButton.textContent = 'Add';
        addButton.classList.add('form-control');
        addButton.style = "width: 58px;";
        addButton.setAttribute('type', 'button');
        addButton.addEventListener('click', function() {
            addInputField();
        });
        return addButton;
    }

    function addInputFields() {
        inputFields.forEach(function(inputField) {
            dynamicFieldsContainer.appendChild(inputField);
        });
    }

    function addInputField() {
        var uniqueId = 'row_' + new Date().getTime(); // Unique ID for each row

        var wrapperDiv = document.createElement('div');
        wrapperDiv.classList.add('dynamic-field');
        wrapperDiv.setAttribute('data-id', uniqueId);

        var inputField = document.createElement('input');
        inputField.setAttribute('type', 'text');
        inputField.setAttribute('placeholder', 'Enter value');
        inputField.style.border = '1px solid #ccc';
        inputField.style.borderRadius = '5px';
        inputField.style.padding = '8px 12px';
        inputField.style.width = '200px';
        inputField.style.boxSizing = 'border-box';
        inputField.setAttribute('data-id', uniqueId);

        var dragButton = document.createElement('button');
        dragButton.textContent = '☰';
        dragButton.setAttribute('type', 'button');
        dragButton.classList.add('btn', 'btn-primary', 'btn-sm', 'drag-button');

        wrapperDiv.addEventListener('dragstart', function(event) {
            event.dataTransfer.setData('text/plain', '');
            event.currentTarget.classList.add('dragging');
            event.stopPropagation();
        });

        wrapperDiv.setAttribute('draggable', 'true');

        var deleteButton = document.createElement('button');
        deleteButton.innerHTML = '&#10005;';
        deleteButton.setAttribute('type', 'button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');

        deleteButton.addEventListener('click', function() {
            wrapperDiv.remove();
            removeFromInputFields(wrapperDiv);
            delete editorInstances[uniqueId]; // Remove CKEditor instance associated with this row
        });

        var EditBodyCKeditor = document.createElement('button');
        EditBodyCKeditor.innerHTML = '&#9998;';
        EditBodyCKeditor.setAttribute('type', 'button');
        EditBodyCKeditor.classList.add('btn', 'btn-primary', 'btn-sm');

        EditBodyCKeditor.addEventListener('click', function() {
            openCkeditorModal(uniqueId);

            $('#saveCkeditorContent').off('click').on('click', function() {
                var editedContent = editorInstance.getData();
                editorInstances[uniqueId] = editedContent; // Store CKEditor content in the object
                $('#ckeditorModal').modal('hide');
            });
        });

        wrapperDiv.appendChild(inputField);
        wrapperDiv.appendChild(dragButton);
        wrapperDiv.appendChild(deleteButton);
        wrapperDiv.appendChild(EditBodyCKeditor);

        dynamicFieldsContainer.insertBefore(wrapperDiv, addButton);
        inputFields.push(wrapperDiv);
    }

    function openCkeditorModal(uniqueId) {
        // Clear any previous content
        editorInstance.setData('');

        // Set the CKEditor content for the specific row
        if (uniqueId && editorInstances[uniqueId]) {
            editorInstance.setData(editorInstances[uniqueId]);
        }

        $('#ckeditorModal').modal('show');
    }

    function removeFromInputFields(element) {
        var index = inputFields.indexOf(element);
        if (index !== -1) {
            inputFields.splice(index, 1);
        }
    }

    function clearInputFields() {
        inputFields.forEach(function(inputField) {
            inputField.remove();
        });
        inputFields = [];
        editorInstances = {}; // Clear all stored CKEditor data
    }
});

function allowDrop(event) {
    event.preventDefault();
}

function drop(event) {
    event.preventDefault();
    var draggedElement = document.querySelector('.dragging');
    var targetElement = event.target.closest('.dynamic-field');
    if (targetElement) {
        if (targetElement !== draggedElement) {
            if (draggedElement.compareDocumentPosition(targetElement) === Node.DOCUMENT_POSITION_FOLLOWING) {
                targetElement.parentNode.insertBefore(draggedElement, targetElement.nextSibling);
            } else {
                targetElement.parentNode.insertBefore(draggedElement, targetElement);
            }
        }
    } else {
        event.target.appendChild(draggedElement);
    }
}
 


function checkVariableNameExists(productName, callback) {
    $.ajax({
        url: '/check-variable-name',
        type: 'GET',
        data: { VariableName: productName },
        success: function(response) {
            callback(response.exists);
        },
        error: function(error) {
            console.error('Error checking variable name:', error);
        }
    });
}

function saveVariable() {
    var productName = $('#product-name-new').val();
    var description = $('#description-new').val();
    var variableType = $('#variable-type').val();

    if (!productName || !description || !variableType) {
        console.error('All fields must be filled out.');
        return;
    }

    var inputFieldValues = null;

    if (variableType !== "Single Line Text" && variableType !== "Multiple Line Text" && variableType !== "Dates") {
        inputFieldValues = [];

        inputFields.forEach(function(wrapperDiv) {
            var uniqueId = $(wrapperDiv).attr('data-id');
            var inputField = $(wrapperDiv).find('input[type="text"]').val();
            var ckEditorContent = editorInstances[uniqueId] || ''; // Get CKEditor content for this row
            inputFieldValues.push({
                id: uniqueId,
                inputValue: inputField,
                ckEditorContent: ckEditorContent
            });
        });
    }

    checkVariableNameExists(productName, function(exists) {
        if (exists) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Variable name already exists in your company. Please choose another name.'
            });
        } else {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/save-variable',
                type: 'POST',
                data: {
                    VariableName: productName,
                    description: description,
                    variableType: variableType,
                    inputFieldValues: inputFieldValues,
                    _token: csrfToken
                },
                success: function(response) {
                    console.log('Data saved successfully.');
                    $('#exampleModalNew').modal('hide');
                    location.reload();
                },
                error: function(error) {
                    console.error('Error saving data:', error);
                }
            });
        }
    });
}

 



function openModalNew() {
    // Using Bootstrap's JavaScript to open the product list modal
    var myModal = new bootstrap.Modal(document.getElementById('exampleModalNew'));
    myModal.show();
}


</script>

    <!-- For Main table --------------------------------------------------------------------------------->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="table-responsive" style="margin-top:10px;">
    
    
    <table id="ContractList" class="table">
    <thead>
        <tr>
            <th style="text-align: left;">ID</th>
            <th style="text-align: left;">@lang('translation.Variable Name')</th>
            <th style="text-align: left;">@lang('translation.Variable Type')</th>
            <th style="text-align: left;">@lang('translation.Description')</th>
            <th style="text-align: left;">@lang('translation.Created Date')</th>
            <th style="text-align: left;">@lang('translation.Updated Date')</th>
            <th style="text-align: left; width: 18%">@lang('translation.Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($variables as $contract)
            <tr>
                <td style="text-align: left;">{{ $contract->VariableID }}</td>
                <td style="text-align: left;">{{ $contract->VariableName }}</td>
                <td style="text-align: left;">{{ $contract->VariableType }}</td>
                <td style="text-align: left;">{{ $contract->Description }}</td>
                <td style="text-align: left;">{{ $contract->created_at }}</td>
                <td style="text-align: left;">{{ $contract->updated_at }}</td>
                <td style="text-align: left;">
                    <div class="btn-toolbar">
                        <button class="btn btn-primary" 
                            onclick="openModal(
                                '{{ $contract->VariableID }}', 
                                '{{ addslashes($contract->VariableName) }}', 
                                '{{ $contract->VariableType }}', 
                                '{{ addslashes($contract->Description) }}', 
                                '{{ htmlentities($contract->VariableLabelValue, ENT_QUOTES, 'UTF-8') }}'
                            )">
                                @lang('translation.Edit')
                        </button>

                        <form id="deleteForm-{{ $contract->VariableID }}" action="{{ route('contract.delete', $contract->VariableID) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="button" style="margin-left:2px;" class="btn btn-danger waves-effect waves-light"
                                onclick="confirmDelete('{{ $contract->VariableID }}');">
                                <i class="bx bx-block font-size-16 align-middle me-2"></i>   @lang('translation.Delete')
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

    
    
    
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

    <!-- JavaScript for confirmation popup -->
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
        
    function confirmDelete(contractId) {
                        $.ajax({
                            url: '/HowmanyVariable',
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                VariableID: contractId
                            },
                            success: function(response) {
                                var count = response.count;
                                var countContract = response.countContract;
                                console.log("Number of variable IDs: " + count);

                                // Display SweetAlert2 confirmation popup
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "This Variable is " + count + " times checked. Are you sure you want to delete this Variable?",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!',
                                    cancelButtonText: 'No, cancel!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                         
                                        document.getElementById('deleteForm-' + contractId).submit();
                                    } else {
                                      
                                        return false;
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                Swal.fire(
                                    'Error!',
                                    'There was an error processing your request.',
                                    'error'
                                );
                            }
                        });
                    }
</script>

 
  
 <!--------------------------------------------- edit Modal  ---------------------------------------------->
 
 <!-- Main Edit Modal -->
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> @lang('translation.Edit Variable')  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="variableForm">
                    <input type="hidden" id="variable-id" name="variable_id">
                    <div class="mb-3">
                        <label for="variable-name" class="col-form-label"> @lang('translation.Variable Name'):</label>
                        <input type="text" class="form-control" id="variable-name" name="variableName">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="variable-type-two" class="col-form-label">@lang('translation.Variable Type'):</label>
                                <select class="form-select" id="variable-type-two">
                                    <option value="Single Line Text">Single Line Text</option>
                                    <option value="Multiple Line Text">Multiple Line Text</option>
                                    <option value="Dates">Dates</option>
                                    <option value="Multiple Box">Multiple Box</option>
                                    <option value="Single Box">Single Box</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6" id="dynamic-fields-container-two">
                            <!-- Dynamic fields will be added here -->
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="col-form-label">@lang('translation.Description'):</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('translation.Close')</button>
                <button type="button" class="btn btn-primary" onclick="editVariable()"> @lang('translation.Save Variable') </button>
            </div>
        </div>
    </div>
</div>

<!-- CKEditor Modal for Editing Content -->
<div class="modal" id="ckeditorModalEdit" tabindex="-1" aria-labelledby="ckeditorModalLabelEdit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ckeditorModalLabelEdit">Edit Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="ckeditorContentEdit"></textarea>

                <style>
                    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                        border-color: var(--ck-color-base-border);
                        height: 235px !important;
                        width: 100% !important;
                    }
                    .ck.ck-editor__editable_inline>:last-child {
                        margin-bottom: var(--ck-spacing-large);
                        height: 235px;
                    }
                    .ck-editor__editable {
                        min-height: 235px;
                    }
                </style>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCkeditorContentEdit">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- arifur for search -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var inputFieldsTwo = []; // Array to store input fields for the edit modal
    var editorInstancesEdit = {}; // Object to keep track of CKEditor content by unique IDs
    var currentEditingId = null; // To keep track of the current editing row
    var editorInstanceEdit; // CKEditor instance for the edit modal

    var variableTypeSelectTwo = document.getElementById('variable-type-two');
    var dynamicFieldsContainerTwo = document.getElementById('dynamic-fields-container-two');
    var addButtonTwo = null;

    // Initialize CKEditor for the Edit modal
    ClassicEditor
        .create(document.querySelector('#ckeditorContentEdit'), {
            ckfinder: {
                uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            }
        })
        .then(newEditor => {
            editorInstanceEdit = newEditor; // Assign to the global variable
            console.log('CKEditor initialized for Edit modal:', editorInstanceEdit);
        })
        .catch(error => {
            console.error('CKEditor initialization error for Edit modal:', error);
        });

    // Event listener for variable type selection
    variableTypeSelectTwo.addEventListener('change', function () {
        clearInputFieldsTwo();
        dynamicFieldsContainerTwo.innerHTML = '';
        var selectedOptionTwo = variableTypeSelectTwo.value;

        if (selectedOptionTwo === 'Multiple Box' || selectedOptionTwo === 'Single Box') {
            addButtonTwo = createAddButtonTwo();
            dynamicFieldsContainerTwo.appendChild(addButtonTwo);
        }
    });

    function createAddButtonTwo() {
        addInputFieldsTwo();
        var addButtonTwo = document.createElement('button');
        addButtonTwo.textContent = 'Add';
        addButtonTwo.classList.add('form-control');
        addButtonTwo.style = "width: 58px;";
        addButtonTwo.setAttribute('type', 'button');
        addButtonTwo.addEventListener('click', function () {
            addInputFieldTwo();
        });
        return addButtonTwo;
    }

    function addInputFieldsTwo() {
        inputFieldsTwo.forEach(function (inputField) {
            dynamicFieldsContainerTwo.appendChild(inputField);
        });
    }

    function addInputFieldTwo() {
        var uniqueId = 'row_' + new Date().getTime(); // Unique ID for each row

        var wrapperDiv = document.createElement('div');
        wrapperDiv.classList.add('dynamic-field');
        wrapperDiv.setAttribute('data-id', uniqueId);

        var inputField = document.createElement('input');
        inputField.setAttribute('type', 'text');
        inputField.setAttribute('placeholder', 'Enter value');
        inputField.style.border = '1px solid #ccc';
        inputField.style.borderRadius = '5px';
        inputField.style.padding = '8px 12px';
        inputField.style.width = '200px';
        inputField.style.boxSizing = 'border-box';
        inputField.setAttribute('data-id', uniqueId);

        var dragButton = document.createElement('button');
        dragButton.textContent = '☰';
        dragButton.setAttribute('type', 'button');
        dragButton.classList.add('btn', 'btn-primary', 'btn-sm', 'drag-button');

        wrapperDiv.addEventListener('dragstart', function(event) {
            event.dataTransfer.setData('text/plain', '');
            event.currentTarget.classList.add('dragging');
            event.stopPropagation();
        });

        wrapperDiv.setAttribute('draggable', 'true');

        var deleteButton = document.createElement('button');
        deleteButton.innerHTML = '&#10005;';
        deleteButton.setAttribute('type', 'button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');

        deleteButton.addEventListener('click', function() {
            wrapperDiv.remove();
            removeFromInputFieldsTwo(wrapperDiv);
            delete editorInstancesEdit[uniqueId]; // Remove CKEditor instance associated with this row
        });

        var EditBodyCKeditor = document.createElement('button');
        EditBodyCKeditor.innerHTML = '&#9998;';
        EditBodyCKeditor.setAttribute('type', 'button');
        EditBodyCKeditor.classList.add('btn', 'btn-primary', 'btn-sm');

        EditBodyCKeditor.addEventListener('click', function() {
            currentEditingId = uniqueId; // Set current editing ID
            openCkeditorModalEdit(uniqueId); // Open the CKEditor modal with the correct content
        });

        wrapperDiv.appendChild(inputField);
        wrapperDiv.appendChild(dragButton);
        wrapperDiv.appendChild(deleteButton);
        wrapperDiv.appendChild(EditBodyCKeditor);

        // Remove the Add button temporarily
        var addButton = dynamicFieldsContainerTwo.querySelector('.form-control');

        dynamicFieldsContainerTwo.removeChild(addButton);

        // Append the new row
        dynamicFieldsContainerTwo.appendChild(wrapperDiv);

        // Re-append the Add button
        dynamicFieldsContainerTwo.appendChild(addButton);

        // Add the new field to the inputFieldsTwo array
        inputFieldsTwo.push(wrapperDiv);

        // Make the new row draggable
        makeRowDraggable(wrapperDiv);
    }


    function removeFromInputFieldsTwo(element) {
        var index = inputFieldsTwo.indexOf(element);
        if (index !== -1) {
            inputFieldsTwo.splice(index, 1);
        }
    }

    function clearInputFieldsTwo() {
        inputFieldsTwo.forEach(function (inputField) {
            inputField.remove();
        });
        inputFieldsTwo = [];
        editorInstancesEdit = {}; // Clear all stored CKEditor data
    }

    // Function to make a row draggable
    function makeRowDraggable(row) {
        var dragButton = row.querySelector('.drag-button');

        dragButton.addEventListener('dragstart', function (event) {
            event.dataTransfer.setData('text/plain', ''); // Required for drag-and-drop
            row.classList.add('dragging'); // Add class to the dragged element
            event.stopPropagation(); // Prevents parent drag event
        });

        row.addEventListener('dragover', function (event) {
            event.preventDefault(); // Prevent default behavior to allow drop
        });

        row.addEventListener('dragenter', function (event) {
            row.classList.add('drag-over'); // Add class to indicate potential drop target
        });

        row.addEventListener('dragleave', function (event) {
            row.classList.remove('drag-over'); // Remove class to indicate no longer potential drop target
        });

        row.addEventListener('drop', function (event) {
            var draggedRow = document.querySelector('.dragging');
            var dropTargetRow = event.currentTarget;

            if (draggedRow.offsetTop < dropTargetRow.offsetTop) {
                dropTargetRow.parentNode.insertBefore(draggedRow, dropTargetRow.nextSibling);
            } else {
                dropTargetRow.parentNode.insertBefore(draggedRow, dropTargetRow);
            }

            draggedRow.classList.remove('dragging');
            dropTargetRow.classList.remove('drag-over');
        });
    }

    // Function to open the CKEditor modal and load the correct content
    function openCkeditorModalEdit(uniqueId) {
        if (editorInstanceEdit) {
            editorInstanceEdit.setData(''); // Clear any previous content
            var content = editorInstancesEdit[uniqueId] || ''; // Load the content specific to the current row
            editorInstanceEdit.setData(content);
        }

        $('#ckeditorModalEdit').modal('show');

        $('#saveCkeditorContentEdit').off('click').on('click', function () {
            if (editorInstanceEdit) {
                var editedContent = editorInstanceEdit.getData();
                editorInstancesEdit[uniqueId] = editedContent; // Store CKEditor content in the object
                $('#ckeditorModalEdit').modal('hide');
            }
        });
    }
    
 
 function openModal(variableID, variableName, variableType, description, variableLabelValue) {
    document.getElementById('variable-id').value = variableID;
    document.getElementById('variable-name').value = variableName;
    document.getElementById('variable-type-two').value = variableType;
    document.getElementById('description').value = description;

    var dynamicFieldsContainerTwo = document.getElementById('dynamic-fields-container-two');
    dynamicFieldsContainerTwo.innerHTML = ''; // Clear previous content

    if (variableLabelValue && !['Single Line Text', 'Multiple Line Text', 'Dates'].includes(variableType)) {
        try {
            // Decode the JSON string
            variableLabelValue = variableLabelValue.replace(/&quot;/g, '"')
                                                    .replace(/&amp;/g, '&')
                                                    .replace(/&lt;/g, '<')
                                                    .replace(/&gt;/g, '>')
                                                    .replace(/&#39;/g, "'");

            console.log('Decoded JSON string:', variableLabelValue);  // Log the decoded JSON string
            var variableLabelValueObj = JSON.parse(variableLabelValue);  // Attempt to parse the JSON

            // Check if the parsed object is an array
            if (Array.isArray(variableLabelValueObj)) {
                variableLabelValueObj.forEach(function (item) {
                    var uniqueId = item.id || 'row_' + new Date().getTime(); // Use existing ID or generate a new one

                    var wrapperDiv = document.createElement('div');
                    wrapperDiv.classList.add('dynamic-field');
                    wrapperDiv.setAttribute('data-id', uniqueId);

                    var inputField = document.createElement('input');
                    inputField.setAttribute('type', 'text');
                    inputField.setAttribute('value', item.inputValue);
                    inputField.style.border = '1px solid #ccc';
                    inputField.style.borderRadius = '5px';
                    inputField.style.padding = '8px 12px';
                    inputField.style.width = '200px';
                    inputField.style.boxSizing = 'border-box';
                    inputField.setAttribute('data-id', uniqueId);

                    var dragButton = document.createElement('button');
                    dragButton.textContent = '☰';
                    dragButton.setAttribute('type', 'button');
                    dragButton.classList.add('btn', 'btn-primary', 'btn-sm', 'drag-button');

                    wrapperDiv.addEventListener('dragstart', function (event) {
                        event.dataTransfer.setData('text/plain', '');
                        event.currentTarget.classList.add('dragging');
                        event.stopPropagation();
                    });

                    wrapperDiv.setAttribute('draggable', 'true');

                    var deleteButton = document.createElement('button');
                    deleteButton.innerHTML = '&#10005;';
                    deleteButton.setAttribute('type', 'button');
                    deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');

                    deleteButton.addEventListener('click', function () {
                        wrapperDiv.remove();
                        removeFromInputFieldsTwo(wrapperDiv);
                        delete editorInstancesEdit[uniqueId];
                    });

                    var EditBodyCKeditor = document.createElement('button');
                    EditBodyCKeditor.innerHTML = '&#9998;';
                    EditBodyCKeditor.setAttribute('type', 'button');
                    EditBodyCKeditor.classList.add('btn', 'btn-primary', 'btn-sm');

                    EditBodyCKeditor.addEventListener('click', function () {
                        currentEditingId = uniqueId; // Set current editing ID
                        openCkeditorModalEdit(uniqueId); // Open the CKEditor modal with the correct content
                    });

                    wrapperDiv.appendChild(inputField);
                    wrapperDiv.appendChild(dragButton);
                    wrapperDiv.appendChild(deleteButton);
                    wrapperDiv.appendChild(EditBodyCKeditor);

                    dynamicFieldsContainerTwo.appendChild(wrapperDiv);
                    makeRowDraggable(wrapperDiv);

                    // Save CKEditor content if present
                    if (item.ckEditorContent) {
                        editorInstancesEdit[uniqueId] = item.ckEditorContent;
                    }
                });
            } else {
                console.error('Parsed JSON is not an array:', variableLabelValueObj);
            }
        } catch (error) {
            console.error('Failed to parse JSON:', error);
            console.log('Invalid JSON string:', variableLabelValue);  // Log the invalid JSON string
        }
    }

    if (variableType === 'Multiple Box' || variableType === 'Single Box') {
        var addButton = document.createElement('button');
        addButton.textContent = 'Add';
        addButton.classList.add('form-control');
        addButton.style = "width: 58px;";
        addButton.setAttribute('type', 'button');
        addButton.addEventListener('click', function () {
            addInputFieldTwo();
        });
        dynamicFieldsContainerTwo.appendChild(addButton);
    }

    var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
    modal.show();
}

 

    // Function to save the edited variable

    function checkVariableNameExistsForEdit(productName, callback) {
        $.ajax({
            url: '/check-variable-name',
            type: 'GET',
            data: { VariableName: productName },
            success: function(response) {
                callback(response.exists);
            },
            error: function(error) {
                console.error('Error checking variable name:', error);
            }
        });
    }
 


    function editVariable() {

        var VariableID = $('#variable-id').val();
        var VariableName = $('#variable-name').val();
        var VariableType = $('#variable-type-two').val();
        var Description = $('#description').val();

        var inputFieldValues = [];

        $('#dynamic-fields-container-two .dynamic-field').each(function () {
            var uniqueId = $(this).data('id');
            var inputValue = $(this).find('input[type="text"]').val();
            var ckEditorContent = editorInstancesEdit[uniqueId] || '';

            inputFieldValues.push({
                id: uniqueId,
                inputValue: inputValue,
                ckEditorContent: ckEditorContent
            });
        });


        checkVariableNameExistsForEdit(VariableName, function(exists) {
        if (exists) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Variable name already exists in your company. Please choose another name.'
            });
        } else {
            $.ajax({
                url: '/update-variable/' + VariableID,
                method: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'VariableName': VariableName,
                    'VariableType': VariableType,
                    'Description': Description,
                    'inputFieldValues': inputFieldValues
                },
                success: function (data) {
                    $('#exampleModal').modal('hide');
                    location.reload();
                },
                error: function (error) {
                    console.error('Error updating variable:', error);
                }
            });
        } 
        });

 

                                    // $.ajax({


                                    //     url: '/update-variable/' + VariableID,
                                    //     method: 'POST',
                                    //     data: {
                                    //         '_token': $('meta[name="csrf-token"]').attr('content'),
                                    //         'VariableName': VariableName,
                                    //         'VariableType': VariableType,
                                    //         'Description': Description,
                                    //         'inputFieldValues': inputFieldValues
                                    //     },
                                    //     success: function (data) {
                                    //         $('#exampleModal').modal('hide');
                                    //         location.reload();
                                    //     },
                                    //     error: function (error) {
                                    //         console.error('Error updating variable:', error);
                                    //     }
                                    // });
    }
        // Ensure it's available globally
        window.openModal = openModal;
            // Ensure it's available globally
         window.editVariable = editVariable;
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
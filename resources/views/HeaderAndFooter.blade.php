@extends('layouts.master')
@section('title')
    @lang('translation.HeaderAndFooter')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.Header And Footer Entries')
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
            <button type="button" class="btn btn-primary" onclick="openModalNew()">@lang('translation.Add New HeaderOrFooter')</button>
        </div>
    </div>
</div>


                    <style>
                        
                        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                            border-color: var(--ck-color-base-border);
                            height: 100px !important;
                        
                        }
                        .ck.ck-editor__editable_inline>:last-child {
                            margin-bottom: var(--ck-spacing-large);
                            height: 100px;
                        }

                        </style>
 
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelNew">New Header/Footer Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="headerFooterFormNew" novalidate>
                    <div class="mb-3">
                        <label for="entry-name" class="col-form-label">Name:</label>
                        <input type="text" class="form-control" id="entry-name" required>
                        <div class="invalid-feedback">
                            Please provide a name for the entry.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="entry-type" class="col-form-label">Type:</label>
                        <select class="form-select" id="entry-type" required>
                            <option value="Header">Header</option>
                            <option value="Footer">Footer</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a type for the entry.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="entry-editor-content" class="col-form-label">Editor Content:</label>
                        <textarea  class="form-control" id="entry-editor-content" required></textarea>
                        <div class="invalid-feedback">
                            Please provide content for the entry.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveHeaderFooter()">Save Entry</button>
            </div>
        </div>
    </div>
</div>

    <!-- Edit Modal -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Header/Footer Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="entryForm">
                        <input type="hidden" id="entry-id-edit" name="entry_id-edit">
                        <div class="mb-3">
                            <label for="entry-name-edit" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="entry-name-edit" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="entry-type-edit" class="col-form-label">Type:</label>
                            <select class="form-select" id="entry-type-edit" name="type">
                                <option value="Header">Header</option>
                                <option value="Footer">Footer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="entry-editor-content-edit" class="col-form-label">Editor Content:</label>
                            <textarea class="form-control" id="entry-editor-content-edit" name="entry-editor-content-edit" rows="3">

                            
                            </textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="editEntry()">Save Entry</button>
                </div>
            </div>
        </div>
    </div>

    <!-- for table -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="table-responsive" style="margin-top:10px;">
    <table id="HeaderAndFooterList" class="table">
        <thead>
            <tr>
        

                <th style="text-align: left;">ID</th>
                <th style="text-align: left;">@lang('translation.Name')</th>
                <th style="text-align: left;">@lang('translation.Type')</th>
                <th style="text-align: left;">@lang('translation.Created Date')</th>
                <th style="text-align: left;">@lang('translation.Updated Date')</th>
                <th style="text-align: left; width: 18%;">@lang('translation.Action')</th>

            </tr>
        </thead>
       
        <tbody>
           @foreach($headerAndFooterEntries as $entry)
                <tr>
                    <td style="text-align: left;" >{{ $entry->id }}</td>
                    <td  style="text-align: left;" >{{ $entry->name }}</td>
                    <td style="text-align: left;" >{{ $entry->type }}</td>
                    <!-- <td>{{ $entry->editor_content }}</td> -->
                    <td style="text-align: left;" >{{ $entry->created_at }}</td>
                    <td style="text-align: left;">{{ $entry->updated_at }}</td>
                    <td style="text-align: left;">


                    <div class="btn-toolbar">
                                <button class="btn btn-primary"
                                onclick="openModal('{{ $entry->id }}', '{{ $entry->name }}', '{{ $entry->type }}', '{{ $entry->editor_content }}')">
                                @lang('translation.Edit')</button>


                                 <form id="deleteForm-{{ $entry->id }}" action="{{ route('entry.delete', $entry->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('POST')
                                </form>

                                <button type="button" style="margin-left:2px;" class="btn btn-danger waves-effect waves-light" 
                                onclick="confirmDelete('{{ $entry->id }}');">
                                <i class="bx bx-block font-size-16 align-middle me-2"></i> @lang('translation.Delete')
                                </button>

                               
                            </div>

<!-- 

    <div class="dropdown">
        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="mdi mdi-dots-horizontal font-size-18"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            <ul class="dropdown-menu dropdown-menu-end show" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-31px, 27px, 0px);" data-popper-placement="bottom-end">
              
                
                   
                <a href="#" class="dropdown-item edit-list" 
                onclick="openModal('{{ $entry->id }}', '{{ $entry->name }}', '{{ $entry->type }}', '{{ $entry->editor_content }}')">
                    <i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit
                </a>
 
                <form id="deleteForm-{{ $entry->id }}" action="{{ route('entry.delete', $entry->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('POST')
                </form>

                <a href="#" class="dropdown-item edit-list" onclick="confirmDelete('{{ $entry->id }}');">
                    <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete
                </a>



            </ul>
        </div>
    </div>
 -->



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

                $(document).ready(function() {
                        let table = new DataTable('#HeaderAndFooterList', {
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


                    function confirmDelete(entryId) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Do you want to delete this Record?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('deleteForm-' + entryId).submit();
                            } else {
                                return false;
                            }
                        });
                    }
                </script>

 
 





<!-- arifur for search -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script> -->

<!--  classic CSKEDitor custom build  -->
<script src="{{ asset('js/ckeditor/build/ckeditor.js') }}"></script>
<script>
    let editor; // Global variable for main CKEditor instance

    ClassicEditor
        .create(document.querySelector('#entry-editor-content'),{
                ckfinder: {
                        uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
                    }
            }
            )
        .then(newEditor => {
            editor = newEditor;
            console.log('CKEditor initialized and editor assigned:', editor);
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });

     let editormodal;

     ClassicEditor
        .create(document.querySelector('#entry-editor-content-edit'),{
                ckfinder: {
                        uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
                    }
            }
            )
        .then(newEditor => {
            editormodal = newEditor;
            console.log('CKEditor initialized and editor assigned:', editormodal);
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });


     
     

    function saveHeaderFooter() {
        if (!editor) {
            console.error('CKEditor is not initialized or editor is not assigned.');
            return;
        }

        var name = document.getElementById('entry-name').value;
        var type = document.getElementById('entry-type').value;
        var editorContent = editor.getData(); // Get CKEditor content
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        $.ajax({
            url: '/header-and-footer/save',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                name: name,
                type: type,
                editor_content: editorContent
            },
            success: function(response) {
                if (response.success) {
                    // Handle success, maybe show a message
                    location.reload();
                } else {
                    console.error('Error saving header/footer entry: ', response.message);
                }
            },
            error: function(error) {
                console.error('Error saving header/footer entry: ', error.responseText);
            }
        });
    }



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
 
    function openModal(entryId, name, type, editorContent) {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();

        // Set values in the modal form
        document.getElementById('entry-id-edit').value = entryId;
        document.getElementById('entry-name-edit').value = name;
        document.getElementById('entry-type-edit').value = type;
       // document.getElementById('entry-editor-content-edit').value = editorContent;
        editormodal.setData(editorContent);
    }

    $('#exampleModal').modal('hide');

     
 

       // Function to handle entry edit
       function editEntry() {
        var id = document.getElementById('entry-id-edit').value;
        var name = document.getElementById('entry-name-edit').value;
        var type = document.getElementById('entry-type-edit').value;
        var editorContent = editormodal.getData(); // Get CKEditor content
         

        // AJAX request to update the entry
        $.ajax({
            url: '/header-and-footer/update/' + id, // Update the URL to match your Laravel route
            method: 'POST',
            data: {
                '_token': '{{ csrf_token() }}', // Add CSRF token for Laravel
                'name': name,
                'type': type,
                'editor_content': editorContent
            },
            success: function (data) {
                // Handle success, for example, close the modal
                var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
                myModal.hide();
                // You can perform additional actions here if needed
                location.reload(); // Reload the page to reflect changes
            },
            error: function (error) {
                // Handle error
                console.error('Error updating header/footer entry:', error);
            }
        });
    }


</script>


<div id="spinner-overlay">
        <div id="spinner"></div>
    </div>



<style>
        /* Add your CSS styles for the spinner here for loading  */
        #spinner-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
            z-index: 9999; /* Ensures it is above other elements */
        }

        #spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>


   <!-- Include jQuery -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $(document).ajaxStart(function() {
        $("#spinner-overlay").show();
    }).ajaxStop(function() {
        $("#spinner-overlay").hide();
    });
});
</script>

@endsection


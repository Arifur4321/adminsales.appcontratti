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
            arifur table
        @endslot
    @endcomponent
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <!--  Arifur change  -->
     <div class="col-sm">
    <div class="search-box me-2 d-inline-block">
        <div class="position-relative">
            <input type="text" class="form-control" autocomplete="off" id="searchTableList" placeholder="Search...">
            <i class="bx bx-search-alt search-icon"></i>
        </div>
    </div>
</div>

<!-- arifur for search -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Reference to the input field and the table
        var $searchInput = $('#searchTableList');
        var $table = $('#projectTable');

        // Event listener for keyup on the search input
        $searchInput.on('keyup', function () {
            var searchText = $(this).val().toLowerCase();

            // Filter the table rows based on the search text
            $table.find('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });
    });
</script>


<!-- Your existing button -->
<div class="col-sm-auto">
    <div class="text-sm-end">
        <button type="button" class="btn btn-primary" onclick="openModalNew()">Add New Project</button>
    </div>
</div>


<!-- Modal -->
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelNew">New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="projectFormNew">
                    <div class="mb-3">
                        <label for="project-name-new" class="col-form-label">Project Name:</label>
                        <input type="text" class="form-control" id="project-name-new">
                    </div>
                    <div class="mb-3">
                        <label for="due-date-new" class="col-form-label">Due Date:</label>
                        <input type="date" class="form-control" id="due-date-new">
                    </div>
                    <div class="mb-3">
                        <label for="status-new" class="col-form-label">Status:</label>
                        <select class="form-select" id="status-new">
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="team-new" class="col-form-label">Team:</label>
                        <input type="text" class="form-control" id="team-new">
                    </div>
                    <div class="mb-3">
                        <label for="action-new" class="col-form-label">Action:</label>
                        <input type="text" class="form-control" id="action-new">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveProject()">Save Project</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for the new modal -->
<script>
    function openModalNew() {
        // Using Bootstrap's JavaScript to open the modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModalNew'));
        myModal.show();
    }
    function saveProject() {
    // Get form data
    var projectName = $('#project-name-new').val();
    var dueDate = $('#due-date-new').val();
    var status = $('#status-new').val();
    var team = $('#team-new').val();
    var action = $('#action-new').val();

    // Basic validation
    if (!projectName || !dueDate || !status || !team || !action) {
        console.error('All fields must be filled out.');
        return;
    }

    // Get the CSRF token from the meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send data to the server using AJAX
    $.ajax({
        url: '/save-project',
        type: 'POST',
        data: {
            projectName: projectName,
            dueDate: dueDate,
            status: status,
            team: team,
            action: action
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            // Handle success
            console.log('Data saved successfully.');
            // Optionally, close the modal or perform other actions.
        },
        error: function (error) {
            // Handle error
            console.error('Error saving data:', error);
        }
    });

    $('#exampleModalNew').modal('hide');
}

</script>



 
<script>
    function openModal() {
        // Using Bootstrap's JavaScript to open the modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
    }
</script>

 


    <!-- resources/views/projects/index.blade.php -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <table id="projectTable" class="table">
    <thead>
        <tr>
            <th>Project ID</th>
            <th>Project Name</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Team</th>
            <th>Action</th>
            <!-- Add more headers for other columns if needed -->
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->projectName }}</td>
                <td>{{ $project->dueDate }}</td>
                <td>{{ $project->status }}</td>
                <td>{{ $project->team }}</td>
                <td>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-horizontal font-size-18"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <ul class="dropdown-menu dropdown-menu-end show" 
                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-31px, 27px, 0px);" 
                    data-popper-placement="bottom-end">
                  
                    <li>
                        <a href="#" class="dropdown-item edit-list" onclick="openModal('{{ $project->id }}', '{{ $project->projectName }}', '{{ $project->dueDate }}', '{{ $project->status }}', '{{ $project->team }}')">
                            <i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit
                        </a>
                    </li> 
 


                    <li>
                    <li><a href="{{ url('delete/'.$project->id) }}" class="dropdown-item remove-list"  >
                            <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Remove
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </td>
                <!-- Add more cells for other columns if needed -->
            </tr>
        @endforeach
    </tbody>


</table>
<span>

{{ $projects->links() }}
</span>

<style>
    .w-5{
        display:none;
    }
</style>

<!-- Delete Modal -->
<div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this project?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteProject()">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>

function openDeleteModal(projectId) {
    // Set the project id in a hidden input field inside the modal
    $('#deleteModal').find('#delete-project-id').val(projectId);

    // Show the delete modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function deleteProject() {
    var projectId = $('#deleteModal').find('#delete-project-id').val();

    // Call your Laravel route to delete the project
    $.ajax({
    url: '/delete-project/' + projectId,
    type: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (response) {
        // Handle success
        console.log('Project deleted successfully.');
        // Optionally, update the table or perform other actions.
        // Close the delete modal
        $('#deleteModal').modal('hide');
    },
    error: function (error) {
        // Handle error
        console.error('Error deleting project:', error);
        // Optionally, display an error message to the user.
    }
});
}


</script>


<!-- edit Modal -->
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="projectForm">
                <input type="hidden" id="project-id" name="project_id">
                <div class="mb-3">
                    <label for="project-name" class="col-form-label">Project name:</label>
                    <input type="text" class="form-control" id="project-name" name="projectName">
                </div>
                    <div class="mb-3">
                        <label for="due-date" class="col-form-label">Due Date:</label>
                        <input type="date" class="form-control" id="due-date">
                    </div>
                    
                    <div class="mb-3">
                        <!-- <label for="team" class="col-form-label">Status:</label>
                        <input type="text" class="form-control" id="status"> -->

                        <label for="status" class="col-form-label">Status:</label>
                        <select class="form-select" id="status-edit">
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="team" class="col-form-label">Team:</label>
                        <input type="text" class="form-control" id="team">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="action" class="col-form-label">Action:</label>
                        <input type="text" class="form-control" id="action">
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editProject()">Save Project</button>
            </div>
        </div>
    </div>
</div>
<!-- for open edit modal  -->
<script>
    function openModal(id, projectName, dueDate, status, team) {
        // Using Bootstrap's JavaScript to open the modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();

        // Set values in the modal form
        document.getElementById('project-id').value = id;
        document.getElementById('project-name').value = projectName;
        document.getElementById('due-date').value = dueDate;
        document.getElementById('status-edit').value = status;
        document.getElementById('team').value = team;
    }
</script>


<script>
    function editProject() {
        var projectId = document.getElementById('project-id').value;
        var projectName = document.getElementById('project-name').value;
        var dueDate = document.getElementById('due-date').value;
        var status = document.getElementById('status-edit').value;
        var team = document.getElementById('team').value;

        // AJAX request to update the project
        $.ajax({
            url: '/update-project/' + projectId,
            method: 'POST',
            data: {
                '_token': '{{ csrf_token() }}', // Add CSRF token for Laravel
                'projectName': projectName,
                'dueDate': dueDate,
                'status': status,
                'team': team
            },
            success: function (data) {
                // Handle success, for example, close the modal
                var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
                myModal.hide();
                // You can perform additional actions here if needed
            },
            error: function (error) {
                // Handle error
                console.error('Error updating project:', error);
            }
        });

        $('#exampleModal').modal('hide');
    }
</script>



<!-- 
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to fetch and populate projects
        function fetchProjects() {
            $.ajax({
                url: '/alldata', // Change this URL to your endpoint
                method: 'GET',
                success: function (data) {
                    updateTable(data);
                },
                error: function (error) {
                    console.error('Error fetching projects:', error);
                }
            });
        }

        // Function to update the table with project data
        function updateTable(projects) {
            var tableBody = $('#projectTable tbody');
            tableBody.empty(); // Clear existing rows

            if (projects.length > 0) {
                $.each(projects, function (index, project) {
                    var row = $('<tr>').attr('data-project-id', project.id);
                    row.append($('<td>').text(project.id));
                    row.append($('<td>').text(project.projectName));
                    row.append($('<td>').text(project.dueDate));
                    row.append($('<td>').text(project.status));
                    row.append($('<td>').text(project.team));
                    row.append($('<td>').html('<div class="dropdown">' +
                        '<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">' +
                        '<i class="mdi mdi-dots-horizontal font-size-18"></i></a>' +
                        '<div class="dropdown-menu dropdown-menu-end">' +
                        '<ul class="dropdown-menu dropdown-menu-end show" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-31px, 27px, 0px);" data-popper-placement="bottom-end">' +
                        '<li><a href="#" class="dropdown-item edit-list"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>' +
                        '<li><a href="#" class="dropdown-item remove-list"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Remove</a></li>' +
                        '</ul></div></div>'));

                    tableBody.append(row);
                });
            } else {
                tableBody.append('<tr><td colspan="6">No projects found.</td></tr>');
            }
        }

        // Initial load of projects
        fetchProjects();

        // Optional: Set up a timer to refresh the data periodically
        // setInterval(fetchProjects, 60000); // Refresh every 60 seconds
    });
</script> -->

@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Domain Details') }}</div>

                    <div class="card-body">
                        @if (isset($errors) && $errors->any())
                            <div class="alert alert-danger" role="alert" id="error-alert">
                                @error('domain_name')
                                    <li>{{ $message }}</li>
                                @enderror
                                @error('project_name')
                                    <li>{{ $message }}</li>
                                @enderror
                                @error('maintenance_page')
                                    <li>{{ $message }}</li>
                                @enderror
                            </div>
                            <script>
                                // Automatically hide the error alert after 5 seconds
                                setTimeout(function() {
                                    var errorAlert = document.getElementById('error-alert');
                                    errorAlert.style.display = 'none';
                                }, 5000);
                            </script>
                        @endif

                        {{-- @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif --}}

                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Domain Name</th>
                                    <th>Project Name</th>
                                    <th>Maintenance Page</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($domains as $domain)
                                    <tr>
                                        <td>{{ $domain->id }}</td>
                                        <td>{{ $domain->domain_name }}</td>
                                        <td>{{ $domain->project_name }}</td>
                                        <td>{{ $domain->maintenance_page }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm edit-btn" data-toggle="modal"
                                                data-target="#editModal" data-domain-id="{{ $domain->id }}">Edit</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button class="btn btn-primary" id="add-row-btn" data-toggle="modal" data-target="#newModal">Add
                            Row</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-center">
        {{ $domains->links() }}
    </div>

    <!-- New Record Modal -->
    <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newModalLabel">New Row</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newForm" action="{{ route('domain.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="column1">Domain Name</label>
                            <input type="text" class="form-control" name="domain_name" />
                        </div>
                        <div class="form-group">
                            <label for="column2">Project Name</label>
                            <input type="text" class="form-control" name="project_name" />
                        </div>
                        <div class="form-group">
                            <label for="text">Maintenance Page</label>
                            <input type="file" class="form-control" name="maintenance_page" />
                        </div>
                        <div class="form-group">
                            <label for="text">Maintenance Page CSS</label>
                            <input type="file" class="form-control" name="maintenance_page_css" />
                        </div>
                        <div class="form-group">
                            <label for="text">Maintenance Page JS</label>
                            <input type="file" class="form-control" name="maintenance_page_js" />
                        </div>
                        <button type="submit" class="btn btn-primary" >Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    {{-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Row</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="{{ route('domain.update',$domain->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <input type="hidden" name="domainId" id="domain_id">

                        <div class="form-group">
                            <label for="column2">Project Name</label>
                            <input type="text" class="form-control" name="project_name" id="project_name">
                        </div>
                        <div class="form-group">
                            <label for="text">Maintenance Page</label>
                            <input type="file" class="form-control" name="maintenance_page" id="maintenance_page" />
                        </div>
                        <div class="form-group">
                            <label for="text">Maintenance Page CSS</label>
                            <input type="file" class="form-control" name="maintenance_page_css" />
                        </div>
                        <div class="form-group">
                            <label for="text">Maintenance Page JS</label>
                            <input type="file" class="form-control" name="maintenance_page_js" />
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}


    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            const addRowBtn = document.getElementById('add-row-btn');
            const dataTable = document.getElementById('data-table');
            const editButtons = document.getElementsByClassName('edit-btn');
            const saveChangesBtn = document.querySelector('#editModal .modal-body form button[type="submit"]');
            const editForm = document.getElementById('editForm');

            let editRowIndex; // Track the index of the row being edited


            // Attach event listeners to each edit button
            $(document).on('click', '.edit-btn', function() {
                editRowIndex = $(this).closest('tr').index(); // Set the current edit row index
                const rowData = dataTable.querySelectorAll('tbody tr')[editRowIndex].querySelectorAll('td');

                // // Populate the edit modal fields with row data
                document.getElementById('domain_name').value = rowData[1].textContent;
                document.getElementById('project_name').value = rowData[2].textContent;
                document.getElementById('domain_id').value = $(this).data('domain-id');
            });
        });
    </script>
@endsection

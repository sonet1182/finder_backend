@extends('backend.layouts.master')

@section('title', 'Notice')
@section('breadcrumb', 'Notice List')

@section('content')

    <div class="card card-default">


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <div class="card-header">
            <div class="d-flex">
                <div class="ml-auto">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal"> <i
                            class="fa fa-plus" aria-hidden="true"></i> Create New Notice </button>
                </div>
            </div>


            <!-- Create Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create New Notice</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="codeForm" class="needs-validation" action="{{ route('notices.store') }}"
                                method="POST" novalidate enctype="multipart/form-data">
                                @csrf

                                <div class="form-row">


                                    <div class="col-md-12 mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control form-control-sm" id="title"
                                            name="title" placeholder="Enter title"
                                            value="{{ !empty($post) ? $post->title : old('title') }}" required>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label>Show Date and time range:</label>

                                        <div class="form-check">
                                            <input class="form-check-input" name="limit" type="checkbox"
                                                id="showRangeCheckbox">
                                            <label class="form-check-label" for="showRangeCheckbox">
                                                Add Time Limit
                                            </label>
                                        </div>

                                        <div class="input-group" id="rangeInputGroup" style="display: none;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            </div>
                                            <input type="text" name="range" class="form-control float-right"
                                                id="reservationtime" value="">
                                            <div class="invalid-feedback">
                                                @error('range')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    @include('backend.notice.textarea')


                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom02">Status</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="validationCustom"
                                                name="status" {{ old('status') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="validationCustom"></label>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>


        </div>


        <div class="card-body">



            <table class="table table-bordered yajra-datatable">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th class="text-center">Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg"" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Notice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="teacher_box"></div>
                    </div>

                </div>
            </div>
        </div>



    </div>
@endsection


@section('scripts')

    <script type="text/javascript">
        var table;

        $(document).ready(function() {
            // Initialize the DataTable
            table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('notices.index') }}",
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'start',
                        name: 'start'
                    },
                    {
                        data: 'end',
                        name: 'end'
                    },
                    {
                        data: 'status_check',
                        name: 'status_check'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
            // Handle delete button click event
            $('.yajra-datatable').on('click', '.delete-btn', function() {
                var rowId = $(this).data('row-id');

                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        url: '/admin/notices/' + rowId,
                        type: 'DELETE',
                        data: {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                            table.ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('Error deleting record');
                        }
                    });
                }
            });

            // Attach form submission handler outside of modal events to avoid multiple bindings
            $('#bannerForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            $('#bannerForm')[0].reset();
                            $('#exampleModal').modal('hide');
                            $('#image-preview').empty();

                            toastr.success("Banner has been added");
                            table.ajax.reload();
                        } else {
                            alert('Error submitting form');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        var errorResponse = JSON.parse(xhr.responseText);
                        toastr.error(errorResponse.message);
                    }
                });
            });

            // Event listener for modal show to load form data for edit
            $('#exampleModal1').on('shown.bs.modal', function() {
                // Update data by ajax form submit
                $('#updateForm').submit(function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.success) {
                                $('#updateForm')[0].reset();
                                $('#exampleModal1').modal('hide');

                                toastr.success("Notice has been Updated");
                                table.ajax.reload();
                            } else {
                                alert('Error submitting form');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            var errorResponse = JSON.parse(xhr.responseText);
                            toastr.error(errorResponse.message);
                        }
                    });
                });
            });
        });
    </script>


    <script>
        $(document).on('click', '.edit-btn', function(event) {
            event.preventDefault();

            $('#exampleModal1').modal('show');

            var banner_id = $(this).data('row-id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="token"]').attr('value')
                }
            });

            $.ajax({
                url: "{{ route('notices.edit', ['notice' => ':banner_id']) }}".replace(':banner_id',
                    banner_id),
                type: 'GET',
                success: function(res) {
                    $("#teacher_box").empty();
                    $("#teacher_box").append(res);
                },
                error: function(data) {
                    console.error(data.responseText);
                }
            });
        });
    </script>



    <script>
        $(function() {
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 15,
                locale: {
                    format: 'MM/DD/YYYY h:mm A'
                }
            });
        });
    </script>


    <script>
        $('#exampleModal').on('shown.bs.modal', function() {
            // document.addEventListener('DOMContentLoaded', function() {
            const showRangeCheckbox = document.getElementById('showRangeCheckbox');
            const rangeInputGroup = document.getElementById('rangeInputGroup');

            showRangeCheckbox.addEventListener('change', function() {
                rangeInputGroup.style.display = showRangeCheckbox.checked ? '' : 'none';
            });
            // });
        });

        $('#exampleModal1').on('shown.bs.modal', function() {
            const showRangeCheckbox = document.getElementById('showRangeCheckbox1');
            const rangeInputGroup = document.getElementById('rangeInputGroup1');

            showRangeCheckbox1.addEventListener('change', function() {
                rangeInputGroup1.style.display = showRangeCheckbox1.checked ? '' : 'none';
            });

            // Trigger the change event on page load if the checkbox is initially checked
            if (showRangeCheckbox1.checked) {
                rangeInputGroup1.style.display = '';
            }


            $('#reservationtime1').daterangepicker({
                timePicker: true,
                timePickerIncrement: 15,
                locale: {
                    format: 'MM/DD/YYYY h:mm A'
                }
            });
        });
    </script>
@endsection

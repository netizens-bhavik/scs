@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    @php

    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">

                    <div class="main-heading">
                        <h4 class="card-label" style="">NOTES MASTER
                        </h4>
                        <div class="text-end">

                            <button type="button" class="btn btn-primary" id="add_new_notes" title="Add">
                                <i class="bx bx-plus"></i> Add Notes
                            </button>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <table class="table table-striped table-bordered" id="notes_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Note's Description</th>
                                    <th>Reminder At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="manageNotesModalBox"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#notes_table').DataTable({
                order: [],
                pageLength: 25,
                info: false,
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 4]
                }],
                ajax: {
                    url: "{{ url('get_notes_list') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataSrc: function(res) {
                        return res.data;
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'reminder_at'
                    },
                    {
                        data: 'action'
                    }
                ],

            });
            bindAddNotesEvent();
            bindEditNotesEvent();
            bindDeleteNotesEvent();
        });

        function bindAddNotesEvent() {
            jQuery("#add_new_notes").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_notes') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function() {
                        swal({
                            title: "info",
                            text: "Please Wait, Your Request has been processed!",
                            icon: "info",
                            button: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        });
                    },
                    success: function(data) {
                        swal.close();
                        jQuery("#manageNotesModalBox").html(data);
                        jQuery("#manageNotesModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        function bindEditNotesEvent() {

            jQuery(document).on('click', '.edit_notes_data', function(e) {
                var notes_id = $(this).val();
                jQuery.ajax({
                    url: "{{ url('manage_notes') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": notes_id
                    },
                    beforeSend: function() {
                        swal({
                            title: "info",
                            text: "Please Wait, Your Request has been processed!",
                            icon: "info",
                            button: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        });
                    },
                    success: function(data) {
                        swal.close();
                        jQuery("#manageNotesModalBox").html(data);
                        jQuery("#manageNotesModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        function bindDeleteNotesEvent() {
            $(document).on('click', '.delete_notes_data', function() {
                var notes_id = $(this).val();
                var _token = $('input[name="_token"]').val();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Record!",
                    icon: "warning",
                    buttons: ['No', 'Yes'],
                    confirmButtonText: 'Yes',
                    denyButtonText: 'No',
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ url('/delete_notes') }}",
                            type: "POST",
                            data: {
                                id: notes_id,
                                _token: "{{ csrf_token() }}"
                            },
                            datatype: "json",
                            beforeSend: function() {
                                swal({
                                    title: "info",
                                    text: "Please Wait, Your Request has been processed!",
                                    icon: "info",
                                    button: false,
                                    closeOnClickOutside: false,
                                    closeOnEsc: false
                                });
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    swal.close();
                                    swal({
                                        title: "Success",
                                        text: data.message,
                                        icon: "success",
                                        button: "OK",
                                    }).then(function() {
                                        $('#notes_table').DataTable().ajax.reload();
                                    });

                                } else {
                                    swal.close();
                                    swal({
                                        title: "Error",
                                        text: data.message,
                                        icon: "error",
                                        button: "OK",
                                    });
                                }
                            }
                        });
                    } else {
                        swal.close();
                        swal("Cancelled", "Deletion Cancelled.", "error");
                    }
                });
            });
        }
    </script>
@endsection

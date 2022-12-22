@extends('backend.dashboard_layouts')

@section('styles')

    <style>
        /*add media query here for 320px*/
        @media (max-width: 320px) {
            .permission-listing-table th {
                padding: 10px 5px !important;
            }

            .permission-listing-table td {
                padding: 10px 5px !important;
            }
        }

    </style>
@endsection

@section('main_content')
    @php
        // echo '<pre>';
        // print_r($user_count);
        // echo '</pre>';
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">

                    <div class="main-heading">
                        <h4 class="card-label" style="">USER MASTER
                        </h4>
                        <div class="text-end">
                            @can('user_add')
                                <button type="button" class="btn btn-primary" id="add_new_user" title="Add">
                                    <i class="bx bx-plus"></i> Add User
                                </button>
                            @endcan
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    @can('user_view')
                        <div id="userDataTableContainer">
                            <table id="user_master_table"
                                class="table table-striped table-bordered mt-5 display responsive nowrap" style="width:100%">
                                <thead>
                                    <tr class="">
                                        <th>NO</th>
                                        <th>USER POSITION</th>
                                        <th>USER NAME</th>
                                        <th>ACTUAL NAME </th>
                                        <th>USER LOCATION</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="manageUserModalBox"></div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            initUserTable();
            bindAddUserEvent();
        });
    </script>

    <script>
        var initUserTable = function() {

            jQuery('#noUserDataTableContainer').hide();
            // DataTable
            $('#user_master_table').DataTable({
                order: [],
                pageLength: 25,
                info: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                responsive: true,
                //orderBy: 'ASC',
                columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                }],
                ajax: {
                    url: "{{ url('get_users_list') }}",
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
                        data: 'role_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'country_name'
                    },
                    {
                        data: 'action'
                    },
                ]
            });
        };


        $(document).ready(function() {

            $(document).on('click', '.delete_user', function() {
                var user_id = $(this).val();
                var _token = $('input[name="_token"]').val();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this user!",
                    icon: "warning",
                    buttons: ['No', 'Yes'],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ url('/delete_user') }}",
                            type: "POST",
                            data: {
                                user_id: user_id,
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
                                        jQuery('#user_master_table').DataTable()
                                            .ajax.reload();
                                    });

                                } else {
                                    swal.close();
                                    swal("Error", data.message, "error");
                                }
                            }
                        });
                    } else {
                        swal.close();
                        swal("Cancelled", "Deletion Cancelled.", "error");
                    }
                });
            });

            $(document).on('click', '.edit_user', function() {
                var user_id = $(this).val();
                $.ajax({
                    url: "{{ url('/manage_users') }}",
                    type: "POST",
                    data: {
                        user_id: user_id,
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
                            closeOnEsc: false,
                        });
                    },
                    success: function(data) {
                        swal.close();
                        jQuery("#manageUserModalBox").html(data.html);
                        jQuery("#user_master_modal").modal('show');
                    }
                });
            });
        });


        function bindAddUserEvent() {
            jQuery("#add_new_user").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_users') }}",
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
                        jQuery("#manageUserModalBox").html(data.html);
                        jQuery("#user_master_modal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            })
        }

        function bindEditUserEvent() {

        }
    </script>
@endsection

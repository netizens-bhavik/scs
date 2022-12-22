@extends('backend.dashboard_layouts')

@section('styles')
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
                        <h4 class="card-label" style="">SOFT CALL MASTER
                        </h4>
                        <div class="text-end">
                            @can('soft_call_add')
                                <button type="button" class="btn btn-primary" id="add_soft_call" title="Add">
                                    <i class="bx bx-plus"></i> Add Record
                                </button>
                            @endcan
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    @can('soft_call_view')
                        <div id="userDataTableContainer">
                            <table id="soft_call_master_table"
                                class="table table-striped table-bordered mt-5 display responsive nowrap" style="width:100%">
                                <thead>
                                    <tr class="">
                                        <th>No</th>
                                        <th>Country</th>
                                        <th>Company Name</th>
                                        <th>Phone Number</th>
                                        <th>Contact Person Name</th>
                                        <th>Mobile Number</th>
                                        <th>Email Address</th>
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
    <div id="manageSoftCallerModalBox"></div>

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

            //jQuery('#noUserDataTableContainer').hide();
            // DataTable
            $('#soft_call_master_table').DataTable({
                order: [],
                pageLength: 25,
                info: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                responsive: true,
                orderBy: 'ASC',
                columnDefs: [{
                    orderable: false,
                    targets: [0, 7]
                }],
                ajax: {
                    url: "{{ url('get_temp_lead_list') }}",
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
                        data: 'country_name'
                    },
                    {
                        data: 'company_name'
                    },
                    {
                        data: 'company_phone_no'
                    },
                    {
                        data: 'contact_person_name'
                    },
                    {
                        data: 'contact_person_phone'
                    },
                    {
                        data: 'contact_person_email'
                    },
                    {
                        data: 'action'
                    }

                ]
            });
        };


        $(document).ready(function() {

            $(document).on('click', '.delete_temp_lead_data', function() {
                var temp_lead_id = $(this).val();
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
                            url: "{{ url('/delete_soft_call_data') }}",
                            type: "POST",
                            data: {
                                temp_lead_id: temp_lead_id,
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
                                        jQuery('#soft_call_master_table')
                                            .DataTable()
                                            .ajax.reload();
                                    });

                                } else {
                                    swal.close();
                                    swal("Error", data.message, "error");
                                }
                            }
                        });
                    } else {
                        swal("Cancelled", "Deletion Cancelled.", "error");
                    }
                });
            });

            $(document).on('click', '.edit_temp_lead_data', function() {
                var temp_lead_id = $(this).val();
                $.ajax({
                    url: "{{ url('/manage_soft_call_data') }}",
                    type: "POST",
                    data: {
                        temp_lead_id: temp_lead_id,
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
                        swal.close();
                        jQuery("#manageSoftCallerModalBox").html(data.html);
                        jQuery("#soft_caller_master_modal").modal('show');
                    }
                });
            });
        });

        function bindAddUserEvent() {
            jQuery("#add_soft_call").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_soft_call_data') }}",
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
                        jQuery("#manageSoftCallerModalBox").html(data.html);
                        jQuery("#soft_caller_master_modal").modal('show');
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

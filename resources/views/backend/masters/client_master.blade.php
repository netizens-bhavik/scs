@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">

                    <div class="main-heading">
                        <h4 class="card-label" style="">CLIENT MASTER
                        </h4>
                        <div class="text-end">
                            @can('client_add')
                                <button type="button" class="btn btn-primary" id="add_new_client" title="Add">
                                    <i class="bx bx-plus"></i> Add Client
                                </button>
                            @endcan
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    @can('client_view')
                        <div id="clientDataTableContainer">
                            <table id="client_table" class="table table-striped table-bordered mt-5 display responsive nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="all">No</th>
                                        <th>Country Name</th>
                                        <th class="all">Company Name</th>
                                        <th>Industry Name</th>
                                        <th>Address</th>
                                        <th>Post Box No.</th>
                                        <th>City</th>
                                        <th>Phone No</th>
                                        <th>Email ID</th>
                                        <th>Website Name</th>
                                        <th>Client Status</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    @endcan
                    <div id="manageClientModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            initClientTable();
            bindAddClientEvent();
        });

        var initClientTable = function() {

            // DataTable
            $('#client_table').DataTable({
                responsive: true,
                order: [],
                pageLength: 25,
                info: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                searchDelay: 500,
                columnDefs: [{
                    orderable: false,
                    width: '20%',
                    targets: [11],
                }, {
                    orderable: false,
                    targets: [0],
                }],
                ajax: {
                    url: "{{ url('get_client_list') }}",
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
                        data: 'industry_name'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'post_box_no'
                    },
                    {
                        data: 'city_name'
                    },
                    {
                        data: 'phone_no'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'website_name'
                    },
                    {
                        data: 'active_status'
                    },
                    {
                        data: 'action'
                    },
                ],
                fnDrawCallback: function(index, rec) {
                    if (index.fnRecordsTotal().toString() == "0") {
                        return;
                    }
                    bindEditClientEvent();
                    bindDeleteClientEvent();
                }
            });
        };

        /**
         *  add client
         */
        function bindAddClientEvent() {
            jQuery("#add_new_client").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_client') }}",
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
                        jQuery("#manageClientModalBox").html(data);
                        jQuery("#manageClientModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        function bindEditClientEvent() {
            jQuery(".edit_client").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_client') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: $(e.target).closest('a').attr('data-id'),
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
                        jQuery("#manageClientModalBox").html(data);
                        jQuery("#manageClientModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        /**
         *  Delete client 
         * */
        function bindDeleteClientEvent() {
            jQuery('.delete_client').on('click', function(e) {
                swal({
                    title: "Delete Client?",
                    text: "Once deleted, you will not be able to recover this record!",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ['No', 'Yes'],
                    confirmButtonText: 'Yes',
                    denyButtonText: 'No',
                }).then((confirmed) => {
                    if (confirmed) {
                        jQuery.ajax({
                            url: "{{ url('delete_client') }}",
                            method: 'POST',
                            dataType: 'JSON',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id: jQuery(e.target).closest('a').attr('data-id'),
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
                                if (data.status == true) {
                                    swal.close();
                                    jQuery("#manageClientModal").modal('hide');
                                    if (!$.fn.dataTable.isDataTable('#client_table')) {
                                        initClientTable();
                                    } else {
                                        jQuery('#client_table').DataTable().ajax.reload();
                                    }

                                    swal({
                                        title: "Success",
                                        text: data.message,
                                        icon: "success",
                                        timer: 3000
                                    });

                                } else {
                                    swal.close();
                                    swal("Error", data.message, "error");
                                }
                            },
                            error: function(data) {
                                console.log(data);
                            }
                        });
                    } else {
                        swal("Cancelled", "Deletion Cancelled.", "error");
                    }
                });
            });
        }
    </script>
@endsection

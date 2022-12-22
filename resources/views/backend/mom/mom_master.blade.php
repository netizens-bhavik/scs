@extends('backend.dashboard_layouts')

@section('styles')
<style>
	table.dataTable tbody tr td:first-child{
		max-width:70px;
		min-width: 70px;
	}
	table.dataTable tbody tr td{
		min-width:150px;
		max-width: 350px;
		white-space: unset !important;
	}
</style>
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">



                    <div class="main-heading">
                        <h4 class="card-label" style="">MOM MASTER
                        </h4>
                        <div class="text-end">
                            @can('mom_add')
                                <button type="button" class="btn btn-primary" id="add_new_mom" title="Add">
                                    <i class="bx bx-plus"></i> Add MOM
                                </button>
                            @endcan
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div id="momDataTableContainer">
                        <table id="mom_table" class="table table-striped table-bordered mt-5 display responsive nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Company Name</th>
                                    <th>Meeting Date</th>
                                    <th>Contact Person</th>
                                    <th>Minutes of Meeting</th>
                                    <th>BDE Feedback</th>
                                    <th>MOM Type</th>
                                    <th>Mode of Meeting</th>
                                    <th class="all">Action</th>
                                    <th>Shared By</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="manageMomModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var mom_user_id = {{$mom_user_id}};

            if(mom_user_id != 0){
                jQuery.ajax({
                    url: "{{ url('manage_mom') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: mom_user_id,
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
                        jQuery("#manageMomModalBox").html(data);
                        jQuery("#manageMomModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }else{

            }
            initMomTable();
            bindAddMomEvent();
        });

        var initMomTable = function() {

            // DataTable
            $('#mom_table').DataTable({
                responsive: true,
                order: [],
                pageLength: 25,
                info: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('get_mom_list') }}",
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
                        data: 'company_name'
                    },
                    {
                        data: 'meeting_date'
                    },
                    {
                        data: 'contact_person'
                    },
                    {
                        data: 'minutes_of_meeting'
                    },
                    {
                        data: 'bde_feedback'
                    },
                    {
                        data: 'mom_type'
                    },
                    {
                        data: 'mode_of_meeting_name'
                    },
                    {
                        data: 'action'
                    },
                    {
                        data: 'shared_user_name'
                    },
                ],
                fnDrawCallback: function(index, rec) {
                    if (index.fnRecordsTotal().toString() == "0") {
                        return;
                    }
                    bindEditMomEvent();
                    bindDeleteMomEvent();
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0,1, 7,8,9]
                }]
            });
        };

        /** Add MOM
         */
        function bindAddMomEvent() {
            jQuery("#add_new_mom").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_mom') }}",
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
                        jQuery("#manageMomModalBox").html(data);
                        jQuery("#manageMomModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        function bindEditMomEvent() {
            $(".edit_mom").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_mom') }}",
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
                        jQuery("#manageMomModalBox").html(data);
                        jQuery("#manageMomModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        function bindDeleteMomEvent() {
            jQuery('.delete_mom').on('click', function(e) {
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Record!",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ['No', 'Yes'],
                    confirmButtonText: 'Yes',
                    denyButtonText: 'No',
                }).then((confirmed) => {
                    if (confirmed) {
                        jQuery.ajax({
                            url: "{{ url('delete_mom') }}",
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
                                    jQuery("#manageMomModal").modal('hide');
                                    if (!$.fn.dataTable.isDataTable('#mom_table')) {
                                        initMomTable();
                                    } else {
                                        jQuery('#mom_table').DataTable().ajax.reload();
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

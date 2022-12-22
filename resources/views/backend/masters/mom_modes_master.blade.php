@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">MODE OF MEETING MASTER
                        </h4>
                        <div class="text-end">
                            @can('mom_mode_add')
                                <a href="javascript:void(0);" class="btn btn-primary font-weight-bolder"
                                   id="add_new_mom_mode" title="Add">
                                    <i class="bx bx-plus"></i>Add MOM-MODE</a>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @can('mom_mode_view')
                        <div id="momModeDataTableContainer">
                            <table id="mom_mode_table" class="table table-striped table-bordered mt-5">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th class="all">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    @endcan
                    <div id="manageMOMModeModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            initMomModeTable();
            bindAddMomModeEvent();
        });

        var initMomModeTable = function () {

            // DataTable
            $('#mom_mode_table').DataTable({
                order: [],
                pageLength: 25,
                info: false,
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('get_mom_mode_list') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataSrc: function (res) {
                        return res.data;
                    }
                },
                columns: [{
                    data: 'id'
                },
                    {
                        data: 'mode_name'
                    },
                    {
                        data: 'action'
                    },
                ],
                fnDrawCallback: function (index, rec) {
                    if (index.fnRecordsTotal().toString() == "0") {
                        return;
                    }
                    bindEditMomModeEvent();
                    bindDeleteMomModeEvent();
                },
                columnDefs: [{
                    orderable: false,
                    targets: [-1, 0]
                }]
            });
        };


        /**
         *  add country
         */
        function bindAddMomModeEvent() {
            jQuery("#add_new_mom_mode").on('click', function (e) {
                jQuery.ajax({
                    url: "{{ url('manage_mom_mode') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        swal({
                            title: "info",
                            text: "Please Wait, Your Request has been processed!",
                            icon: "info",
                            button: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        });
                    },
                    success: function (data) {
                        swal.close();
                        jQuery("#manageMOMModeModalBox").html(data);
                        jQuery("#manageMomModeModal").modal('show');
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        }

        /**
         *  edit country
         */
        function bindEditMomModeEvent() {
            jQuery(".edit_mom_mode").on('click', function (e) {
                jQuery.ajax({
                    url: "{{ url('manage_mom_mode') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: $(e.target).closest('a').attr('data-id'),
                    },
                    beforeSend: function () {
                        swal({
                            title: "info",
                            text: "Please Wait, Your Request has been processed!",
                            icon: "info",
                            button: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        });
                    },
                    success: function (data) {
                        swal.close();
                        jQuery("#manageMOMModeModalBox").html(data);
                        jQuery("#manageMomModeModal").modal('show');
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        }

        /**
         *  delete country
         */
        function bindDeleteMomModeEvent() {
            jQuery('.delete_mom_mode').on('click', function (e) {
                swal({
                    title: "Delete Mode Of Meeting?",
                    text: "Once deleted, you will not be able to recover this record!",
                    icon: "warning",
                    buttons: ['No', 'Yes'],
                    confirmButtonText: 'Yes',
                    denyButtonText: 'No',
                    dangerMode: true,
                }).then((confirmed) => {
                    if (confirmed) {
                        jQuery.ajax({
                            url: "{{ url('delete_mom_mode') }}",
                            method: 'POST',
                            dataType: 'JSON',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id: jQuery(e.target).closest('a').attr('data-id'),
                            },
                            beforeSend: function () {
                                swal({
                                    title: "info",
                                    text: "Please Wait, Your Request has been processed!",
                                    icon: "info",
                                    button: false,
                                    closeOnClickOutside: false,
                                    closeOnEsc: false
                                });
                            },
                            success: function (data) {
                                if (data.status == true) {
                                    swal.close();

                                    jQuery("#manageMomModeModal").modal('hide');
                                    if (!$.fn.dataTable.isDataTable('#mom_mode_table')) {
                                        initCountryTable();
                                    } else {
                                        jQuery('#mom_mode_table').DataTable().ajax.reload();
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
                            error: function (data) {
                                // console.log(data);
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

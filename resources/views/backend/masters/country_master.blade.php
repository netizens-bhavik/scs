@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">COUNTRY MASTER
                        </h4>
                        <div class="text-end">
                            @can('country_add')
                                <button type="button" class="btn btn-primary" id="add_new_country" title="Add">
                                    <i class="bx bx-plus"></i> Add Country
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @can('country_view')
                        <div id="countryDataTableContainer">
                            <table id="country_table" class="table table-striped table-bordered mt-5 display responsive nowrap"
                                style="width:100%">
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
                    <div id="manageCountryModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            initCountryTable();
            bindAddCountryEvent();
        });

        var initCountryTable = function() {

            jQuery('#noCountryDataTableContainer').hide();
            // DataTable
            $('#country_table').DataTable({
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
                    targets: [0, 2],
                }],
                ajax: {
                    url: "{{ url('get_country_list') }}",
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
                        data: 'action'
                    },
                ],
                fnDrawCallback: function(index, rec) {
                    if (index.fnRecordsTotal().toString() == "0") {
                        return;
                    }
                    bindEditCountryEvent();
                    bindDeleteCountryEvent();
                }
            });
        };


        /**
         *  add country
         */
        function bindAddCountryEvent() {
            jQuery("#add_new_country").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_country') }}",
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
                        jQuery("#manageCountryModalBox").html(data);
                        jQuery("#manageCountryModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        /**
         *  edit country
         */
        function bindEditCountryEvent() {
            jQuery(".edit_country").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_country') }}",
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
                        jQuery("#manageCountryModalBox").html(data);
                        jQuery("#manageCountryModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        /**
         *  delete country
         */
        function bindDeleteCountryEvent() {
            jQuery('.delete_country').on('click', function(e) {
                swal({
                    title: "Delete Country?",
                    text: "Once deleted, you will not be able to recover this record!",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ['No', 'Yes'],
                    confirmButtonText: 'Yes',
                    denyButtonText: 'No',
                }).then((confirmed) => {
                    if (confirmed) {
                        jQuery.ajax({
                            url: "{{ url('delete_country') }}",
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
                                    $('#countryDataTableContainer').show();
                                    $('#noCountryDataTableContainer').hide();
                                    jQuery("#manageCountryModal").modal('hide');
                                    if (!$.fn.dataTable.isDataTable('#country_table')) {
                                        initCountryTable();
                                    } else {
                                        jQuery('#country_table').DataTable().ajax.reload();
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

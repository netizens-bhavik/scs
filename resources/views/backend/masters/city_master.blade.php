@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">CITY MASTER
                        </h4>
                        <div class="text-end">
                            @can('city_add')
                                <button type="button" class="btn btn-primary" id="add_new_city" title="Add">
                                    <i class="bx bx-plus"></i> Add City
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @can('city_view')
                        <div id="cityDataTableContainer">
                            <table id="city_table" class="table table-striped table-bordered mt-5 display responsive nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Country Name</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    @endcan
                    <div id="manageCityModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            initCityTable();
            bindAddCityEvent();
        });

        var initCityTable = function() {

            // DataTable
            $('#city_table').DataTable({
                responsive: true,
                order: [],
                pageLength: 25,
                info: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('get_city_list') }}",
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
                        data: 'city_name'
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
                    bindEditCityEvent();
                    bindDeleteCityEvent();
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0, 3]
                }]
            });
        };

        /**
         *  add city
         */
        function bindAddCityEvent() {
            jQuery("#add_new_city").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_city') }}",
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
                        jQuery("#manageCityModalBox").html(data);
                        jQuery("#manageCityModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        /**
         *  edit city
         */
        function bindEditCityEvent() {
            jQuery(".edit_city").on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('manage_city') }}",
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
                        jQuery("#manageCityModalBox").html(data);
                        jQuery("#manageCityModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        /**
         *  delete city
         */
        function bindDeleteCityEvent() {
            jQuery('.delete_city').on('click', function(e) {
                swal({
                    title: "Delete City?",
                    text: "Once deleted, you will not be able to recover this record!",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ['No', 'Yes'],
                    confirmButtonText: 'Yes',
                    denyButtonText: 'No',
                }).then((confirmed) => {
                    if (confirmed) {
                        jQuery.ajax({
                            url: "{{ url('delete_city') }}",
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
                                    $('#cityDataTableContainer').show();
                                    $('#noCityDataTableContainer').hide();
                                    jQuery("#manageCityModal").modal('hide');
                                    if (!$.fn.dataTable.isDataTable('#city_table')) {
                                        initCityTable();
                                    } else {
                                        jQuery('#city_table').DataTable().ajax.reload();
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

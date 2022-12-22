@php
    //    echo "<pre>";
    //    print_r($filter_data);
    //    echo "</pre>";
@endphp


    <!-- data-table -->
<div class="col-lg-12 col-md-12 mb-4">
    <div class="card">
        <h5 class="card-header">Manage FollowUps</h5>
        <div class="text-nowrap tab p-3">
            <table class="table table table-striped table-bordered" id="followup_datatable">
                <thead class="table-bordered">
                <tr>
                    <th>No</th>
                    <th>Date-Time</th>
                    <th>User Name</th>
                    <th class="all">Company Name</th>
                    <th>Contact Person</th>
                    <th>Mode Of Meeting</th>
                    <th class="all">ACTION</th>
                    <th>SHARED BY</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">


                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ data-table -->

<script>
    $(document).ready(function () {
        $('#followup_datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            info: false,
            searching: false,
            responsive: true,

            columnDefs: [{
                orderable: false,
                targets: [0, 1, 2, 3, 4, 5,6,7]
            }],
            lengthChange: false,
            ajax: {
                url: "{{ url('get_mom_followups_data') }}",
                type: 'POST',
                data: function (d) {
                    d.mom_followups_start_date = "{{ $filter_data['start_date'] }}";
                    d.mom_followups_end_date = "{{ $filter_data['end_date'] }}";
                    d.mom_followups_followup_type = "{{ $filter_data['followup_type'] }}";
                    d.mom_followups_country_id = "{{ $filter_data['country_id'] }}";
                    d.mom_followups_user_id = "{{ $filter_data['user_id'] }}";
                    d.mom_followups_hierarchy_users = "{{ $filter_data['hierarchy_users'] }}";
                }
            },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'followup_datetime',
                    name: 'followup_datetime'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    data: 'contact_person',
                    name: 'contact_person'
                },
                {
                    data: 'mode_of_meeting_name',
                    name: 'mode_of_meeting_name'
                },
                {
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'shared_by_name',
                    name: 'shared_by_name'
                }
            ],
            order: [],
            fnDrawCallback: function(index, rec) {
                bindTableEvents();
            }

        });

    });

    function bindTableEvents()
    {

        $(".edit_mom").on('click',function (e) {

            jQuery.ajax({
                url: "{{ url('manage_mom') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: $(e.target).closest('a').attr('data-id'),
                    dashboard_country_id: "{{ $filter_data['country_id'] ?? '' }}",
                    dashboard_user_id: "{{ $filter_data['user_id'] ?? '' }}",
                    dashboard_flag: true,
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
                    $("#manageMomModalBox").html('');
                    $("#manageMomModalBox").html(data);
                    $("#manageMomModal").modal('show');
                    //get_followup_count({{ $filter_data['country_id'] }}, {{ $filter_data['user_id'] }});
                    //get_chart_data({{ $filter_data['country_id'] }}, {{ $filter_data['user_id'] }});
                },
                error: function (data) {
                    console.log(data);
                }
            });

        });

        $(".delete_mom").on('click', function(e) {
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
                            if (data.status == true) {
                                swal.close();
                                $("#manageMomModal").modal('hide');
                                if (!$.fn.dataTable.isDataTable('#followup_datatable')) {
                                    initMomTable();
                                } else {
                                    $('#followup_datatable').DataTable().ajax.reload();
                                }
                                get_followup_count({{ $filter_data['country_id'] }}, {{ $filter_data['user_id'] }});
                                get_chart_data({{ $filter_data['country_id'] }}, {{ $filter_data['user_id'] }});

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
                            console.log(data);
                        }
                    });
                } else {
                    swal("Cancelled", "Deletion Cancelled.", "error");
                }
            });
        });

        $(".btn-close").on('click',function(e){
            $("#manageMomModal").modal('hide');
            $("#manageMomModalBox").html('');
        });

        $(".company_modal").on('click',function (e){
            var id = $(this).attr('data-id');
            jQuery.ajax({
                url: "{{ url('company_modal') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
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
                    if(data.status == true) {
                        $("#companyModalBox").html('');
                        $("#companyModalBox").html(data.html);
                        $("#companyModal").modal('show');
                    } else {
                        swal("Error", data.message, "error");
                    }

                },
                error: function (data) {
                    console.log(data);
                }
            });
        });

        $(".manage_mom").on('click',function (e) {
            var id = $(this).attr('data-id');
            var url = "{{ url('moms') }}" + "/" + id;
            window.location.href = url;
        });

    }


</script>

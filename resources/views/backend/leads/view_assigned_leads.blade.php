@extends('backend.dashboard_layouts')

@section('styles')
    <style>

    </style>
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="header-title mb-0" style="display: inline-block">NEW LEADS</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div id="assignedleadDataTableContainer">
                        <table id="assigned_leads_table"
                            class="table table-striped table-bordered mt-5 display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="all">No</th>
                                    <th>Company Name</th>
                                    <th>Industry Name</th>
                                    <th>Country Name</th>
                                    <th>Address</th>
                                    <th>Spoken With</th>
                                    <th>Basic Requirement</th>
                                    <th>Assigned To.</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="manageAssignedLeadModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            initAssignedLeadsTable();
        });

        var initAssignedLeadsTable = function() {

            // DataTable
            $('#assigned_leads_table').DataTable({
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
                    targets: [0, 5, 7, 8],
                }],
                ajax: {
                    url: "{{ url('get_assigned_leads_list') }}",
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
                        data: 'industry_name'
                    },
                    {
                        data: 'country_name'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'spoken_with'
                    },
                    {
                        data: 'basic_requirement'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'action'
                    },
                ],
                fnDrawCallback: function(index, rec) {
                    if (index.fnRecordsTotal().toString() == "0") {
                        return;
                    }
                    bindManageClientEvent();
                    bindManageMOMEvent();
                }
            });
        };

        function bindManageClientEvent() {
            $('.add_client').on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('add_client_to_leads') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        leadId: $(e.target).closest('a').attr('data-id'),
                        clientId: $(e.target).closest('a').attr('data-client')
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
                        jQuery("#manageAssignedLeadModalBox").html(data);
                        jQuery("#manageClientModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        function bindManageMOMEvent() {
            $('.add_mom').on('click', function(e) {
                jQuery.ajax({
                    url: "{{ url('add_mom_to_leads') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        leadId: $(e.target).closest('a').attr('data-id'),
                        clientId: $(e.target).closest('a').attr('data-client')
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
                        jQuery("#manageAssignedLeadModalBox").html(data);
                        jQuery("#manageMomModal").modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }
    </script>
@endsection

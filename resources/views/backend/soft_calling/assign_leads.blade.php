@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">ASSIGN LEADS
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="assign_leads_form" method="POST" action="javascript:void(0)">
                                @csrf
                                <table id="gm_assign_leads" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            {{-- <th>Client Name</th> --}}
                                            <th>Company Name</th>
                                            <th>Country Name</th>
                                            <th>Phone Number</th>
                                            <th>Spoken With</th>
                                            <th>Cell No.</th>
                                            <th>Basic Requirement</th>
                                            <th class="all">Assign To.</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                <div class="col-md-12 text-center mt-3">
                                    <button type="submit" class="btn btn-primary" id="assign_leads_btn" title="Assign Leads">Assign
                                        Leads</button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            bindAssignLeadsEvent();

            $('#assign_leads_form').submit(function(e) {
                e.preventDefault();
                // var assign_user_id = [];
                // var assign_lead_id = [];
                var assign_to = [];

                $('.assign_to_drop').each(function(index, element) {
                    if ($(this).val() != '') {
                        assign_to.push({
                            'user_id': $(this).val(),
                            'lead_id': $(this).attr('data-lead_id')
                        });
                    }
                });

                if (assign_to.length > 0) {
                    $.ajax({
                        url: "{{ route('assign_leads') }}",
                        type: "POST",
                        data: {
                            assign_to: assign_to,
                            _token: "{{ csrf_token() }}"
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
                        success: function(response) {
                            if (response.status == true) {
                                swal.close();
                                swal({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success",
                                    button: "OK",
                                }).then(function() {
                                    $('#gm_assign_leads').DataTable().ajax.reload();
                                });

                            } else {
                                swal.close();
                                swal("Error", data.message, "error");
                            }
                        }
                    });
                } else {
                    swal("Error", 'Please Select Minimum 1 Record to Assign', "error");
                }
            });

        });

        function bindAssignLeadsEvent() {
            $('#gm_assign_leads').DataTable({
                order: [],
				bPaginate: false,
                pageLength: 99999,
                info: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                responsive: true,
                orderBy: 'ASC',
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5, 6, 7]
                }],
                ajax: {
                    url: "{{ route('get_upcoming_assign_leads') }}",
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
                    // {
                    //     data: 'client_name'
                    // },
                    {
                        data: 'company_name'
                    },
                    {
                        data: 'country_name'
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'spoken_with'
                    },
                    {
                        data: 'contact_no'
                    },
                    {
                        data: 'basic_requirement'
                    },
                    {
                        data: 'action'
                    }
                ]
            });
        }
    </script>
@endsection

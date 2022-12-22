@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">JOB STATUS
                        </h4>
                        <a href="{{ url('/clinet_jobs') }}"><span class="h5 text-primary">GO BACK</span></a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- @canany([]) --}}
                    <div id="jobDataTableContainer">
                        <table id="job_table" class="table table-striped table-bordered mt-5 display responsive nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Job Date</th>
                                    <th>Job Category</th>
                                    <th>Quantity</th>
                                    <th>Description</th>
                                    <th class="all">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary" id="job_status_btn" title="Change Status">Change Status</button>
                        </div>
                    </div>
                    {{-- @endcanany --}}
                    <div id="manageJobModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            initJobTable();
            bindChangeStatusEvent();
        });

        var initJobTable = function() {

            // DataTable
            $('#job_table').DataTable({
                responsive: true,
                order: [],
                pageLength: 25,
                info: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('get_job_list') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        momId: "{{ $momId }}",
                    },
                    dataSrc: function(res) {
                        return res.data;
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'j_date'
                    },
                    {
                        data: 'job_category'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'job_description'
                    },
                    {
                        data: 'action'
                    },
                ],
                fnDrawCallback: function(index, rec) {
                    if (index.fnRecordsTotal().toString() == "0") {
                        return;
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 5]
                }]
            });
        };

        function bindChangeStatusEvent() {
            $(document).on('click', '#job_status_btn', function() {
                var job_status = [];

                $('.job_status_drop').each(function(index, element) {
                    if ($(this).val() != '') {
                        job_status.push({
                            'jobStatus': $(this).val(),
                            'jobId': $(this).attr('data-id')
                        });
                    }
                });

                if (job_status.length > 0) {
                    $.ajax({
                        url: "{{ route('change_job_status') }}",
                        type: "POST",
                        data: {
                            job_status: job_status,
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
                                    $('#job_table').DataTable().ajax.reload();
                                });

                            } else {
                                swal.close();
                                swal("Error", data.message, "error");
                            }
                        }
                    });
                } else {
                    swal("Error", 'Select status', "error");
                }
            });
        }
    </script>
@endsection

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
                    </div>

                </div>
                <div class="card-body">
                    {{-- @canany(['client_view', 'client_edit', 'client_delete']) --}}
                        <div id="clientJobDataTableContainer">
                            <table id="client_job_table" class="table table-striped table-bordered mt-5 display responsive nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Company Name</th>
                                        <th>MOM Date</th>
                                        <th>Total Job Categories</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    {{-- @endcanany --}}
                    <div id="manageClientJobModalBox"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            initClientJobTable();
        });

        var initClientJobTable = function() {

            // DataTable
            $('#client_job_table').DataTable({
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
                    targets: [0,3,4],
                }],
                ajax: {
                    url: "{{ url('get_client_job_list') }}",
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
                        data: 'total_jobs'
                    },
                    {
                        data: 'action'
                    },
                ],
                fnDrawCallback: function(index, rec) {
                    if (index.fnRecordsTotal().toString() == "0") {
                        return;
                    }
                }
            });
        };
    </script>
@endsection

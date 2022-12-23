@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">CLIENT STATUS REPORT
                        </h4>
                        <div class="text-end">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="message">
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close" title="Close"></button>
                            </div>
                        @endif
                    </div>
                    <div id="clientStatusReportFilters">
                        <form action="{{ route('client_status_report_export') }}" method="POST"
                              id="client_status_report_export_form">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-sm-6 col-xl-3 col-xxl-2">
                                            <div class="form-group">
                                                <label for="client_status_report_country">Country</label>
                                                <select class="form-control form-select"
                                                        id="client_status_report_country"
                                                        name="client_status_report_country">
                                                    <option value="">Select Country</option>
                                                    @foreach ($all_countries as $country)
                                                        <option
                                                            value="{{ $country['id'] }}">{{ $country['country_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xl-3 col-xxl-2">
                                            <div class="form-group">
                                                <label for="client_status_report_city">City</label>
                                                <select class="form-control form-select" id="client_status_report_city"
                                                        name="client_status_report_city">
                                                    <option value="">Select City</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xl-3 col-xxl-2">
                                            <div class="form-group">
                                                <label for="client_status_report_industry">Industry</label>
                                                <select class="form-control form-select"
                                                        id="client_status_report_industry"
                                                        name="client_status_report_industry">
                                                    <option value="">Select Industry</option>
                                                    @foreach ($all_industries as $industry)
                                                        <option
                                                            value="{{ $industry['id'] }}">{{ $industry['industry_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xl-3 col-xxl-2">
                                            <div class="form-group">
                                                <label for="client_status_report_user">User</label>
                                                <select class="form-control form-select" id="client_status_report_user"
                                                        name="client_status_report_user">
                                                    <option value="">Select User</option>
                                                    @foreach ($user_list as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xl-3 col-xxl-2">
                                            <div class="form-group">
                                                <label for="client_status_report_activity">Duration/Activity</label>
                                                <select class="form-control form-select"
                                                        id="client_status_report_activity"
                                                        name="client_status_report_activity">
                                                    <option value="">Select Activity</option>
                                                    <option value="30p">Select Last 30 Days Active</option>
                                                    <option value="30n">Select Last 30 Days Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xl-3 col-xxl-2 align-self-end">
                                            <div class="form-group mb-1">
                                                <div class="d-flex">
                                                    <div class="app-btn me-2">
                                                        <button type="button" class="btn btn-primary btn-md"
                                                                id="client_status_report_filter_btn" title="Filter">
                                                            <i class='bx bx-filter-alt'></i>
                                                            Filter
                                                        </button>
                                                    </div>

                                                    <div class="app-btn me-2">
                                                        <button type="button" class="btn btn-primary btn-md"
                                                                id="client_status_report_filter_reset_btn"
                                                                title="Reset">
                                                            <i class='bx bx-reset'></i>
                                                            Reset
                                                        </button>
                                                    </div>
                                                    <div class="app-btn">
                                                        <button type="submit" class="btn btn-primary btn-md"
                                                                id="client_status_report_export_btn" title="Export"><i
                                                                class='bx bxs-file-export'></i>
                                                            Export
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="reportDataTableContainer" class="my-3">
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function () {

            setTimeout(function () {
                $("div.alert").remove();
            }, 3000);

            getReportDataTable();

            $('#client_status_report_meeting_from_date').change(function (e) {
                e.preventDefault();
                $('#client_status_report_meeting_to_date').val('');
                var startDate = $('#client_status_report_meeting_from_date').val();
                $('#client_status_report_meeting_to_date').attr('min', startDate);
            });

            // Call Status Report Filter
            $('#client_status_report_filter_btn').click(function () {
                getReportDataTable();
            });

            $('#client_status_report_filter_reset_btn').click(function () {
                location.reload();
            });

            $('#client_status_report_country').change(function () {
                var country_id = $(this).val();
                $.ajax({
                    url: "{{ route('client_status_report_country_change') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $('#client_status_report_city').html('');
                        $('#client_status_report_city').html(data.html);
                    }
                });
            });

            $('#client_status_report_export_btn').click(function (e) {
                e.preventDefault();
                var country_id = $('#client_status_report_country').val();
                var city_id = $('#client_status_report_city').val();
                var user_id = $('#client_status_report_user').val();
                var industry_id = $('#client_status_report_industry').val();
                var activity_status = $('#client_status_report_activity').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('client_status_report_export_check') }}",
                    data: {
                        country_id: country_id,
                        city_id: city_id,
                        user_id: user_id,
                        industry_id: industry_id,
                        activity_status: activity_status,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status == true) {
                            swal({
                                icon: 'success',
                                title: 'Export Successful!',
                                text: response.message,
                                button: "Download",
                                closeOnClickOutside: false,
                                closeOnEsc: false
                            }).then(function () {
                                $('#client_status_report_export_form').submit();
                            });
                        } else {
                            swal({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    }
                });
            });


        });


        function getReportDataTable() {
            var country_id = $('#client_status_report_country').val();
            var city_id = $('#client_status_report_city').val();
            var user_id = $('#client_status_report_user').val();
            var industry_id = $('#client_status_report_industry').val();
            var activity_status = $('#client_status_report_activity').val();

            var data = {
                country_id: country_id,
                city_id: city_id,
                user_id: user_id,
                industry_id: industry_id,
                activity_status: activity_status,
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: "{{ route('client_status_report_table_view') }}",
                type: "POST",
                data: data,
                success: function (data) {
                    $('#reportDataTableContainer').html(data.html);
                }
            });
        }
    </script>
@endsection

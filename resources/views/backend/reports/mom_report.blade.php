@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">MOM REPORT
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
                    <div id="momReportFilters">
                        <form action="{{ route('mom_report_export') }}" method="POST" id="mom_report_export_form">
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="mom_report_meeting_from_date">From Date (Meeting)</label>
                                        <input type="date" class="form-control" id="mom_report_meeting_from_date"
                                            name="mom_report_meeting_from_date" placeholder="From Date" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="mom_report_meeting_to_date">To Date (Meeting)</label>
                                        <input type="date" class="form-control" id="mom_report_meeting_to_date"
                                            name="mom_report_meeting_to_date" placeholder="To Date" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="mom_report_country">Country</label>
                                        <select class="form-control form-select" id="mom_report_country"
                                                name="mom_report_country">
                                            <option value="">Select Country</option>
                                            @foreach ($all_countries as $country)
                                                <option value="{{ $country['id'] }}">{{ $country['country_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row g-3">

                                {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="mom_report_follow_up_from_date">From Date (Follow-Up)</label>
                                    <input type="date" class="form-control" id="mom_report_follow_up_from_date"
                                        name="mom_report_follow_up_from_date" placeholder="From Date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="mom_report_follow_up_to_date">To Date (Follow-Up)</label>
                                    <input type="date" class="form-control" id="mom_report_follow_up_to_date"
                                        name="mom_report_follow_up_to_date" placeholder="To Date" autocomplete="off">
                                </div>
                            </div> --}}

                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="mom_report_company_name">Company Name</label>
                                        <select class="form-control form-select" id="mom_report_company_name"
                                            name="mom_report_company_name">
                                            <option value="">Select Company</option>
                                            @foreach ($company_data as $company)
                                                <option value="{{ $company->id }}">
                                                    {{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="mom_report_user">Added By</label>
                                        <select class="form-control form-select" id="mom_report_user"
                                            name="mom_report_user">
                                            <option value="">Select User</option>
                                            @foreach ($mom_added_by_users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4 col-xl-3 align-self-end">
                                    <div class="form-group mb-2">

                                        <div class="d-flex">
                                            <div class="app-btn me-2">
                                                <button type="button" class="btn btn-primary btn-md"
                                                    id="mom_report_filter_btn" title="Filter">
                                                    <i class='bx bx-filter-alt'></i>
                                                    Filter
                                                </button>
                                            </div>

                                            <div class="app-btn me-2">

                                                <button type="button" class="btn btn-primary btn-md"
                                                    id="mom_report_filter_reset_btn" title="Reset">
                                                    <i class='bx bx-reset'></i>
                                                    Reset
                                                </button>
                                            </div>
                                            <div class="app-btn">
                                                <button type="submit" class="btn btn-primary btn-md"
                                                    id="mom_report_export_btn" title="Export"> <i class='bx bxs-file-export'></i>
                                                    Export
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="momReportDataTableContainer" class="my-3">
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $("div.alert").remove();
            }, 3000);
            get_datatable();
            country_change();
            company_change();
            reset_filter();
            filter_data();
            export_data();
            date_validation();


            $('#mom_report_export_btn').click(function(e) {
                e.preventDefault();
                var meetingFromDate = $('#mom_report_meeting_from_date').val();
                var meetingToDate = $('#mom_report_meeting_to_date').val();
                var companyName = $('#mom_report_company_name').val();
                var country = $('#mom_report_country').val();
                var user = $('#mom_report_user').val();

                if (meetingFromDate != '') {
                    if (meetingToDate == '') {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please Select To Date!',
                        });
                        return false;
                    }
                }
                if (meetingFromDate != '' && meetingToDate != '') {
                    if (meetingFromDate > meetingToDate) {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'From Date Should Be Less Than To Date!',
                        });
                        return false;
                    }
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('mom_report_export_check') }}",
                    data: {
                        meetingFromDate: meetingFromDate,
                        meetingToDate: meetingToDate,
                        companyName: companyName,
                        country: country,
                        user: user,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == true) {
                            swal({
                                icon: 'success',
                                title: 'Export Successful!',
                                text: response.message,
                                button: "Download",
                                closeOnClickOutside: false,
                                closeOnEsc: false
                            }).then(function() {
                                $('#mom_report_export_form').submit();
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

        function date_validation() {
            $('#mom_report_meeting_from_date').change(function(e) {
                e.preventDefault();
                $('#mom_report_meeting_to_date').val('');
                var startDate = $('#mom_report_meeting_from_date').val();
                $('#mom_report_meeting_to_date').attr('min', startDate);
            });

            $('#mom_report_follow_up_from_date').change(function(e) {
                e.preventDefault();
                $('#mom_report_follow_up_to_date').val('');
                var startDate = $('#mom_report_follow_up_from_date').val();
                $('#mom_report_follow_up_to_date').attr('min', startDate);
            });
        }

        function country_change() {

            if ($('#mom_report_country').val() != '') {
                var country_id = $('#mom_report_country').val();

                $.ajax({
                    url: "{{ route('get_company_by_country') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#mom_report_company_name').html(data.html);
                    }
                });

                // if (country_id != '') {

                // } else {
                //     $('#mom_report_company_name').html('<option value="">No Company Records</option>');
                // }
            }


            $('#mom_report_country').change(function(e) {
                e.preventDefault();
                var country_id = $(this).val();

                $.ajax({
                    url: "{{ route('get_company_by_country') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#mom_report_company_name').html(data.html);
                    }
                });

                // if (country_id != '') {

                // } else {
                //     $('#mom_report_company_name').html('<option value="">No Company Records</option>');
                // }
            });
        }

        function company_change() {
            $('#mom_report_company_name').change(function(e) {
                e.preventDefault();
                var company_id = $(this).val();

                $.ajax({
                    url: "{{ route('get_company_users') }}",
                    type: "POST",
                    data: {
                        company_id: company_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#mom_report_user').html(data.html);
                    }
                });

                // if (company_id != '') {

                // } else {
                //     $('#mom_report_user').html('<option value="">No User Records</option>');
                // }
            });
        }

        function reset_filter() {
            $('#mom_report_filter_reset_btn').click(function(e) {
                e.preventDefault();
                location.reload();
            });
        }

        function filter_data() {
            $('#mom_report_filter_btn').click(function(e) {
                e.preventDefault();
                get_datatable();
            });
        }

        function get_datatable() {
            var meetingFromDate = $('#mom_report_meeting_from_date').val();
            var meetingToDate = $('#mom_report_meeting_to_date').val();

            if (meetingFromDate != '') {
                if (meetingToDate == '') {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please Select To Date!',
                    });
                    return false;
                }
            }
            if (meetingFromDate != '' && meetingToDate != '') {
                if (meetingFromDate > meetingToDate) {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'From Date Should Be Less Than To Date!',
                    });
                    return false;
                }
            }

            var companyName = $('#mom_report_company_name').val();
            var country = $('#mom_report_country').val();
            var user = $('#mom_report_user').val();

            var data = {
                meetingFromDate: meetingFromDate,
                meetingToDate: meetingToDate,
                companyName: companyName,
                country: country,
                user: user,
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: "{{ route('mom_report_data') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    $('#momReportDataTableContainer').html('');
                    $('#momReportDataTableContainer').html(response.html);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function export_data() {

        }
    </script>
@endsection

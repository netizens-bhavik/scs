@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">CALL STATUS (USER WISE) REPORT
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
                    <div id="callStatusUWReportFilters">
                        <form action="{{ route('call_status_uw_report_export') }}" method="POST"
                            id="call_status_uw_report_export_form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8 col-xxl-6">
                                    <div class="row">
                                        <div class="col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="call_status_uw_report_country">Country</label>
                                                <select class="form-control form-select" id="call_status_uw_report_country"
                                                    name="call_status_uw_report_country">
                                                    <option value="">Select Country</option>
                                                    @foreach ($all_countries as $country)
                                                        <option value="{{ $country['id'] }}">{{ $country['country_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="call_status_uw_report_meeting_from_date">From Date</label>
                                                <input type="date" class="form-control"
                                                    id="call_status_uw_report_meeting_from_date"
                                                    name="call_status_uw_report_meeting_from_date" placeholder="From Date"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="call_status_uw_report_meeting_to_date">To Date</label>
                                                <input type="date" class="form-control"
                                                    id="call_status_uw_report_meeting_to_date"
                                                    name="call_status_uw_report_meeting_to_date" placeholder="To Date"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xxl-6">
                                    <div class="row my-4">
                                        <div class="col-md-12 col-xl-12 align-self-end">
                                            <div class="form-group mb-2">
                                                <div class="d-flex">
                                                    <div class="app-btn me-2">
                                                        <button type="button" class="btn btn-primary btn-md"
                                                            id="call_status_uw_report_filter_btn" title="Filter">
                                                            <i class='bx bx-filter-alt'></i>
                                                            Filter
                                                        </button>
                                                    </div>

                                                    <div class="app-btn me-2">
                                                        <button type="button" class="btn btn-primary btn-md"
                                                            id="call_status_uw_report_filter_reset_btn" title="Reset">
                                                            <i class='bx bx-reset'></i>
                                                            Reset
                                                        </button>
                                                    </div>
                                                    <div class="app-btn">
                                                        <button type="submit" class="btn btn-primary btn-md"
                                                            id="call_status_uw_report_export_btn" title="Export"> <i
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
        $(document).ready(function() {

            setTimeout(function() {
                $("div.alert").remove();
            }, 3000);

            $('#call_status_uw_report_meeting_from_date').change(function(e) {
                e.preventDefault();
                $('#call_status_uw_report_meeting_to_date').val('');
                var startDate = $('#call_status_uw_report_meeting_from_date').val();
                $('#call_status_uw_report_meeting_to_date').attr('min', startDate);
            });

            // Call Status Report Filter
            $('#call_status_uw_report_filter_btn').click(function() {
                var country_id = $('#call_status_uw_report_country').val();
                var from_date = $('#call_status_uw_report_meeting_from_date').val();
                var to_date = $('#call_status_uw_report_meeting_to_date').val();

                if (country_id == '' && from_date == '' && to_date == '') {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select at least one filter option!',
                    });
                    return false;
                }

                if (from_date != '') {
                    if (to_date == '') {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please select To Date!',
                        });
                        return false;
                    }
                }
                $.ajax({
                    url: "{{ route('call_status_uw_report_table_view') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        from_date: from_date,
                        to_date: to_date,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#reportDataTableContainer').html('');
                        $('#reportDataTableContainer').html(data.html);
                    }
                });
            });

            // Call Status Report Filter Reset
            $('#call_status_uw_report_filter_reset_btn').click(function() {
                location.reload();
            });

            $('#call_status_uw_report_export_btn').click(function(e) {
                e.preventDefault();
                var country_id = $('#call_status_uw_report_country').val();
                var from_date = $('#call_status_uw_report_meeting_from_date').val();
                var to_date = $('#call_status_uw_report_meeting_to_date').val();

                if (from_date != '') {
                    if (to_date == '') {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please select To Date!',
                        });
                        return false;
                    }
                }
                if (from_date != '' && to_date != '') {
                    if (from_date > to_date) {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'From Date should be less than To Date!',
                        });
                        return false;
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('call_status_uw_report_export_check') }}",
                    data: {
                        country_id: country_id,
                        from_date: from_date,
                        to_date: to_date,
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
                                $('#call_status_uw_report_export_form').submit();
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
    </script>
@endsection

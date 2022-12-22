@extends('backend.dashboard_layouts')

@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/css/data-table.css') }}"/>
    <style>
        .dataTables_wrapper {
            min-height: unset;
        }

        @media (max-width: 767px) {
            .app-chart .apexcharts-canvas {
                margin: 0 auto;
            }
        }
    </style>
@endsection

@section('main_content')

    @hasanyrole('administrator|director|general manager|bde|bdm')
    <div class="flex-grow-1 container-p-y">
        @hasanyrole('administrator|director|general manager')
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-lg-9 col-xl-9 col-xxl-8">
                            <div class="card-body">

                                <form id="dashboard-filter">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label">Branch</label>
                                                <select class="form-control form-select" name="country_id"
                                                        id="dashboard_country_id">
                                                    <option value="">Select Branch</option>
                                                    @foreach($all_country as $branch)
                                                        <option
                                                            value="{{ $branch['id'] }}">{{ $branch['country_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label">User</label>
                                                <select class="form-control form-select" name="user_id"
                                                        id="dashboard_user_id">
                                                    <option value="">Select User</option>
                                                    @foreach($all_users as $user)
                                                        <option value="{{ $user['id'] }}">{{ucwords($user['role_name'])}}-{{ucwords($user['country_name'])}}-{{ ucwords($user['name']) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label"> &nbsp; </label>
                                                <div>
                                                    <button type="button" class="btn btn-primary fltr-btn"
                                                            id="fltr-btn" title="Filter">Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endhasanyrole
        <div class="followups">
            <div class="row" id="followup_count">
                <div class="col-lg-12 col-md-12 order-1">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-3 col-6 mb-4">
                            <a href="#">
                                <div class="card  follow_up-tab" id="follow_up-tab" data-followup_type="1">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h5 class="mb-0">Today's FollowUps</h5>
                                        </div>
                                        <h3 class="count mb-0" id="today-followups"><img
                                                src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader"
                                                width="22px" height="22px"></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 mb-4">
                            <a href="#">
                                <div class="card follow_up-tab" id="follow_up-tab" data-followup_type="2">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h5 class="mb-0">Tomorrow's FollowUps</h5>
                                        </div>
                                        <h3 class="count mb-0" id="tomorrow-followups"><img
                                                src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader"
                                                width="22px" height="22px"></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 mb-4">
                            <a href="#">
                                <div class="card follow_up-tab" id="follow_up-tab" data-followup_type="3">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h5 class="mb-0">Pending FollowUps</h5>
                                        </div>
                                        <h3 class="count mb-0" id="pending-followups"><img
                                                src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader"
                                                width="22px" height="22px"></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 mb-4">
                            <a href="#">
                                <div class="card follow_up-tab" id="follow_up-tab" data-followup_type="4">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h5 class="mb-0">Future FollowUps</h5>
                                        </div>
                                        <h3 class="count mb-0" id="future-followups"><img
                                                src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader"
                                                width="22px" height="22px"></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="datatable-space">

        </div>
        <div id="chart-space">

        </div>
    </div>

    <div id="manageMomModalBox"></div>

    <div id="companyModalBox"></div>
    @else
        @include('backend.dashboard.coming_soon')
        @endhasanyrole
        @endsection

        @section('scripts')
            <script src="{{ asset('public/assets/js/data-table.js') }}"></script>
            <script>
                $(document).ready(function () {
                    var followup_type;
                    var country_id;
                    var user_id;

                    get_followup_count(country_id, user_id);
                    get_chart_data(country_id, user_id);

                    $(document).on('click', '#fltr-btn', function () {
                        country_id = $('#dashboard_country_id').val();
                        user_id = $('#dashboard_user_id').val();

                        get_followup_count(country_id, user_id);
                        get_chart_data(country_id, user_id);

                        $.ajax({
                            url: "{{url('mom_stats')}}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                country_id: country_id,
                                user_id: user_id,
                                followup_type: followup_type,
                            },
                            beforeSend: function () {
                                $('#datatable-space').html('<div class="text-center"><img src="{{asset('public/assets/images/scload.gif')}}" alt="loader"><h3>Loading...</h3></div>');
                            },
                            success: function (data) {
                                $('#datatable-space').html('');
                                $('.follow_up-tab').removeClass('active');
                                // $('#datatable-space').html(data.html);
                            }
                        });

                    });

                    $('#dashboard_country_id').change(function () {
                        var country_id = $(this).val();
                        $.ajax({
                            url: "{{url('get_dashboard_country_users')}}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                country_id: country_id,
                            },
                            success: function (data) {
                                $('#dashboard_user_id').html(data.html);
                            }
                        });
                    });

                    $(document).on('click', '#follow_up-tab', function () {
                        $('.follow_up-tab').removeClass('active');
                        $(this).addClass('active');
                        followup_type = $(this).attr('data-followup_type');

                        $.ajax({
                            url: "{{url('mom_stats')}}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                country_id: country_id,
                                user_id: user_id,
                                followup_type: followup_type,
                            },
                            beforeSend: function () {
                                {{--$('#datatable-space').html('<div class="text-center"><img src="{{asset('public/assets/images/scload.gif')}}" alt="loader"><h3>Loading...</h3></div>');--}}
                            },
                            success: function (data) {
                                $('#datatable-space').html('');
                                $('#datatable-space').html(data.html);
                            }
                        });
                    });

                });

                function get_followup_count(country_id = null, user_id = null) {
                    $.ajax({
                        url: "{{url('get_followup_count')}}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            country_id: country_id,
                            user_id: user_id,
                        },
                        beforeSend: function () {
                            $('#today-followups').html('<img src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader" width="22px" height="22px">');
                            $('#tomorrow-followups').html('<img src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader" width="22px" height="22px">');
                            $('#pending-followups').html('<img src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader" width="22px" height="22px">');
                            $('#future-followups').html('<img src="{{asset('public/assets/images/stats_load.gif')}}" alt="loader" width="22px" height="22px">');
                        },
                        success: function (data) {
                            if (data.status == true) {

                                if (data.total_today_records == 0) {
                                    // change pointer events to none
                                    $('#today-followups').parent().parent().parent().css('pointer-events', 'none');
                                    $('#today-followups').html(data.total_today_records);
                                } else {
                                    $('#today-followups').parent().parent().parent().css('pointer-events', 'auto');
                                    $('#today-followups').html(data.total_today_records);
                                }

                                if (data.total_tomorrow_records == 0) {
                                    $('#tomorrow-followups').parent().parent().parent().css('pointer-events', 'none');
                                    $('#tomorrow-followups').html(data.total_tomorrow_records);
                                } else {
                                    $('#tomorrow-followups').parent().parent().parent().css('pointer-events', 'auto');
                                    $('#tomorrow-followups').html(data.total_tomorrow_records);
                                }

                                if (data.total_pending_records == 0) {
                                    $('#pending-followups').parent().parent().parent().css('pointer-events', 'none');
                                    $('#pending-followups').html(data.total_pending_records);
                                } else {
                                    $('#pending-followups').parent().parent().parent().css('pointer-events', 'auto');
                                    $('#pending-followups').html(data.total_pending_records);
                                }

                                if (data.total_future_records == 0) {
                                    $('#future-followups').parent().parent().parent().css('pointer-events', 'none');
                                    $('#future-followups').html(data.total_future_records);
                                } else {
                                    $('#future-followups').parent().parent().parent().css('pointer-events', 'auto');
                                    $('#future-followups').html(data.total_future_records);
                                }

                                // $('#today-followups').html(data.total_today_records);
                                // $('#tomorrow-followups').html(data.total_tomorrow_records);
                                // $('#pending-followups').html(data.total_pending_records);
                                // $('#future-followups').html(data.total_future_records);
                            }
                        }
                    });
                }

                function get_chart_data(country_id = null, user_id = null) {
                    $.ajax({
                        url: "{{url('get_chart_data_view')}}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            country_id: country_id,
                            user_id: user_id,
                        },
                        beforeSend: function () {
                            {{--$('#chart-space').html('<div class="text-center"><img src="{{asset('public/assets/images/scload.gif')}}" alt="loader"><h3>Loading...</h3></div>');--}}
                        },
                        success: function (data) {
                            $('#chart-space').html('');
                            $('#chart-space').html(data.html);
                        }
                    });
                }


            </script>
        @endsection

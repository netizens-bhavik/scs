<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-4 order-0 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Client's Status </h5>
                </div>

            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-3" style="position: relative;">
                    <div id="client_status_chart" class="app-chart" style="height: 24px; width: 100%;"></div>

                </div>
                <ul class="p-0 m-0" id="client_status_data_list">

                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-4 order-0 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Job Status</h5>
                </div>

            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-3" style="position: relative;">

                    <div id="job_status_chart" class="app-chart" style="height: 24px; width: 100%;"></div>
                </div>
                <ul class="p-0 m-0" id="job_status_data_list">

                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-4 order-0 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Country-Wise Clients</h5>
                </div>

            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-3" style="position: relative;">

                    <div id="country_wise_status_chart" class="app-chart" style="height: 24px; width: 100%;"></div>
                </div>
                <ul class="p-0 m-0" id="country_wise_data_list">

                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            url: "{{url('get_chart_data')}}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                country_id: {{$country_id ?? 0}},
                user_id: {{$user_id ?? 0}},
            },
            beforeSend: function () {

            },
            success: function (data) {

                if (data.total_client != 0) {
                    var client_options = {
                        series: data.client_status,
                        labels: data.client_status_labels,
                        colors: ['#267278', '#65338d', '#4770b3', '#d21f75', '#f7b924', '#58595b'],
                        chart: {
                            type: 'donut',
                            width: 370
                        },
                        dataLabels: {
                            enabled: false
                        },
                        noData: {
                            text: "There's no data",
                            align: 'center',
                            verticalAlign: 'middle',
                            offsetX: 0,
                            offsetY: 0
                        },
                        responsive: [{
                            breakpoint: 480,

                            options: {
                                chart: {
                                    offsetX: 0,
                                    width: 280,
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    var client_chart = new ApexCharts(document.querySelector("#client_status_chart"), client_options);
                    client_chart.render();

                    var client_status_data = '';
                    $.each(data.client_status, function (index, value) {
                        client_status_data += '<li class="d-flex mb-4 pb-1">\n';
                        client_status_data += '<div class="avatar flex-shrink-0 me-3">\n';
                        client_status_data += '<span class="avatar-initial rounded bg-label-primary">\n';
                        client_status_data += '<i class="bx bxs-chevrons-right" style="cursor: default;"></i>\n';
                        client_status_data += '</span>\n';
                        client_status_data += '</div>\n';
                        client_status_data += '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">\n';
                        client_status_data += '<div class="me-2">\n';
                        client_status_data += '<h6 class="mb-0">' + data.client_status_labels[index] + '</h6>\n';
                        client_status_data += '</div>\n';
                        if (data.client_status_labels[index] == 'no data') {
                            client_status_data += '<div class="user-progress">';
                            client_status_data += '<small class="fw-semibold">' + 0 + '</small>';
                            client_status_data += '</div>';
                        } else {
                            client_status_data += '<div class="user-progress">';
                            client_status_data += '<small class="fw-semibold">' + value + '</small>';
                            client_status_data += '</div>';
                        }
                        client_status_data += '</div>\n';
                        client_status_data += '</li>\n';
                    });
                    $('#client_status_data_list').html(client_status_data);
                } else {
                    $('#client_status_chart').html('<h6 class="text-center"><span class="badge bg-label-danger">No Data Found</span></h6>');
                    var client_status_data = '';
                    $.each(data.client_status, function (index, value) {
                        client_status_data += '<li class="d-flex mb-4 pb-1">\n';
                        client_status_data += '<div class="avatar flex-shrink-0 me-3">\n';
                        client_status_data += '<span class="avatar-initial rounded bg-label-primary">\n';
                        client_status_data += '<i class="bx bxs-chevrons-right" style="cursor: default;"></i>\n';
                        client_status_data += '</span>\n';
                        client_status_data += '</div>\n';
                        client_status_data += '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">\n';
                        client_status_data += '<div class="me-2">\n';
                        client_status_data += '<h6 class="mb-0">' + data.client_status_labels[index] + '</h6>\n';
                        client_status_data += '</div>\n';
                        client_status_data += '<div class="user-progress">';
                        client_status_data += '<small class="fw-semibold">' + value + '</small>';
                        client_status_data += '</div>';
                        client_status_data += '</div>\n';
                        client_status_data += '</li>\n';
                    });
                    $('#client_status_data_list').html(client_status_data);
                }

                if(data.total_job != 0){
                    var job_options = {
                        series: data.job_status,
                        labels: data.job_status_labels,
                        chart: {
                            type: 'donut',
                            width: 310
                        },
                        dataLabels: {
                            enabled: false
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 260
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };
                    var job_chart = new ApexCharts(document.querySelector("#job_status_chart"), job_options);
                    job_chart.render();
                    var job_status_data = '';
                    $.each(data.job_status, function (index, value) {
                        job_status_data += '<li class="d-flex mb-4 pb-1">\n';
                        job_status_data += '<div class="avatar flex-shrink-0 me-3">\n';
                        job_status_data += '<span class="avatar-initial rounded bg-label-primary">\n';
                        job_status_data += '<i class="bx bxs-chevrons-right" style="cursor: default;"></i>\n';
                        job_status_data += '</span>\n';
                        job_status_data += '</div>\n';
                        job_status_data += '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">\n';
                        job_status_data += '<div class="me-2">\n';
                        job_status_data += '<h6 class="mb-0">' + data.job_status_labels[index] + '</h6>\n';
                        job_status_data += '</div>\n';
                        job_status_data += '<div class="user-progress">';
                        job_status_data += '<small class="fw-semibold">' + value + '</small>';
                        job_status_data += '</div>';
                        job_status_data += '</div>\n';
                        job_status_data += '</li>\n';
                    });
                    $('#job_status_data_list').html(job_status_data);
                }else{
                    $('#job_status_chart').html('<h6 class="text-center"><span class="badge bg-label-danger">No Data Found</span></h6>');
                    var job_status_data = '';
                    $.each(data.job_status, function (index, value) {
                        job_status_data += '<li class="d-flex mb-4 pb-1">\n';
                        job_status_data += '<div class="avatar flex-shrink-0 me-3">\n';
                        job_status_data += '<span class="avatar-initial rounded bg-label-primary">\n';
                        job_status_data += '<i class="bx bxs-chevrons-right" style="cursor: default;"></i>\n';
                        job_status_data += '</span>\n';
                        job_status_data += '</div>\n';
                        job_status_data += '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">\n';
                        job_status_data += '<div class="me-2">\n';
                        job_status_data += '<h6 class="mb-0">' + data.job_status_labels[index] + '</h6>\n';
                        job_status_data += '</div>\n';
                        if (data.job_status_labels[index] == 'no data') {
                            job_status_data += '<div class="user-progress">';
                            job_status_data += '<small class="fw-semibold">' + 0 + '</small>';
                            job_status_data += '</div>';
                        } else {
                            job_status_data += '<div class="user-progress">';
                            job_status_data += '<small class="fw-semibold">' + value + '</small>';
                            job_status_data += '</div>';
                        }
                        job_status_data += '</div>\n';
                        job_status_data += '</li>\n';
                    });
                    $('#job_status_data_list').html(job_status_data);
                }


                if(data.total_country != 0){
                    var country_option = {
                        series: data.country_wise_data,
                        labels: data.country_wise_labels,
                        chart: {
                            type: 'donut',
                            width: 370
                        },
                        dataLabels: {
                            enabled: false
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 240
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    var country_chart = new ApexCharts(document.querySelector("#country_wise_status_chart"), country_option);
                    country_chart.render();

                    var country_wise_data = '';
                    $.each(data.country_wise_data, function (index, value) {
                        country_wise_data += '<li class="d-flex mb-4 pb-1">';
                        country_wise_data += '<div class="avatar flex-shrink-0 me-3">';
                        country_wise_data += '<span class="avatar-initial rounded bg-label-success">';
                        country_wise_data += '<i class="bx bx-current-location" style="cursor: default;"></i>';
                        country_wise_data += '</span>';
                        country_wise_data += '</div>';
                        country_wise_data += '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">';
                        country_wise_data += '<div class="me-2">';
                        country_wise_data += '<h6 class="mb-0">' + data.country_wise_labels[index] + '</h6>';
                        country_wise_data += '</div>';
                        country_wise_data += '<div class="user-progress">';
                        country_wise_data += '<small class="fw-semibold">' + value + '</small>';
                        country_wise_data += '</div>';
                        country_wise_data += '</div>';
                        country_wise_data += '</li>';
                    });

                    $('#country_wise_data_list').html(country_wise_data);
                }else{

                    $('#country_wise_status_chart').html('<h6 class="text-center"><span class="badge bg-label-danger">No Data Found</span></h6>');
                    var country_wise_data = '';
                    $.each(data.country_wise_data, function (index, value) {
                        country_wise_data += '<li class="d-flex mb-4 pb-1">';
                        country_wise_data += '<div class="avatar flex-shrink-0 me-3">';
                        country_wise_data += '<span class="avatar-initial rounded bg-label-success">';
                        country_wise_data += '<i class="bx bx-current-location" style="cursor: default;"></i>';
                        country_wise_data += '</span>';
                        country_wise_data += '</div>';
                        country_wise_data += '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">';
                        country_wise_data += '<div class="me-2">';
                        country_wise_data += '<h6 class="mb-0">' + data.country_wise_labels[index] + '</h6>';
                        country_wise_data += '</div>';
                        if (data.country_wise_labels[index] == 'no data') {
                            country_wise_data += '<div class="user-progress">';
                            country_wise_data += '<small class="fw-semibold">' + 0 + '</small>';
                            country_wise_data += '</div>';
                        } else {
                            country_wise_data += '<div class="user-progress">';
                            country_wise_data += '<small class="fw-semibold">' + value + '</small>';
                            country_wise_data += '</div>';
                        }
                        country_wise_data += '</div>';
                        country_wise_data += '</li>';
                    });
                    $('#country_wise_data_list').html(country_wise_data);
                }

            }
        });
    });
</script>

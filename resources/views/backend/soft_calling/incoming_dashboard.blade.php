@extends('backend.dashboard_layouts')

@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/css/data-table.css') }}" />
@endsection


@php
// echo "<pre>"; print_r($temp_leads); echo "</pre>";
// exit;
@endphp

@section('main_content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header ">
                    <div class="main-heading">
                        <h4 class="header-title mb-0">INCOMING DASHBOARD</h4>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <span class="form-control-static">Seach Records </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="search" id="search" class="form-control"
                                            placeholder="Search Records" />
                                    </div>
                                    <div class="col-sm-8 my-3">
                                        <button class="btn btn-primary searchBtn" name="search" title="Search">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="search_result_datatable my-5" id="search_result_datatable">


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {

            function fetch_customer_data(query = '') {
                var query_string = query;
                query_string = query_string.replace(/\s+/g, ' ').trim();
                if(query_string.length == 0){
                    return false;
                }

                if (query == '') {
                    $('.search_result_datatable').html('');
                } else {
                    $.ajax({
                        url: "{{ route('search_temp_leads') }}",
                        method: 'POST',
                        data: {
                            search_query: query_string
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('.search_result_datatable').html(data.html);
                        }
                    })
                }
            }

            $(document).on('click', '.searchBtn', function() {
                var query = $('#search').val();
                fetch_customer_data(query);
            });

            $('#search').keypress(function(e) {
                if (e.which == 13) {
                    var query = $('#search').val();
                    fetch_customer_data(query);
                }
            });
        });
    </script>
@endsection

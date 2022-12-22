@extends('backend.dashboard_layouts')

@section('styles')
    <style>
        .app-card {
            border: 1px solid #e5e5e5;
            border-radius: 5px;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.05);
            margin-bottom: 8px;
            padding: 20px;
            background-color: #FFFFFF;
        }
    </style>
@endsection

@section('main_content')

{{--    @php--}}
{{--    echo "<pre>";--}}
{{--    print_r($client_data);--}}
{{--    echo "</pre>";--}}
{{--    @endphp--}}

    <div>
        <div class="row mb-2">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="main-heading">
                            <h4 class="header-title mb-0" style="display: inline-block">CLIENTS HISTORY</h4>
                            <a href="{{ url('/') }}"><span class="h5 text-primary">GO BACK</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="">
                    <div class="app-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 style="fill: #fff;">
                                <path
                                    d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm9-8.586 6 6V15l.001 5H6v-9.586l6-6z">
                                </path>
                                <path
                                    d="M12 18c3.703 0 4.901-3.539 4.95-3.689l-1.9-.621c-.008.023-.781 2.31-3.05 2.31-2.238 0-3.02-2.221-3.051-2.316l-1.899.627C7.099 14.461 8.297 18 12 18z">
                                </path>
                            </svg>
                        </div>
                        <div class="content">
                            <h5>Company Name</h5>
                            <h6>{{$client_data[0]->company_name}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="">
                    <div class="app-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #fff;">
                                <path d="M2.76 20.2a2.73 2.73 0 0 0 2.15.85 8.86 8.86 0 0 0 3.37-.86 9 9 0 0 0 12.27-10.9c1.31-2.23 1.75-4.26.67-5.48a2.94 2.94 0 0 0-2.57-1A5 5 0 0 0 16.1 4 9 9 0 0 0 3.58 15.14c-1.06 1.21-2.05 3.68-.82 5.06zm1.5-1.32c-.22-.25 0-1.07.37-1.76a9.26 9.26 0 0 0 1.57 1.74c-1.03.3-1.71.28-1.94.02zm14.51-5.17A7 7 0 0 1 15.58 18 7.12 7.12 0 0 1 12 19a6.44 6.44 0 0 1-1.24-.13 30.73 30.73 0 0 0 4.42-3.29 31.5 31.5 0 0 0 3.8-4 6.88 6.88 0 0 1-.21 2.13zm.09-8.89a.94.94 0 0 1 .87.32c.23.26.16.94-.26 1.93a9.2 9.2 0 0 0-1.61-1.86 2.48 2.48 0 0 1 1-.39zM5.22 10.31A6.94 6.94 0 0 1 8.41 6 7 7 0 0 1 12 5a6.9 6.9 0 0 1 6 3.41 5.19 5.19 0 0 1 .35.66 27.43 27.43 0 0 1-4.49 5A27.35 27.35 0 0 1 8.35 18a7 7 0 0 1-3.13-7.65z">
                                </path>
                            </svg>
                        </div>
                        <div class="content">
                            <h5>Company Website</h5>
                            <h6>{{$client_data[0]->website_name}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="">
                    <div class="app-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 style="fill: #fff;">
                                <path
                                    d="M16.57 22a2 2 0 0 0 1.43-.59l2.71-2.71a1 1 0 0 0 0-1.41l-4-4a1 1 0 0 0-1.41 0l-1.6 1.59a7.55 7.55 0 0 1-3-1.59 7.62 7.62 0 0 1-1.59-3l1.59-1.6a1 1 0 0 0 0-1.41l-4-4a1 1 0 0 0-1.41 0L2.59 6A2 2 0 0 0 2 7.43 15.28 15.28 0 0 0 6.3 17.7 15.28 15.28 0 0 0 16.57 22zM6 5.41 8.59 8 7.3 9.29a1 1 0 0 0-.3.91 10.12 10.12 0 0 0 2.3 4.5 10.08 10.08 0 0 0 4.5 2.3 1 1 0 0 0 .91-.27L16 15.41 18.59 18l-2 2a13.28 13.28 0 0 1-8.87-3.71A13.28 13.28 0 0 1 4 7.41zM20 11h2a8.81 8.81 0 0 0-9-9v2a6.77 6.77 0 0 1 7 7z">
                                </path>
                                <path d="M13 8c2.1 0 3 .9 3 3h2c0-3.22-1.78-5-5-5z"></path>
                            </svg>
                        </div>
                        <div class="content">
                            <h5>Phone No</h5>
                            <h6>{{$client_data[0]->phone_no}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="">
                    <div class="app-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 style="fill: #fff;">
                                <path
                                    d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z">
                                </path>
                            </svg>
                        </div>
                        <div class="content">
                            <h5>Email Id</h5>
                            <h6>{{$client_data[0]->email}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="mom_table_container card p-3">
                    <table id="client_mom_table" class="table table-striped table-bordered">
                        <thead>
                        <th>No.</th>
                        <th class="all">Date</th>
                        <th>User Name</th>
                        <th>Contact Person</th>
                        <th>Minutes Of Minutes</th>
                        <th>BDE Feedback</th>
                        <th>Next MOM Date-Time</th>
                        <th>MOM Status</th>
                        <th>Added By</th>
                        </thead>
                        <tbody>
                        @foreach($client_data['moms'] as $mom)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{date('d/m/Y',strtotime($mom->meeting_date))}}</td>
                                <td>{{$client_data[0]->user_name}}</td>
                                <td>{{$mom->contact_person}}</td>
                                <td>{{$mom->minutes_of_meeting}}</td>
                                <td>{{$mom->bde_feedback}}</td>
                                <td>{{date('d/m/Y',strtotime($mom->next_followup_date))}}  {{date('h:i:A',strtotime($mom->next_followup_time))}} </td>
                                <td>
                                    @if($mom->mom_type == 1)
                                        Follow Up
                                    @elseif($mom->mom_type == 2)
                                        Meeting
                                    @elseif($mom->mom_type == 3)
                                        Requirement Discussion
                                    @elseif($mom->mom_type == 4)
                                        Close
                                    @else
                                        Not Started
                                    @endif
                                </td>
                                <td>{{$mom->created_by_name}}</td>

                            @endforeach

                        </tbody>


                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#client_mom_table').DataTable({
                paging: false,
                searching: false,
                info: false,
                responsive: true,
            });
        });
    </script>
@endsection


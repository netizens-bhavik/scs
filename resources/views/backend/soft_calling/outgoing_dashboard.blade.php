@extends('backend.dashboard_layouts')

@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/css/data-table.css') }}" />
@endsection



@section('main_content')
    @if (count($temp_leads) > 0)
        @php
            if ($temp_leads['last_call_date']) {
                $last_call_date = date('d/m/Y H:i', strtotime($temp_leads['last_call_date']));
            } else {
                $last_call_date = 'N/A';
            }
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="main-heading">
                            <h4 class="header-title mb-0" style="display: inline-block">OUTGOING DASHBOARD</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-8 col-xxl-7">
                                <div class="main-details">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M19 4H6V2H4v18H3v2h4v-2H6v-5h13a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1zm-1 9H6V6h12v7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Country Name</h5>
                                                    <h6>{{ $temp_leads['company_country'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
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
                                                    <h6>{{ $temp_leads['company_name'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V9h4v10z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Industry Name</h5>
                                                    <h6>{{ $temp_leads['industry'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24"style="fill: #fff;">
                                                        <path
                                                            d="M8 12c2.28 0 4-1.72 4-4s-1.72-4-4-4-4 1.72-4 4 1.72 4 4 4zm0-6c1.178 0 2 .822 2 2s-.822 2-2 2-2-.822-2-2 .822-2 2-2zm1 7H7c-2.757 0-5 2.243-5 5v1h2v-1c0-1.654 1.346-3 3-3h2c1.654 0 3 1.346 3 3v1h2v-1c0-2.757-2.243-5-5-5zm9.364-10.364L16.95 4.05C18.271 5.373 19 7.131 19 9s-.729 3.627-2.05 4.95l1.414 1.414C20.064 13.663 21 11.403 21 9s-.936-4.663-2.636-6.364z">
                                                        </path>
                                                        <path
                                                            d="M15.535 5.464 14.121 6.88C14.688 7.445 15 8.198 15 9s-.312 1.555-.879 2.12l1.414 1.416C16.479 11.592 17 10.337 17 9s-.521-2.592-1.465-3.536z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Contact Person Name</h5>
                                                    <h6>{{ $temp_leads['contact_person_name'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M5 16h3v3c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2v-9c0-1.103-.897-2-2-2h-3V5c0-1.103-.897-2-2-2H5c-1.103 0-2 .897-2 2v9c0 1.103.897 2 2 2zm9.001-2L14 10h.001v4zM19 10l.001 9H10v-3h4c1.103 0 2-.897 2-2v-4h3zM5 5h9v3h-4c-1.103 0-2 .897-2 2v4H5V5z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Department </h5>
                                                    <h6>{{ $temp_leads['department'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M11.219 3.375 8 7.399 4.781 3.375A1.002 1.002 0 0 0 3 4v15c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V4a1.002 1.002 0 0 0-1.781-.625L16 7.399l-3.219-4.024c-.381-.474-1.181-.474-1.562 0zM5 19v-2h14.001v2H5zm10.219-9.375c.381.475 1.182.475 1.563 0L19 6.851 19.001 15H5V6.851l2.219 2.774c.381.475 1.182.475 1.563 0L12 5.601l3.219 4.024z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Designation </h5>
                                                    <h6>{{ $temp_leads['designation'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Email id</h5>
                                                    <h6>{{ $temp_leads['contact_person_email'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M2.76 20.2a2.73 2.73 0 0 0 2.15.85 8.86 8.86 0 0 0 3.37-.86 9 9 0 0 0 12.27-10.9c1.31-2.23 1.75-4.26.67-5.48a2.94 2.94 0 0 0-2.57-1A5 5 0 0 0 16.1 4 9 9 0 0 0 3.58 15.14c-1.06 1.21-2.05 3.68-.82 5.06zm1.5-1.32c-.22-.25 0-1.07.37-1.76a9.26 9.26 0 0 0 1.57 1.74c-1.03.3-1.71.28-1.94.02zm14.51-5.17A7 7 0 0 1 15.58 18 7.12 7.12 0 0 1 12 19a6.44 6.44 0 0 1-1.24-.13 30.73 30.73 0 0 0 4.42-3.29 31.5 31.5 0 0 0 3.8-4 6.88 6.88 0 0 1-.21 2.13zm.09-8.89a.94.94 0 0 1 .87.32c.23.26.16.94-.26 1.93a9.2 9.2 0 0 0-1.61-1.86 2.48 2.48 0 0 1 1-.39zM5.22 10.31A6.94 6.94 0 0 1 8.41 6 7 7 0 0 1 12 5a6.9 6.9 0 0 1 6 3.41 5.19 5.19 0 0 1 .35.66 27.43 27.43 0 0 1-4.49 5A27.35 27.35 0 0 1 8.35 18a7 7 0 0 1-3.13-7.65z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Website Name</h5>
                                                    <h6>{{ $temp_leads['website_name'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M19 2H9c-1.103 0-2 .897-2 2v6H5c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4c0-1.103-.897-2-2-2zM5 12h6v8H5v-8zm14 8h-6v-8c0-1.103-.897-2-2-2H9V4h10v16z">
                                                        </path>
                                                        <path
                                                            d="M11 6h2v2h-2zm4 0h2v2h-2zm0 4.031h2V12h-2zM15 14h2v2h-2zm-8 .001h2v2H7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Client City</h5>
                                                    <h6>{{ $temp_leads['company_city'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M16.57 22a2 2 0 0 0 1.43-.59l2.71-2.71a1 1 0 0 0 0-1.41l-4-4a1 1 0 0 0-1.41 0l-1.6 1.59a7.55 7.55 0 0 1-3-1.59 7.62 7.62 0 0 1-1.59-3l1.59-1.6a1 1 0 0 0 0-1.41l-4-4a1 1 0 0 0-1.41 0L2.59 6A2 2 0 0 0 2 7.43 15.28 15.28 0 0 0 6.3 17.7 15.28 15.28 0 0 0 16.57 22zM6 5.41 8.59 8 7.3 9.29a1 1 0 0 0-.3.91 10.12 10.12 0 0 0 2.3 4.5 10.08 10.08 0 0 0 4.5 2.3 1 1 0 0 0 .91-.27L16 15.41 18.59 18l-2 2a13.28 13.28 0 0 1-8.87-3.71A13.28 13.28 0 0 1 4 7.41zM20 11h2a8.81 8.81 0 0 0-9-9v2a6.77 6.77 0 0 1 7 7z">
                                                        </path>
                                                        <path d="M13 8c2.1 0 3 .9 3 3h2c0-3.22-1.78-5-5-5z"></path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Phone No</h5>
                                                    <h6>{{ $temp_leads['company_phone_no'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Email Id</h5>
                                                    <h6>{{ $temp_leads['company_email'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M16.75 2h-10c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h10c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zm-10 18V4h10l.002 16H6.75z">
                                                        </path>
                                                        <circle cx="11.75" cy="18" r="1"></circle>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Mobile No</h5>
                                                    <h6>{{ $temp_leads['contact_person_phone'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M19 5h-6V2h-2v3H5C3.346 5 2 6.346 2 8v10c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V8c0-1.654-1.346-3-3-3zM5 7h14a1 1 0 0 1 1 1l.001 3.12c-.896.228-1.469.734-1.916 1.132-.507.45-.842.748-1.588.748-.745 0-1.08-.298-1.587-.747-.595-.529-1.409-1.253-2.915-1.253-1.505 0-2.319.724-2.914 1.253-.507.45-.841.747-1.586.747-.743 0-1.077-.297-1.582-.747-.447-.398-1.018-.905-1.913-1.133V8a1 1 0 0 1 1-1zM4 18v-4.714c.191.123.374.274.583.461C5.178 14.276 5.991 15 7.495 15c1.505 0 2.319-.724 2.914-1.253.507-.45.841-.747 1.586-.747s1.08.298 1.587.747c.595.529 1.409 1.253 2.915 1.253s2.321-.724 2.916-1.253c.211-.188.395-.34.588-.464L20.002 18H4z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5> Date of Birth </h5>
                                                    <h6>{{ $temp_leads['dob'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <circle cx="12" cy="12" r="4"></circle>
                                                        <path
                                                            d="M13 4.069V2h-2v2.069A8.01 8.01 0 0 0 4.069 11H2v2h2.069A8.008 8.008 0 0 0 11 19.931V22h2v-2.069A8.007 8.007 0 0 0 19.931 13H22v-2h-2.069A8.008 8.008 0 0 0 13 4.069zM12 18c-3.309 0-6-2.691-6-6s2.691-6 6-6 6 2.691 6 6-2.691 6-6 6z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Client Address </h5>
                                                    <h6>{{ $temp_leads['address'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                            <div class="app-card">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" style="fill: #fff;">
                                                        <path
                                                            d="M22 8a.76.76 0 0 0 0-.21v-.08a.77.77 0 0 0-.07-.16.35.35 0 0 0-.05-.08l-.1-.13-.08-.06-.12-.09-9-5a1 1 0 0 0-1 0l-9 5-.09.07-.11.08a.41.41 0 0 0-.07.11.39.39 0 0 0-.08.1.59.59 0 0 0-.06.14.3.3 0 0 0 0 .1A.76.76 0 0 0 2 8v8a1 1 0 0 0 .52.87l9 5a.75.75 0 0 0 .13.06h.1a1.06 1.06 0 0 0 .5 0h.1l.14-.06 9-5A1 1 0 0 0 22 16V8zm-10 3.87L5.06 8l2.76-1.52 6.83 3.9zm0-7.72L18.94 8 16.7 9.25 9.87 5.34zM4 9.7l7 3.92v5.68l-7-3.89zm9 9.6v-5.68l3-1.68V15l2-1v-3.18l2-1.11v5.7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="content">
                                                    <h5>Client Post Box. No</h5>
                                                    <h6>{{ $temp_leads['post_box_no'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-xxl-5">
                                <div class="last-call-history mb-3">
                                    <div class="row gx-2">
                                        <div class="col-md-12">
                                            <h6>Last Call History :</h6>
                                        </div>
                                        <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4 mb-2">
                                            <div class="app-card">
                                                <div class="content">
                                                    <h5 class="text-secondary text-dark h6 mb-1">Call Date and Time : </h5>
                                                    <h6 class="text-primary h6">
                                                        {{ $last_call_date ?? 'N/A' }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4 mb-2">
                                            <div class="app-card">
                                                <div class="content">
                                                    <h5 class="text-secondary text-dark h6 mb-1">Call Status : </h5>
                                                    <h6 class="text-primary h6">
                                                        {{ $temp_leads['calling_status'] ?? 'N/A' }} </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-8 col-xxl-4 mb-2">
                                            <div class="app-card">
                                                <div class="content">
                                                    <h5 class="text-secondary text-dark h6 mb-1">Called By : </h5>
                                                    <h6 class="text-primary h6">
                                                        {{ $temp_leads['last_called_by'] ?? 'N/A' }} </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" id="soft_calling_outgoing_frm" method="POST"
                                    enctype="multipart/form-data">
                                    <input type="hidden" name="_token"
                                        value="5B1h5dBVeHCDp0eNnfabLBLjqi677YXIV4nsWFp9"> <input type="hidden"
                                        name="lead_id" value="{{ $temp_leads['id'] }}">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-12 col-xxl-6 mb-0 mb-xl-2 mb-xxl-0">
                                            <span class="text-danger">*</span>
                                            <label for="call_status">Select Call Status :</label>
                                            <select class="form-control form-select" name="call_status" id="call_status"
                                                placeholder="Select Call Status" required="">
                                                <option value="">Select Call Status</option>
                                                <option value="1">BUSY</option>
                                                <option value="2">CALL LATER</option>
                                                <option value="3">CALLED</option>
                                                <option value="4">DO NOT CALL AGAIN</option>
                                                <option value="5">NO REQUIREMENT</option>
                                                <option value="6">NOT REACHABLE</option>
                                                <option value="7">OUT OF SERVICE</option>
                                                <option value="8">RINGING</option>
                                                <option value="9">SWITCH OFF</option>
                                                <option value="10">WRONG NUMBER</option>
                                            </select>
                                            <span class="text-danger" id="cs_error_message"></span>

                                        </div>
                                        <div class="col-md-6 col-xl-12 col-xxl-6" id="ncd" style="display: none">
                                            <span class="text-danger">*</span>
                                            <label for="call_date">Next Call Date :</label>
                                            <input type="datetime-local" class="form-control" name="next_call_date"
                                                id="next_call_date" placeholder="Next Call Date">
                                            <span class="text-danger" id="ncd_error_message"></span>
                                        </div>

                                        <div class="col-md-12 mt-3" id="called_details" style="display: none">
                                            <div class="row">
                                                <div class="col-sm-6 col-xl-12 col-xxl-6 mb-0 mb-xl-2 mb-xxl-0">
                                                    <span class="text-danger">*</span>
                                                    <label for="spoken/-with">Spoken with :</label>
                                                    <input type="text" class="form-control" name="spoken_with"
                                                        id="spoken_with" placeholder="Spoken with">
                                                    <span class="text-danger" id="sw_error_message"></span>
                                                </div>
                                                <div class="col-sm-6 col-xl-12 col-xxl-6">
                                                    <span class="text-danger">*</span>
                                                    <label for="cell_no">Cell No :</label>
                                                    <input type="text" class="form-control" name="cell_no"
                                                        id="cell_no" placeholder="Cell No">
                                                    <span class="text-danger" id="cn_error_message"></span>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-6 col-xl-12 col-xxl-6 mb-0 mb-xl-2 mb-xxl-0">
                                                    <span class="text-danger">*</span>
                                                    <label for="email_id">Email Id :</label>
                                                    <input type="text" class="form-control" name="email_id"
                                                        id="email_id" placeholder="Email Id">
                                                    <span class="text-danger" id="ei_error_message"></span>
                                                </div>
                                                <div class="col-sm-6 col-xl-12 col-xxl-6 mt-3 mt-sm-0">
                                                    <span class="text-danger">*</span>
                                                    <label for="manpower" class="mb-2">Do you have any manpower
                                                        requirement?</label> <br>
                                                    <input type="radio" class="form-check-input requirement-radio"
                                                        name="requirement" value="1" id="requirement"> Yes
                                                    <input type="radio" class="form-check-input requirement-radio"
                                                        name="requirement" value="2" id="requirement"
                                                        checked=""> No
                                                    <span class="text-danger" id="r_error_message"></span>
                                                </div>
                                                <div class="col-12 mt-3" id="requirement_remarks_block"
                                                    style="display: none">
                                                    <span class="text-danger">*</span>
                                                    <label for="requirement_remarks">Basic Requirement :</label>
                                                    <textarea class="form-control" name="requirement_remarks" id="requirement_remarks" placeholder="Remarks"></textarea>
                                                    <span class="text-danger" id="rr_error_message"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <button type="submit" class="btn btn-primary btn-md me-2" title="Submit"> Submit</button>
                                            <button type="reset" class="btn btn-outline-danger btn-md resetBtn" title="Reset">
                                                Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12 contailer-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h3 class="text-secondary">Outgoing Dashboard</h3>
                                <span class="text-primary h4">
                                    No Records Available
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {

            // $('#call_status').select2();

            $("input[name='cell_no']").keypress(
                function(event) {
                    if (event.which == '13') {
                        event.preventDefault();
                    }
                });

            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            var hh = today.getHours();
            var min = today.getMinutes();
            var ss = today.getSeconds();
            if (dd < 10) {
                dd = '0' + dd
            }
            if (mm < 10) {
                mm = '0' + mm
            }
            if (hh < 10) {
                hh = '0' + hh
            }
            if (min < 10) {
                min = '0' + min
            }
            if (ss < 10) {
                ss = '0' + ss
            }
            today = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
            $("#next_call_date").attr("min", today);
        });
        $('#call_status').change(function(e) {
            e.preventDefault();
            var call_status = $(this).val();
            //alert(call_status);
            if (call_status == 2) {
                $('#ncd').show();
            } else {
                $('#ncd').hide();
            }
            if (call_status == 3) {
                $('#called_details').show();
            } else {
                $('#called_details').hide();
            }
        });

        $(document).on('click', '.resetBtn', function() {
            $('#ncd').hide();
            $('#called_details').hide();
        });

        $('.requirement-radio').change(function(e) {
            e.preventDefault();
            var requirement = $('.requirement-radio:checked').val()
            if (requirement == 1) {
                $('#requirement_remarks_block').show();
            } else {
                $('#requirement_remarks_block').hide();
            }
        });

        $('#soft_calling_outgoing_frm').submit(function(e) {
            e.preventDefault();
            var form_data = ($(this).serialize());

            var call_status = $('#call_status').val();

            if (call_status == '') {
                $('#cs_error_message').html('Please select call status');
                return false;
            } else {
                $('#cs_error_message').html('');
            }

            if (call_status == 2) {
                var next_call_date = $('#next_call_date').val();
                if (next_call_date == '') {
                    $('#ncd_error_message').html('Please select next call date');
                    return false;
                } else {
                    $('#ncd_error_message').html('');
                }
            }

            if (call_status == 3) {
                var spoken_with = $('#spoken_with').val();
                if (spoken_with == '') {
                    $('#sw_error_message').html('Please enter spoken with');
                } else {
                    $('#sw_error_message').html('');
                }
                var cell_no = $('#cell_no').val();
                if (cell_no == '') {
                    $('#cn_error_message').html('Please enter cell number');
                } else {
                    if (cell_no < 0) {
                        $('#cn_error_message').html('Please enter a valid cell number');
                        return false;
                    } else {
                        if (!$.isNumeric(cell_no)) {
                            $('#cn_error_message').html('Please enter a valid cell number');
                            return false;
                        } else {
                            $('#cn_error_message').html('');
                        }
                    }
                    //$('#cn_error_message').html('Please enter a valid cell number');
                    //return false;
                }
                var email_id = $('#email_id').val();
                if (email_id == '') {
                    $('#ei_error_message').html('Please enter email id');
                } else {
                    $('#ei_error_message').html('');
                }
                var requirement = $('.requirement-radio:checked').val()
                if (requirement == '') {
                    $('#r_error_message').html('Please select any requirement');
                } else {
                    $('#r_error_message').html('');
                }
                if (requirement == 1) {
                    var requirement_remarks = $('#requirement_remarks').val();
                    if (requirement_remarks == '') {
                        $('#rr_error_message').html('Please enter requirement remarks');
                    } else {
                        $('#rr_error_message').html('');
                    }
                }

                if (spoken_with == '' || cell_no == '' || email_id == '' || requirement == '' ||
                    requirement_remarks == '') {
                    return false;
                }
            }

            $.ajax({
                type: "POST",
                url: "{{ url('outgoing_call_status') }}",
                data: form_data,
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
                            location.reload();
                        });
                    } else {
                        swal.close();
                        swal({
                            title: "Error",
                            text: response.message,
                            icon: "error",
                            button: "OK",
                        }).then(function() {
                            location.reload();
                        });
                    }
                }
            });
        });
    </script>
@endsection

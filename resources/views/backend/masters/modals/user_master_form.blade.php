<div class="modal" id="user_master_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header container">
                <h5 class="modal-title" id="modalFullTitle">
                    {{-- User Master --}}
                    Manage User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <span class="text-danger" id="error_msg"></span>
                    <div class="col-12">
                        <span class="form-control-label h5"> User Details : </span>
                    </div>
                    <form id="user_master_form" class="row g-3" method="POST" action="javascript:void(0)"
                        enctype="multipart/form-data" role="form">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user_data['id'] ?? '' }}">
                        <input type="hidden" name="report_to_user_data" id="report_to_user_data"
                            value="{{ $user_data['reporting_user_id'] ?? '' }}">
                        <div class="col-md-6">
                            <span class="text-danger">*</span>
                            <label for="user_position" class="form-label">User Position</label>
                            <select name="user_position" id="user_position" class="form-control form-select"
                                value="{{ $user_data['role'] ?? '' }}" required>
                                <option value="">Select User Position</option>
                                <option value="administrator"
                                    @isset($user_data)
                                    @if ($user_data['role'] == 'administrator') selected @endif
                                    @endisset>
                                    Administrator</option>
                                <option value="director"
                                    @isset($user_data)
                                @if ($user_data['role'] == 'director') selected @endif
                                @endisset>
                                    Director</option>
                                <option value="general manager"
                                    @isset($user_data)
                                @if ($user_data['role'] == 'general manager') selected @endif
                                @endisset>
                                    General Manager</option>
                                <option value="bdm"
                                    @isset($user_data)
                                @if ($user_data['role'] == 'bdm') selected @endif
                                @endisset>
                                    BDM</option>
                                <option value="bde"
                                    @isset($user_data)
                                @if ($user_data['role'] == 'bde') selected @endif
                                @endisset>
                                    BDE</option>
                                <option value="softcaller"
                                    @isset($user_data)
                                @if ($user_data['role'] == 'softcaller') selected @endif
                                @endisset>
                                    SoftCaller</option>
                            </select>
                            <span class="text-danger" id="user_position_error_msg"></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-danger">*</span>
                            <label for="user_email" class="form-label">User Name(Email)</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Email Address" value="{{ $user_data['email'] ?? '' }}" required>
                            <span class="text-danger" id="email_error_msg"></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-danger">*</span>
                            <label for="actual_name" class="form-label">Actual Name</label>
                            <input type="text" class="form-control" id="actual_name" name="actual_name"
                                placeholder="Actual Name" value="{{ $user_data['name'] ?? '' }}" required>
                            <span class="text-danger" id="actual_name_error_msg"></span>
                        </div>

                        @if (!isset($user_data))
                            <div class="col-md-6">
                                <span class="text-danger">*</span>
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder=" Enter Password" required>
                                <span class="text-danger" id="password_error_msg"></span>
                            </div>
                        @endisset

                        <div class="col-md-6">
                            <span class="text-danger">*</span>
                            <label for="user_location" class="form-label">User Location</label>
                            <select name="user_location" id="user_location" class="form-control form-select"
                                value="{{ $user_data['country_id'] ?? '' }}" required>
                                <option value="">Select User Location</option>
                                @foreach ($country as $country)
                                    <option value="{{ $country['id'] }}"
                                        @isset($user_data)
                                    @if ($user_data['country_id'] == $country['id']) selected @endif
                                    @endisset>
                                        {{ $country['country_name'] }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="user_location_error_msg"></span>
                        </div>


                        <div class="col-md-6" id="report_to_div" style="display:none">
                            <span class="text-danger">*</span>
                            <label for="user_location" class="form-label">Report To </label>
                            <select name="report_to" id="report_to" class="form-control form-select">
                            </select>
                            <span class="text-danger" id="user_location_error_msg"></span>
                        </div>

                        <hr>
                        <div class="col-12">
                            <span class="form-control-label h5"> User Rights : </span>
                        </div>
                        <div class="col-12  m-0">
                            Select All Rights :
                            <input class="form-check-input permission_check_all" type="checkbox"
                                name="user_rights[]" value="">
                        </div>

                        <div class="col-md-12">
                            <table class="table permission-listing-table">
                                <thead>
                                    <tr>
                                        <th>Modules</th>
                                        <th>Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>User</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="user_view"
                                                        id="user_view"
                                                        @isset($user_data)
                                                        @if (in_array('user_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="user_add"
                                                        id="user_add"
                                                        @isset($user_data)
                                                        @if (in_array('user_add', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Add
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="user_edit"
                                                        id="user_edit"
                                                        @isset($user_data)
                                                        @if (in_array('user_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="user_delete"
                                                        id="user_delete"
                                                        @isset($user_data)
                                                        @if (in_array('user_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>City</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="city_view"
                                                        id="city_view"
                                                        @isset($user_data)
                                                        @if (in_array('city_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="city_add"
                                                        id="city_add"
                                                        @isset($user_data)
                                                        @if (in_array('city_add', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Add
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="city_edit"
                                                        id="city_edit"
                                                        @isset($user_data)
                                                        @if (in_array('city_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="city_delete"
                                                        id="city_delete"
                                                        @isset($user_data)
                                                        @if (in_array('city_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Country</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="country_view"
                                                        id="country_view"
                                                        @isset($user_data)
                                                        @if (in_array('country_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="country_add"
                                                        id="country_add"
                                                        @isset($user_data)
                                                    @if (in_array('country_add', $user_data['permissions'])) checked @endif
                                                    @endisset>
                                                    Add
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="country_edit"
                                                        id="country_edit"
                                                        @isset($user_data)
                                                        @if (in_array('country_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="country_delete" id="country_delete"
                                                        @isset($user_data)
                                                        @if (in_array('country_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Industry</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="industry_view" id="industry_view"
                                                        @isset($user_data)
                                                        @if (in_array('industry_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="industry_add"
                                                        id="industry_add"
                                                        @isset($user_data)
                                                        @if (in_array('industry_add', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Add
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="industry_edit" id="industry_edit"
                                                        @isset($user_data)
                                                        @if (in_array('industry_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="industry_delete" id="industry_delete"
                                                        @isset($user_data)
                                                        @if (in_array('industry_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>MOM Modes</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                           type="checkbox" name="user_rights[]"
                                                           value="mom_mode_view" id="mom_mode_view"
                                                           @isset($user_data)
                                                               @if (in_array('mom_mode_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                           type="checkbox" name="user_rights[]" value="mom_mode_add"
                                                           id="mom_mode_add"
                                                           @isset($user_data)
                                                               @if (in_array('mom_mode_add', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Add
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                           type="checkbox" name="user_rights[]"
                                                           value="mom_mode_edit" id="mom_mode_edit"
                                                           @isset($user_data)
                                                               @if (in_array('mom_mode_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                           type="checkbox" name="user_rights[]"
                                                           value="mom_mode_delete" id="mom_mode_delete"
                                                           @isset($user_data)
                                                               @if (in_array('mom_mode_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Client</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="client_view"
                                                        id="client_view"
                                                        @isset($user_data)
                                                        @if (in_array('client_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="client_add"
                                                        id="client_add"
                                                        @isset($user_data)
                                                        @if (in_array('client_add', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Add
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="client_edit"
                                                        id="client_edit"
                                                        @isset($user_data)
                                                        @if (in_array('client_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="client_delete" id="client_delete"
                                                        @isset($user_data)
                                                        @if (in_array('client_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="transfer_lead" id="transfer_lead"
                                                        @isset($user_data)
                                                        @if (in_array('transfer_lead', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Transfer Clients
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Soft Calling</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_upload" id="soft_call_upload"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_upload', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Import Records
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_view" id="soft_call_view"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View Records
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_add" id="soft_call_add"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_add', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Add Records
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_edit" id="soft_call_edit"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit Records
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_delete" id="soft_call_delete"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete Records
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_incoming" id="soft_call_incoming"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_incoming', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Incoming Dashboard
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_outgoing" id="soft_call_outgoing"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_outgoing', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Outgoing Dashboard
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_assign" id="soft_call_assign"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_assign', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Lead Assign
                                                </div>
                                                <div class="col-lg-6 col-xl-3">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="soft_call_view_assigned_leads"
                                                        id="soft_call_view_assigned_leads"
                                                        @isset($user_data)
                                                        @if (in_array('soft_call_view_assigned_leads', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View Assigned Leads
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>MOM</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="mom_view"
                                                        id="mom_view"
                                                        @isset($user_data)
                                                        @if (in_array('mom_view', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    View
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="mom_add"
                                                        id="mom_add"
                                                        @isset($user_data)
                                                        @if (in_array('mom_add', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Add
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="mom_edit"
                                                        id="mom_edit"
                                                        @isset($user_data)
                                                        @if (in_array('mom_edit', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Edit
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="mom_delete"
                                                        id="mom_delete"
                                                        @isset($user_data)
                                                        @if (in_array('mom_delete', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Delete
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]"
                                                        value="mom_job_status" id="mom_job_status"
                                                        @isset($user_data)
                                                        @if (in_array('mom_job_status', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Manage Job Status
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Notes</td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="manage_notes"
                                                        id="manage_notes"
                                                        @isset($user_data)
                                                        @if (in_array('manage_notes', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Manage Notes/Reminder
                                                </div>

                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Report </td>
                                        <td>
                                            <div class="permissions-check-listing">
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="mom_report"
                                                        id="mom_report"
                                                        @isset($user_data)
                                                        @if (in_array('mom_report', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    MOM Report
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="call_status_report"
                                                        id="call_status_report"
                                                        @isset($user_data)
                                                        @if (in_array('call_status_report', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Call Status Report
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="call_status_uw_report"
                                                        id="call_status_uw_report"
                                                        @isset($user_data)
                                                        @if (in_array('call_status_uw_report', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Call Status (User Wise) Report
                                                </div>
                                                <div class="permissions-check-click">
                                                    <input class="form-check-input permission_check"
                                                        type="checkbox" name="user_rights[]" value="client_status_report"
                                                        id="client_status_report"
                                                        @isset($user_data)
                                                        @if (in_array('client_status_report', $user_data['permissions'])) checked @endif
                                                        @endisset>
                                                    Client Status Report
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                Close
            </button>
            <button type="submit" class="btn btn-primary submitUserForm" title="Submit">Save</button>
        </div>
        </form>
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
      //  role_based_permissions_check();
        bindUserModalEvents();
        permission_check();
        report_to_user_list();
    });


    function bindUserModalEvents() {

        $('#user_master_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                bindUserValidationEvent();
            }
        });

        $('.submitUserForm').click(function(e) {
            e.preventDefault();
            bindUserValidationEvent();
        });
    }

    $("#user_master_form").validate({
        rules: {
            user_position: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            actual_name: {
                required: true,
            },
            password: {
                required: true,
                minlength: 6
            },
            user_location: {
                required: true
            },
            report_to: {
                required: true
            }

        },
        messages: {
            user_position: {
                required: "Please select user position",
            },
            actual_name: {
                required: "Please enter actual name",
            },
            email: {
                required: "Please enter email",
                email: "Please enter valid email"
            },
            password: {
                required: "Please enter password",
                minlength: "Password must be at least 6 characters long"
            },
            user_location: {
                required: "Please select user location",
            },
            report_to: {
                required: "Please select report to user",
            }
        }
    });

    function bindUserValidationEvent() {
        if ($("#user_master_form").valid()) {
            formCheckSubmit();
        }
    }

    // function role_based_permissions_check() {
    //     $('#user_position').change(function(e) {
    //         e.preventDefault();
    //         var role = $(this).val();
    //         if (role != "") {
    //             $.ajax({
    //                 type: "POST",
    //                 url: "{{ url('/permissions_check') }}",
    //                 data: {
    //                     role: role,
    //                     _token: "{{ csrf_token() }}"
    //                 },
    //                 dataType: "json",
    //                 success: function(response) {
    //                     if (response.permissions.length > 0) {
    //                         $('.permission_check').prop('checked', false);
    //                         $.each(response.permissions, function(index, value) {
    //                             $('#' + value).prop('checked', true);
    //                         });
    //                     } else {
    //                         $('.permission_check').prop('checked', false);
    //                     }
    //                 }
    //             });
    //         } else {
    //             $('.permission_check').prop('checked', false);
    //         }
    //     });
    // }

    function report_to_user_list() {
        var report_user_data = $('#report_to_user_data').val();
        if (report_user_data != "") {
            var role = $('#user_position').val();
            if (role == 'administrator' || role == 'softcaller') {
                $('#report_to_div').hide();
            } else {
                $('#report_to_div').show();
            }
            if (role != "") {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/report_to_user_list') }}",
                    data: {
                        role: role,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.users.length > 0) {
                            $('#report_to').html('');
                            $('#report_to').append('<option value="">' +
                                'Select Reporting User' + '</option>');
                            $.each(response.users, function(index, value) {
                                if (value.id == report_user_data) {
                                    $('#report_to').append('<option value="' + value.id +
                                        '" selected>' +
                                        value.name + '</option>');
                                } else {
                                    $('#report_to').append('<option value="' + value.id + '">' +
                                        value.name + '</option>');
                                }

                            });
                        } else {
                            $('#report_to').html('');
                        }
                    }
                });
            } else {
                $('#report_to').html('');
            }
        }
        $('#user_position').change(function(e) {
            e.preventDefault();
            var role = $(this).val();
            if (role == 'administrator' || role == 'softcaller') {
                $('#report_to_div').hide();
            } else {
                $('#report_to_div').show();
            }
            if (role != "") {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/report_to_user_list') }}",
                    data: {
                        role: role,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.users.length > 0) {
                            $('#report_to').html('');
                            $('#report_to').append('<option value="">' +
                                'Select Reporting User' + '</option>');
                            $.each(response.users, function(index, value) {
                                $('#report_to').append('<option value="' + value.id + '">' +
                                    value.name + '</option>');
                            });
                        } else {
                            $('#report_to').html('');
                        }
                    }
                });
            } else {
                $('#report_to').html('');
            }
        });
    }



    function formCheckSubmit() {
        var user_id = $('#user_id').val();

        var actual_name = $('#actual_name').val();

        var regexps = /^[a-zA-Z ]*$/;
        if (!regexps.test(actual_name)) {
            $('#actual_name_error_msg').html('Please enter valid name');
            return false;
        } else {
            $('#actual_name_error_msg').html('');
        }

        var email = $('#email').val();
        var regex =
            /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,9}|[0-9]{1,3})(\]?)$/;
        if (!regex.test(email)) {
            $('#email_error_msg').html('Please enter valid email');
            return false;
        } else {
            $('#email_error_msg').html('');
        }

        var form_data = $('#user_master_form').serialize();
        if (user_id == '') {
            $.ajax({
                type: "POST",
                url: "{{ url('/add_user') }}",
                data: form_data,
                datatype: "json",
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
                success: function(data) {
                    if (data.status == true) {
                        swal.close();
                        swal({
                            title: "Success",
                            text: data.message,
                            icon: "success",
                            button: "OK",
                        }).then(function() {
                            $('#user_master_form')[0].reset();
                            $('#user_master_modal').modal('hide');
                            jQuery('#user_master_table').DataTable().ajax.reload();
                        });

                    } else {
                        swal.close();
                        swal("Error", data.message, "error");
                    }
                }
            });
        }
        if (user_id != '') {
            $.ajax({
                type: "POST",
                url: "{{ url('/update_user') }}",
                data: form_data,
                datatype: "json",
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
                success: function(data) {
                    if (data.status == true) {
                        swal.close();
                        swal({
                            title: "Success",
                            text: data.message,
                            icon: "success",
                            button: "OK",
                        }).then(function() {
                            $('#user_master_form')[0].reset();
                            $('#user_master_modal').modal('hide');
                            jQuery('#user_master_table').DataTable().ajax.reload();
                        });

                    } else {
                        swal.close();
                        swal("Error", data.message, "error");
                    }
                }
            });
        }
    }

    function permission_check() {
        $('.permission_check_all').click(function() {
            if ($(this).is(':checked')) {
                $('.permission_check').prop('checked', true);
            } else {
                $('.permission_check').prop('checked', false);
            }
        });

        $(".permission_check").on('click', function() {
            if ($('.permission_check:checked').length == $('.permission_check').length) {
                $('.permission_check_all').prop('checked', true);
            } else {
                $('.permission_check_all').prop('checked', false);
            }
        });
    }
</script>

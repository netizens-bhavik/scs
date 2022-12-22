<style>
    @media (max-width: 1199px) {
        .add-mom-modal .modal-dialog {
            max-width: 1200px;
            margin: 40px
        }
    }

    @media (max-width: 767px) {
        .add-mom-modal .modal-dialog {
            margin: 20px 10px;
        }
    }

    @media (max-width: 767px) {
        .add-mom-modal .modal-dialog .modal-body {
            margin: 20px 10px;
        }
    }

    form .cp_error:not(li):not(input) {
        color: #ff3e1d;
        font-size: 85%;
        margin-top: 0.25rem;
    }
</style>

<div class="modal add-mom-modal" id="manageMomModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFullTitle">
                    {{ isset($mom['id']) && !empty($mom['id']) ? 'Edit MOM' : 'Add MOM' }}                    
                </h5>
                <button type="button" class="btn-close bm_modal_close" data-bs-dismiss="modal"
                        aria-label="Close" title="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <form id="mom_master_form" method="POST" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="mom_id" value="{{ $mom['id'] ?? '' }}">
                    @php
                        if (isset($dashboard_flag) && isset($mom['follow_up_id'])) {
                            $follow_up_id = $mom['follow_up_id'] ?? '';
                            echo '<input type="hidden" name="follow_up_id" id="follow_up_id" value="'.$follow_up_id.'">';
                        }                   
                    @endphp                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="text-danger">*</span>
                            <label for="meeting_date" class="form-label">Meeting Date</label>
                            <input type="date" class="form-control" name="meeting_date"
                                   value="{{ $mom['meeting_date'] ?? '' }}" placeholder="Meeting Date">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="client_id" class="form-label">Company Name</label>
                            <select id="client_id" name="client_id" class="form-select company_select">
                                <option value="">Select Company</option>
                                @foreach ($clients as $value)
                                    <option value="{{ $value['id'] }}"
                                        {{ isset($mom['client_id']) && $mom['client_id'] == $value['id'] ? 'selected' : '' }}>
                                        {{ $value['company_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <select id="contact_person" name="contact_person" class="form-select contact_person_select">
                                <option value="">Select Contact Person</option>
                                @if (isset($mom['contactPersons']) && !empty($mom['contactPersons']))
                                    @foreach ($mom['contactPersons'] as $value)
                                        <option value="{{ $value['name'] }}"
                                            {{ isset($mom['contact_person']) && $mom['contact_person'] == $value['name'] ? 'selected' : '' }}>
                                            {{ $value['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="minutes_of_meeting" class="form-label">Minutes of Meeting </label>
                            <textarea class="form-control" name="minutes_of_meeting"
                                      placeholder="Minutes of Meeting">{{ $mom['minutes_of_meeting'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="bde_feedback" class="form-label">BDE Feedback </label>
                            <textarea class="form-control" name="bde_feedback"
                                      placeholder="BDE Feedback">{{ $mom['bde_feedback'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 mt-0">
                            <span class="text-danger">*</span>
                            <label for="mom_type" class="form-label me-3">MOM Type </label>
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input mom_type" type="radio" name="mom_type" value="1"
                                    {{ (isset($mom['mom_type']) && $mom['mom_type'] == 1) || !isset($mom['id']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Follow Up</label>
                            </div>
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input mom_type" type="radio" name="mom_type" value="2"
                                    {{ isset($mom['mom_type']) && $mom['mom_type'] == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Meeting</label>
                            </div>
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input mom_type" type="radio" name="mom_type" value="3"
                                    {{ isset($mom['mom_type']) && $mom['mom_type'] == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Requirement Discussion</label>
                            </div>
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input mom_type" type="radio" name="mom_type" value="4"
                                    {{ isset($mom['mom_type']) && $mom['mom_type'] == 4 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Close</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="followup_input"
                         style="display: {{ isset($mom['mom_type']) && $mom['mom_type'] == 4 ? 'none' : 'block' }}">
                        <div class="col-12 mt-0 mb-3">
                            <span class="text-danger">*</span>
                            <label for="followup" class="form-label me-3">Follow Up </label>
                            <div class="form-check form-check-inline mt-3" id="personal_followup"
                                 style="display: {{ isset($mom['followup']) && ($mom['followup'] == 1 || $mom['followup'] == 2) ? 'inline-block' : (!isset($mom['followup']) ? 'inline-block' : 'none') }}">
                                <input class="form-check-input followup" id="followup1" type="radio"
                                       name="followup" value="1"
                                    {{ (isset($mom['followup']) && $mom['followup'] == 1) || !isset($mom['id']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Personal</label>
                            </div>
                            <div class="form-check form-check-inline mt-3" id="share_followup"
                                 style="display: {{ isset($mom['followup']) && ($mom['followup'] == 1 || $mom['followup'] == 2) ? 'inline-block' : (!isset($mom['followup']) ? 'inline-block' : 'none') }}">
                                <input class="form-check-input followup" id="followup2" type="radio"
                                       name="followup" value="2"
                                    {{ isset($mom['followup']) && $mom['followup'] == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Share</label>
                            </div>
                            <div class="form-check form-check-inline mt-3" id="next_followup"
                                 style="display: {{ isset($mom['followup']) && $mom['followup'] == 3 ? 'inline-block' : 'none' }}">
                                <input class="form-check-input followup" id="followup3" type="radio"
                                       name="followup" value="3"
                                    {{ isset($mom['followup']) && $mom['followup'] == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Next Follow Up Date and
                                    Time</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="share_user"
                         style="display: {{ isset($mom['followup']) && $mom['followup'] == 2 ? 'block' : 'none' }}">
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="share_user_id" class="form-label">Share</label>
                            <select id="share_user_id" name="share_user_id" class="form-select">
                                <option value="">Select User</option>
                                @foreach ($users as $value)
                                    <option value="{{ $value['id'] }}"
                                        {{ isset($mom['share_user_id']) && $mom['share_user_id'] == $value['id'] ? 'selected' : '' }}>
                                        {{ strtoupper($value['roles'][0]['name']) . '-' . ucwords($value['country']['country_name'] . '-' . $value['name']) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @php
                        if (isset($mom['mom_type']) && $mom['mom_type'] == 4) {
                            $dstr = 'none';
                        } else {
                            $dstr = 'block';
                        }
                    @endphp
                    <div class="row g-3">
                        <div class="col-lg-6 mb-0 mb-lg-3" id="next_followup_datetime"
                             style="display: {{ $dstr }}">
                            <div class="row g-3">
                                <div class="col-sm-6 mb-3">
                                    <span class="text-danger">*</span>
                                    <label for="next_followup_date" class="form-label">Next Follow Up Date</label>
                                    <input type="date" class="form-control" name="next_followup_date"
                                           value="{{ $mom['next_followup_date'] ?? '' }}"
                                           placeholder="Next Follow Up Date">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <span class="text-danger">*</span>
                                    <label for="next_followup_time" class="form-label">Next Follow Up Time</label>
                                    <input type="time" class="form-control" name="next_followup_time"
                                           value="{{ isset($mom['next_followup_time']) && !empty($mom['next_followup_time']) ? date('H:i', strtotime($mom['next_followup_time'])) : '' }}"
                                           placeholder="Next Follow Up Time">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="client_status" class="form-label">Client Status</label>
                            <select id="client_status" name="client_status" class="form-select">
                                <option value="">Select Client Status</option>
                                <option value="1"
                                    {{ isset($mom['client_status']) && $mom['client_status'] == '1' ? 'selected' : '' }}>
                                    High Priority
                                </option>
                                <option value="2"
                                    {{ isset($mom['client_status']) && $mom['client_status'] == '2' ? 'selected' : '' }}>
                                    Medium Priority
                                </option>
                                <option value="3"
                                    {{ isset($mom['client_status']) && $mom['client_status'] == '3' ? 'selected' : '' }}>
                                    Low Priority
                                </option>
                                <option value="4"
                                    {{ isset($mom['client_status']) && $mom['client_status'] == '4' ? 'selected' : '' }}>
                                    Requirement Received
                                </option>
                                <option value="5"
                                    {{ isset($mom['client_status']) && $mom['client_status'] == '5' ? 'selected' : '' }}>
                                    Under Discussion
                                </option>
                                <option value="6"
                                    {{ isset($mom['client_status']) && $mom['client_status'] == '6' ? 'selected' : '' }}>
                                    Closed
                                </option>
                            </select>
                        </div>
                    </div>
                    <div id="job_info"
                         style="display: {{ isset($mom['mom_type']) && $mom['mom_type'] == 3 ? 'block' : 'none' }}">
                        <div class="col-md-12">

                            @if (isset($mom['jobs']) && !empty($mom['jobs']))
                                @foreach ($mom['jobs'] as $jkey => $jvalue)
                                    <div class="row job_input">
                                        <input type="hidden" name="job_id[]" value="{{ $jvalue['id'] ?? '' }}">
                                        <div class="col-6 col-sm-4 col-lg-2">
                                            <span class="text-danger">*</span>
                                            <label for="job_date" class="form-label">Date</label>
                                            <input type="date" class="form-control job_date" name="job_date[]"
                                                   placeholder="Date" value="{{ $jvalue['j_date'] ?? '' }}">
                                            <label class="cp_error"></label>
                                        </div>
                                        <div class="col-6 col-sm-4 col-lg-2">
                                            <span class="text-danger">*</span>
                                            <label for="job_category" class="form-label">Job Category</label>
                                            <input type="text" class="form-control" name="job_category[]"
                                                   value="{{ $jvalue['job_category'] ?? '' }}"
                                                   placeholder="Job Category">
                                            <label class="cp_error"></label>
                                        </div>
                                        <div class="col-6 col-sm-4 col-lg-2">
                                            <span class="text-danger">*</span>
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="quantity[]"
                                                   value="{{ $jvalue['quantity'] ?? '' }}" placeholder="Quantity">
                                            <label class="cp_error"></label>
                                        </div>
                                        <div class="col-6 col-sm-4 col-lg-2">
                                            <span class="text-danger">*</span>
                                            <label for="job_description" class="form-label">Job Description</label>
                                            <input type="text" class="form-control" name="job_description[]"
                                                   value="{{ $jvalue['job_description'] ?? '' }}"
                                                   placeholder="Job Description">
                                            <label class="cp_error"></label>
                                        </div>
                                        <div class="col align-self-center">
                                            <a href="javascript:void(0);"
                                               class="btn btn btn-icon btn-outline-primary delete_job"
                                               data-id="{{ $jvalue['id'] ?? '' }}" title="Delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row g-3 job_input">
                                    <input type="hidden" name="job_id[]">
                                    <div class="col-6 col-sm-4 col-lg-2">
                                        <span class="text-danger">*</span>
                                        <label for="job_date" class="form-label">Date</label>
                                        <input type="date" class="form-control job_date" name="job_date[]"
                                               placeholder="Date">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-6 col-sm-4 col-lg-2">
                                        <span class="text-danger">*</span>
                                        <label for="job_category" class="form-label">Job Category</label>
                                        <input type="text" class="form-control" name="job_category[]"
                                               placeholder="Job Category">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-6 col-sm-4 col-lg-2">
                                        <span class="text-danger">*</span>
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" name="quantity[]"
                                               placeholder="Quantity">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-6 col-sm-4 col-lg-2">
                                        <span class="text-danger">*</span>
                                        <label for="job_description" class="form-label">Job Description</label>
                                        <input type="text" class="form-control" name="job_description[]"
                                               placeholder="Job Description">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col align-self-center">
                                        <a href="javascript:void(0);"
                                           class="btn btn btn-icon btn-outline-primary delete_job" title="Delete">
                                            <i class="bx bx-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-primary float-end" id="add_new_job" title="Add">
                                    <i class="bx bx-plus"></i> Add More
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary bm_modal_close" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" id="save_mom" title="Submit">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        bindMomModalEvents();

        $(document).on('click', '.bm_modal_close', function () {
            {{--var url = "{{ url('moms') }}";--}}
            {{--var view_assinged_leads_url = "{{ url('view_assinged_leads') }}";--}}

            {{--var currentUrl = window.location.href;--}}
            {{--if (currentUrl == view_assinged_leads_url) {--}}
            {{--    window.location.reload();--}}
            {{--} else if (currentUrl == url) {--}}
            {{--    // window.location.reload();--}}
            {{--} else {--}}
            {{--    // window.location.href = url;--}}
            {{--    //redirect to back page--}}
            {{--    window.history.back();--}}

            {{--}--}}
        });
    });

    /**
     * Bind Modal Events
     *
     */
    function bindMomModalEvents() {
        $("#save_mom").on('click', function (e) {
            bindMomValidationEvent();
        });

        bindMomTypeChangeEvent();
        bindAddJobEvent();
        bindDeleteJobEvent();
        bindInputChangeEvent();
        bindCompanyChangeEvent();

        $("input[name='quantity[]']").keypress(
            function (event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            });

        // disabled future datetime
        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if (month < 10)
            month = '0' + month.toString();
        if (day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;
        $("input[name='next_meeting_date[]'],input[name='next_followup_date']").attr('min', maxDate);
        $("input[name='meeting_date']").attr('max', maxDate);

    }

    function bindMomTypeChangeEvent() {
        $(".mom_type").on('click', function (e) {
            var checkedVal = $('.mom_type:checked').val();
            switch (checkedVal) {
                case '1':
                    $("#followup_input,#personal_followup,#share_followup,#next_followup_datetime").show();
                    $(".followup").prop('checked', false);
                    $("#followup1").prop('checked', true);
                    $("#next_followup,#job_info,#share_user").hide();
                    break;
                case '2':
                    $("#followup_input,#personal_followup,#share_followup,#next_followup_datetime").show();
                    $(".followup").prop('checked', false);
                    $("#followup1").prop('checked', true);
                    $("#next_followup,#job_info,#share_user").hide();
                    break;
                case '3':
                    $("#followup_input,#next_followup,#job_info,#next_followup_datetime,.job_input").show();
                    $(".followup").prop('checked', false);
                    $("#followup3").prop('checked', true);
                    $("#personal_followup,#share_followup,#share_user").hide();
                    break;
                case '4':
                    $(".followup").prop('checked', false);
                    $("input[name='next_followup_date'],input[name='next_followup_time']").val("");
                    $(".job_input").find(
                        "input[type=date],input[type=number],input[type=time],input:text,input:hidden").val(
                        "").end()
                    $("#followup_input,#share_user,#next_followup_datetime,#job_info").hide();
                    break;
                default:
                    break;
            }
        });

        $(".followup").on("click", function (e) {
            var checked = $('.followup:checked').val();
            switch (checked) {
                case '1':
                    $("#share_user").hide();
                    $("#next_followup_datetime").show();
                    break;
                case '2':
                    $("#share_user").show();
                    $("#next_followup_datetime").show();
                    break;
                case '3':
                    $("#next_followup_datetime").show();
                    $("#share_user").hide();
                    break;
                default:
                    break;
            }
        });
    }

    function bindAddJobEvent() {
        $("#add_new_job").on("click", function () {
            if ($(".job_input:visible").length >= 1) {
                $('.job_input:last').clone()
                    .find("input[type=date]").val("").end()
                    .find("input[type=time]").val("").end()
                    .find("input[type=number]").val("").end()
                    .find("input:text,input:hidden").val("").end()
                    .insertAfter(".job_input:last")
                /* find("input").each(function () {
                    $(this).rules("add", {
                        required: true
                    });
                }) */
                ;
                $(".job_input:last .cp_error").html('');
            } else {
                $(".job_input").show()
            }
            bindDeleteJobEvent();
            bindInputChangeEvent();
        });
    }

    function bindDeleteJobEvent() {
        $(".delete_job").unbind().on("click", function (e) {
            if ($(".delete_job").length != 1) {
                var id = $(e.target).closest('a').attr('data-id');
                if (id != '') {
                    jQuery.ajax({
                        type: 'POST',
                        url: "{{ url('delete_mom_job') }}",
                        dataType: 'JSON',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function (data) {
                            $(e.target).closest('.job_input').remove();
                        },
                        complete: function () {

                        },
                        error: function () {
                        }
                    });
                } else {
                    $(e.target).closest('.job_input').remove();
                }
            } else {
                swal("Error", "You mast have to add atleast one Job", "error");
            }
        });
    }

    /**
     *  input change event
     * */
    function bindInputChangeEvent() {

        $("input[name='job_date[]']").on('change', function () {
            if ($(this).val() == '') {
                $(this).next().html('Date is required').show();
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='job_category[]']").on('change', function () {
            if ($(this).val() == '') {
                $(this).next().html('Category is required');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='quantity[]']").on('change', function () {
            if ($(this).val() == '') {
                $(this).next().html('Quantity is required');
            } else if (isNaN($(this).val())) {
                $(this).next().html('Invalid Quantity');
            } else if (Math.sign($(this).val()) <= 0) {
                $(this).next().html('Invalid Quantity');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='job_description[]']").on('change', function () {
            if ($(this).val() == '') {
                $(this).next().html('Description is required');
            } else {
                $(this).next().html('');
            }
        });
    }

    /**
     * validate mom
     *
     */
    $("#mom_master_form").validate({
        rules: {
            meeting_date: {
                required: true,
            },
            client_id: {
                required: true
            },
            contact_person: {
                required: true
            },
            minutes_of_meeting: {
                required: true
            },
            bde_feedback: {
                required: true
            },
            share_user_id: {
                required: function () {
                    if ($(".followup:checked").val() == 2 && $("#share_user_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            next_followup_date: {
                required: function () {
                    if ($(".followup:checked").val() != 4 && $("input[name='next_followup_date']").val() ==
                        '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            next_followup_time: {
                required: function () {
                    if ($(".followup:checked").val() != 4 && $("input[name='next_followup_time']").val() ==
                        '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            client_status: {
                required: true
            }
        },
        messages: {
            meeting_date: {
                required: "Meeting Date is required",
            },
            client_id: {
                required: "Company Name is required",
            },
            contact_person: {
                required: "Contact Person is required",
            },
            minutes_of_meeting: {
                required: "Minutes of Meeting is required",
            },
            bde_feedback: {
                required: "Feedback is required",
            },
            share_user_id: {
                required: "User is required",
            },
            next_followup_date: {
                required: "Followup date is required",
            },
            next_followup_time: {
                required: "Followup time is required",
            },
            client_status: {
                required: "Client Status is required",
            }
        }
    });


    /**
     * Save mom
     *
     */
    function bindMomValidationEvent() {
        var flag = true;
        if ($(".mom_type:checked").val() == 3) {
            $("input[name='job_date[]']").each(function (i, value) {
                if ($(this).val() == '') {
                    $(this).next().html('Date is required').show();
                    flag = false;
                }
            });

            $("input[name='job_category[]']").each(function (i, value) {
                if ($(this).val() == '') {
                    $(this).next().html('Category is required');
                    flag = false;
                }
            });

            $("input[name='quantity[]']").each(function (i, value) {
                if ($(this).val() == '') {
                    $(this).next().html('Quantity is required');
                    flag = false;
                } else if (isNaN($(this).val())) {
                    $(this).next().html('Invalid Quantity');
                    flag = false;
                } else if (Math.sign($(this).val()) <= 0) {
                    $(this).next().html('Invalid Quantity');
                    flag = false;
                } else {
                    $(this).next().html('');
                }
            });

            $("input[name='job_description[]']").each(function (i, value) {
                if ($(this).val() == '') {
                    $(this).next().html('Description is required');
                    flag = false;
                }
            });
        }

        if ($("#mom_master_form").valid()) {
            if (flag == true) {
                saveMOM();
            }
        }
    }

    function saveMOM() {
        jQuery.ajax({
            type: 'POST',
            url: "{{ url('save_mom') }}",
            dataType: 'JSON',
            data: jQuery("#mom_master_form").serialize(),
            beforeSend: function () {
                swal({
                    title: "info",
                    text: "Please Wait, Your Request has been processed!",
                    icon: "info",
                    button: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false
                });
            },
            success: function (data) {
                if (data.status == true) {
                    swal.close();
                    jQuery("#manageMomModal").modal('hide');

                    //if document contains #assigned_leads_table or #mom_table then reload datatable

                    if ($("#assigned_leads_table").length || $("#mom_table").length) {
                        var isBDE = '{{ isset($isBDE) ? $isBDE : 0 }}';
                        if (isBDE == 1) {
                            if (!$.fn.dataTable.isDataTable('#assigned_leads_table')) {
                                initAssignedLeadsTable();
                            } else {
                                jQuery('#assigned_leads_table').DataTable().ajax.reload();
                            }
                        } else {
                            if (!$.fn.dataTable.isDataTable('#mom_table')) {
                                initMomTable();
                            } else {
                                jQuery('#mom_table').DataTable().ajax.reload();
                            }
                        }
                    }
                    //if document contains #followup_datatable then reload datatable
                    if ($("#followup_datatable").length) {

                        if (!$.fn.dataTable.isDataTable('#followup_datatable')) {

                        } else {

                            jQuery('#followup_datatable').DataTable().ajax.reload();
                        }
                    }
                    //if document contains #manageMomModalBox then remove html from #manageMomModalBox
                    if ($("#manageMomModalBox").length) {
                        $("#manageMomModalBox").html('');
                    }

                    //call get_followup_count function if document contains #followup_count
                    if ($("#followup_count").length) {
                       // alert('call get_followup_count function');

                        $("#manageMomModal").modal('hide');
                        $("#manageMomModalBox").html('');
                        var check_user_id = '{{ $dashboard_country_id ?? 0 }}';
                        var check_country_id = '{{ $dashboard_user_id ?? 0 }}';
                        get_followup_count(check_user_id, check_country_id);
                    }
                    //call get_chart_data function if document contains #chart-space
                    if ($("#chart-space").length) {
                       // alert('call get_chart_data function');

                        $("#manageMomModal").modal('hide');
                        $("#manageMomModalBox").html('');
                        var check_user_id = '{{ $dashboard_country_id ?? 0 }}';
                        var check_country_id = '{{ $dashboard_user_id ?? 0 }}';
                        get_chart_data(check_user_id, check_country_id);
                    }
                    swal({
                        title: "Success",
                        text: data.message,
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        var url = "{{ url('moms') }}";
                        var view_assinged_leads_url = "{{ url('view_assinged_leads') }}";
                        var currentUrl = window.location.href;
                        if (currentUrl == view_assinged_leads_url) {
                            window.location.reload();
                        } else if (currentUrl == url) {
                            // window.location.reload();
                        } else {
                            //window.location.href = url;
                        }
                    });
                } else {
                    swal.close();
                    var error = '';
                    $.each(data.errors, function (key, value) {
                        error += value
                    });
                    swal("Error", error, "error");
                }
            },
            complete: function () {

            },
            error: function () {
            }
        });
    }

    function bindCompanyChangeEvent() {
        jQuery(".company_select").on('change', function (e) {
            $(".contact_person_select").html(`<option value=''>Select Contact Person</option>`);
            jQuery.ajax({
                type: 'POST',
                url: "{{ url('get_contact_persons') }}",
                dataType: 'JSON',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: $(e.target).val()
                },
                success: function (data) {
                    if (data.status == true) {
                        console.log(data.contactPerson)
                        $.each(data.contactPerson, function (key, value) {
                            $(".contact_person_select").append(`<option value="` + value
                                .name + `">` + value.name + `</option>`);
                        });
                    }
                },
                complete: function () {

                },
                error: function () {
                }
            });
        });
    }
</script>

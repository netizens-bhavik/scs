<style>
    @media(max-width: 1199px) {
        .add-client-modal .modal-dialog {
            max-width: 1200px;
            margin: 40px
        }
    }

    @media(max-width: 767px) {
        .add-client-modal .modal-dialog {
            margin: 20px 10px;
        }
    }

    @media(max-width: 767px) {
        .add-client-modal .modal-dialog .modal-body {
            margin: 20px 10px;
        }
    }

    form .cp_error:not(li):not(input) {
        color: #ff3e1d;
        font-size: 85%;
        margin-top: 0.25rem;
    }
</style>
<div class="modal add-client-modal" data-bs-backdrop="static" data-bs-keyboard="false" id="manageClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFullTitle">
                    {{-- {{ isset($client['id']) && !empty($client['id']) ? 'Edit Client' : 'Add Client' }} --}}
                    Manage Client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <form id="client_master_form" method="POST" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="client_id" value="{{ $client['id'] ?? '' }}">
                    <input type="hidden" name="lead_id" id="lead_id" value="{{ $client['lead_id'] ?? '' }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_name"
                                value="{{ $client['company_name'] ?? '' }}" placeholder="Company Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="industry" class="form-label">Industry</label>
                            <select id="industry" name="industry_id" class="form-select">
                                <option value="">Select Industry</option>
                                @foreach ($indusries as $value)
                                    <option value="{{ $value['id'] }}"
                                        {{ isset($client['industry_id']) && $client['industry_id'] == $value['id'] ? 'selected' : '' }}>
                                        {{ $value['industry_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email ID</label>
                            <input type="text" class="form-control" name="email"
                                value="{{ $client['email'] ?? '' }}" placeholder="Email ID">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_no" class="form-label">Phone No</label>
                            <input type="text" class="form-control" name="phone_no"
                                value="{{ $client['phone_no'] ?? '' }}" placeholder="Phone No">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="country" class="form-label">Country</label>
                            <select name="country_id" id="country_select" class="form-select">
                                <option value="">Select Country</option>
                                @foreach ($countries as $value)
                                    <option value="{{ $value['id'] }}"
                                        {{ isset($client['country_id']) && $client['country_id'] == $value['id'] ? 'selected' : '' }}>
                                        {{ $value['country_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city_id" class="form-label">City</label>
                            <select id="city_select" name="city_id" class="form-select">
                                <option value="">Select City</option>
                                @if (isset($cities) && !empty($cities))
                                    @foreach ($cities as $value)
                                        <option value="{{ $value['id'] }}"
                                            {{ isset($client['city_id']) && $client['city_id'] == $value['id'] ? 'selected' : '' }}>
                                            {{ $value['city_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="post_box_no" class="form-label">Post Box No.</label>
                            <input type="text" class="form-control" name="post_box_no"
                                value="{{ $client['post_box_no'] ?? '' }}" placeholder="Post Box No.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="website_name" class="form-label">Website Name</label>
                            <input type="text" class="form-control" name="website_name"
                                value="{{ $client['website_name'] ?? '' }}" placeholder="http://www.example.com">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <span class="text-danger">*</span>
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" name="address" placeholder="Address" required>{{ $client['address'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sort_description" class="form-label">Client Short Description</label>
                            <textarea class="form-control" name="sort_description" placeholder="Client Short Description">{{ $client['sort_description'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mt-0">
                        <label for="active_status" class="form-label me-3">Client Status </label>
                        <div class="form-check form-check-inline mt-3">
                            <input class="form-check-input" type="radio" name="active_status" value="1"
                                {{ (isset($client['active_status']) && $client['active_status'] == 1) || !isset($client['id']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio1">Active</label>
                        </div>
                        <div class="form-check form-check-inline mt-3">
                            <input class="form-check-input" type="radio" name="active_status" value="0"
                                {{ isset($client['active_status']) && $client['active_status'] == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio1">Inactive</label>
                        </div>
                    </div>

                    <div class="col-md-12 my-2">
                        <h5 class="m-0">Contact Person :</h5>
                    </div>
                    <div class="col-md-12" id="contact_person_info">

                        @if (isset($client['contactPerson']) && !empty($client['contactPerson']))
                            @foreach ($client['contactPerson'] as $ckey => $cpvalue)
                                <div class="row contact_person_input">
                                    <input type="hidden" name="cp_id[]" value="{{ $cpvalue['id'] ?? '' }}">
                                    <div class="col-7 col-sm-4 col-lg-3">
                                        <span class="text-danger">*</span>
                                        <label for="cp_name" class="form-label">Contact Person Name</label>
                                        <input type="text" class="form-control" name="cp_name[]"
                                            value="{{ $cpvalue['name'] ?? '' }}" placeholder="Name">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-7 col-sm-4 col-lg-3">
                                        <span class="text-danger">*</span>
                                        <label for="department" class="form-label">Department</label>
                                        <input type="text" class="form-control" name="department[]"
                                            value="{{ $cpvalue['department'] ?? '' }}" placeholder="Department">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-7 col-sm-4 col-lg-3">
                                        <span class="text-danger">*</span>
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text" class="form-control" name="designation[]"
                                            value="{{ $cpvalue['designation'] ?? '' }}" placeholder="Designation">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-7 col-sm-4 col-lg-3">
                                        <span class="text-danger">*</span>
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" name="cp_email[]"
                                            value="{{ $cpvalue['email'] ?? '' }}" placeholder="Email">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-7 col-sm-4 col-lg-3">
                                        <span class="text-danger">*</span>
                                        <label for="mobile_no" class="form-label">Mobile No</label>
                                        <input type="number" class="form-control" name="mobile_no[]"
                                            value="{{ $cpvalue['mobile_no'] ?? '' }}" placeholder="Mobile No">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col-7 col-sm-4 col-lg-3">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control dob" name="dob[]"
                                            value="{{ $cpvalue['dob'] ?? '' }}" placeholder="Date of Birth">
                                        <label class="cp_error"></label>
                                    </div>
                                    <div class="col align-self-center">
                                        <label class="form-label"></label>
                                        <a href="javascript:void(0);"
                                            class="btn btn btn-icon btn-outline-primary delete_contact_person"
                                            data-id="{{ $cpvalue['id'] ?? '' }}" title="Delete">
                                            <i class="bx bx-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row contact_person_input gx-3">
                                <input type="hidden" name="cp_id[]" value="">
                                <div class="col-sm-6 col-lg-3">
                                    <span class="text-danger">*</span>
                                    <label for="cp_name" class="form-label">Contact Person Name</label>
                                    <input type="text" class="form-control" name="cp_name[]" value=""
                                        placeholder="Name">
                                    <label class="cp_error"></label>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <span class="text-danger">*</span>
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control" name="department[]" value=""
                                        placeholder="Department">
                                    <label class="cp_error"></label>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <span class="text-danger">*</span>
                                    <label for="designation" class="form-label">Designation</label>
                                    <input type="text" class="form-control" name="designation[]" value=""
                                        placeholder="Designation">
                                    <label class="cp_error"></label>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <span class="text-danger">*</span>
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" name="cp_email[]" value=""
                                        placeholder="Email">
                                    <label class="cp_error"></label>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <span class="text-danger">*</span>
                                    <label for="mobile_no" class="form-label">Mobile No</label>
                                    <input type="text" class="form-control" name="mobile_no[]" value=""
                                        placeholder="Mobile No">
                                    <label class="cp_error"></label>
                                </div>
                                <div class="col-8 col-sm-4 col-lg-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control dob" name="dob[]" value=""
                                        placeholder="Date of Birth">
                                    <label class="cp_error"></label>
                                </div>
                                <div class="col-4 col-sm-2 col-lg-3">
                                    <label class="form-label"> &nbsp; </label>
                                    <a href="javascript:void(0);"
                                        class="btn btn btn-icon btn-outline-primary delete_contact_person d-flex"
                                        title="Delete">
                                        <i class="bx bx-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary float-end" id="add_new_contact_person" title="Add">
                                <i class="bx bx-plus"></i> Add More
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" id="save_client" title="Submit">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        bindClientModalEvents();

        //$('#industry').select2();
        // $('#country_id').select2();
        // $('#city_select').select2();
    });

    /**
     * Bind Modal Events
     *
     */
    function bindClientModalEvents() {
        $("#save_client").on('click', function(e) {
            bindClientValidationEvent();
        });

        bindCountryChangeEvent();
        bindAddContactPersonEvent();
        bindDeleteContactPersonEvent();
        bindInputChangeEvent();

        $("input[name='mobile_no[]']").keypress(
            function(event) {
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
        $("input[name='dob[]']").attr('max', maxDate);
    }

    function bindCountryChangeEvent() {
        jQuery("#country_select").on('change', function(e) {
            $("#city_select").html(`<option value=''>Select City</option>`);
            jQuery.ajax({
                type: 'POST',
                url: "{{ url('get_city') }}",
                dataType: 'JSON',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: $(e.target).val()
                },
                success: function(data) {
                    if (data.status == true) {
                        $.each(data.cities, function(key, value) {
                            $("#city_select").append(`<option value="` + value.id + `">` +
                                value.city_name + `</option>`);
                        });
                    }
                },
                complete: function() {

                },
                error: function() {}
            });
        });
    }

    function bindAddContactPersonEvent() {
        $("#add_new_contact_person").on("click", function() {
            $('.contact_person_input:last').clone()
                .find("input[type=date]").val("").end()
                .find("input:text,input:hidden").val("").end()
                .insertAfter(".contact_person_input:last")
            /* find("input").each(function () { 
                $(this).rules("add", {
                    required: true
                });
            }) */
            ;
            $(".contact_person_input:last .error").html('');
            /*  $('.contact_person_input:last input').each(function () {
                 //console.log($(this))
                 $(this).rules('add', {
                     required: true
                 });
             }); */

            //$("#client_master_form").validator('update');  
            bindDeleteContactPersonEvent();
            bindInputChangeEvent();
        });
    }

    function bindDeleteContactPersonEvent() {
        $(".delete_contact_person").unbind().on("click", function(e) {
            console.log($(".delete_contact_person").length);
            if ($(".delete_contact_person").length != 1) {
                var id = $(e.target).closest('a').attr('data-id');
                if (id != '') {
                    jQuery.ajax({
                        type: 'POST',
                        url: "{{ url('delete_contact_person') }}",
                        dataType: 'JSON',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(data) {
                            $(e.target).closest('.contact_person_input').remove();
                        },
                        complete: function() {

                        },
                        error: function() {}
                    });
                } else {
                    $(e.target).closest('.contact_person_input').remove();
                }
            } else {
                swal("Error", "You mast have to add atleast one contact person", "error");
            }
        });
    }
    /**
     * validate client
     *
     */
    $("#client_master_form").validate({
        rules: {
            company_name: {
                required: true,
                minlength: 2
            },
            industry_id: {
                required: true
            },
            country_id: {
                required: true
            },
            email: {
                email: true
            },
            phone_no: {
                number: true,
                min: 1
                /* minlength:10,
                maxlength:10 */
            },
            website_name: {
                //url:true
            }
        },
        messages: {
            name: {
                required: "City name is required",
                minlength: " Your city name must consist of at least 2 characters"
            },
            country_id: {
                required: "Select Country",
            },
            industry_id: {
                required: "Select Industry",
            },
            phone_no: {
                min: "Invalid phone number",
            }
        }
    });

    /** 
     *  input change event
     * */
    function bindInputChangeEvent() {
        $("input[name='mobile_no[]'], input[name='phone_no']").keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;

        });

        $("input[name='company_name']").on('change', function() {
            if ($(this).val() == '') {
                $(this).next().html('Company name is required');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='country_id']").on('change', function() {
            if ($(this).val() == '') {
                $(this).next().html('Country is required');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='industry_id']").on('change', function() {
            if ($(this).val() == '') {
                $(this).next().html('Industry name is required');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='cp_name[]']").on('change', function() {
            if ($(this).val() == '') {
                $(this).next().html('Contact person name is required');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='department[]']").on('change', function() {
            if ($(this).val() == '') {
                $(this).next().html('Department name is required');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='mobile_no[]']").on('change', function() {
            /* var err = "mobile_no[]-error";
            if($("#"+err).length > 0) {
                $("#"+err).remove();
            } */
            if ($(this).next().hasClass('error')) {
                $(this).next().remove();
            }
            if ($(this).val() == '') {
                $(this).next().html('Mobile number is required');
                /* } else if ($(this).val().length != 10) {
                    $(this).next().html('Mobile number must have 10 digits'); */
            } else if (isNaN($(this).val())) {
                $(this).next().html('Invalid Mobile number');
            } else if (Math.sign($(this).val()) <= 0) {
                $(this).next().html('Invalid Mobile number');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='cp_email[]']").on('change', function() {
            var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,9}\b$/i;
            if ($(this).val() == '') {
                $(this).next().html('Email is required');
            } else if (!pattern.test($(this).val())) {
                $(this).next().html('Invalid Email');
                flag = false;
            } else {
                $(this).next().html('');
            }
        });

        // $("input[name='dob[]']").on('change', function() {
        //     if ($(this).val() == '') {
        //         $(this).next().html('Birthdate is required');
        //     } else {
        //         $(this).next().html('');
        //     }
        // });

        $("input[name='designation[]']").on('change', function() {
            if ($(this).val() == '') {
                $(this).next().html('Designation is required');
            } else {
                $(this).next().html('');
            }
        });
    }

    /**
     * Save city
     *
     */
    function bindClientValidationEvent() {

        var flag = true;
        $("input[name='cp_name[]']").each(function(i, value) {
            if ($(this).val() == '') {
                $(this).next().html('Contact person name is required').show();
                flag = false;
            }
        });

        $("input[name='department[]']").each(function(i, value) {
            if ($(this).val() == '') {
                $(this).next().html('Department is required');
                flag = false;
            }
        });

        $("input[name='mobile_no[]']").each(function(i, value) {
            if ($(this).val() == '') {
                $(this).next().html('Mobile number is required');
                /* } else if ($(this).val().length != 10) {
                    $(this).next().html('Mobile number must have 10 digits'); */
            } else if (isNaN($(this).val())) {
                $(this).next().html('Invalid Mobile number');
            } else if (Math.sign($(this).val()) <= 0) {
                $(this).next().html('Invalid Mobile number');
            } else {
                $(this).next().html('');
            }
        });

        $("input[name='cp_email[]']").each(function(i, value) {
            var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,9}\b$/i;
            if ($(this).val() == '') {
                $(this).next().html('Email is required');
                flag = false;
            } else if (!pattern.test($(this).val())) {
                $(this).next().html('Invalid Email');
                flag = false;
            }
        });

        // $("input[name='dob[]']").each(function(i, value) {
        //     if ($(this).val() == '') {
        //         $(this).next().html('Birthdate is required');
        //         flag = false;
        //     }
        // });

        $("input[name='designation[]']").each(function(i, value) {
            if ($(this).val() == '') {
                $(this).next().html('Designation is required');
                flag = false;
            }
        });

        if ($("#client_master_form").valid()) {
            if (flag == true) {
                saveClient();
            }
        }
    }

    function saveClient() {
        jQuery.ajax({
            type: 'POST',
            url: "{{ url('save_client') }}",
            dataType: 'JSON',
            data: jQuery("#client_master_form").serialize(),
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
                    jQuery("#manageClientModal").modal('hide');

                    var isBDE = '{{ isset($isBDE) ? $isBDE : 0 }}';
                    if (isBDE == 1) {
                        if (!$.fn.dataTable.isDataTable('#assigned_leads_table')) {
                            initAssignedLeadsTable();
                        } else {
                            jQuery('#assigned_leads_table').DataTable().ajax.reload();
                        }
                    } else {
                        if (!$.fn.dataTable.isDataTable('#client_table')) {
                            initClientTable();
                        } else {
                            jQuery('#client_table').DataTable().ajax.reload();
                        }
                    }

                    swal({
                        title: "Success",
                        text: data.message,
                        icon: "success",
                        timer: 3000
                    });
                } else {
                    swal.close();
                    var error = '';
                    $.each(data.errors, function(key, value) {
                        error += value
                    });
                    swal("Error", error, "error");
                }
            },
            complete: function() {

            },
            error: function() {}
        });
    }
</script>

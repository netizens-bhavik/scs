<div class="modal" id="soft_caller_master_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header container">
                <h5 class="modal-title" id="modalFullTitle">Manage Soft Call Master</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="javascript:void(0);" id="soft_calling_add" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="temp_lead_id" id="temp_lead_id" value="{{ $temp_lead['id'] ?? '' }}">
                        <div class="row">
                            <div class="form-group col-sm-6 mt-3">
                                <span class="text-danger">*</span>
                                <label for="company_name">Company Name</label>
                                <input type="text" class="form-control" name="company_name" id="company_name"
                                       placeholder="Enter Company Name" value="{{ $temp_lead['company_name'] ?? '' }}"
                                       required>
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <span class="text-danger">*</span>
                                <label for="industry_name">Industry Name</label>
                                <select class="form-control form-select" name="industry_id" id="industry_id"
                                        placeholder="Select Industry Name" required>
                                    <option value="">Select Industry Name</option>
                                    @foreach ($all_industries as $item)
                                        @if (count($temp_lead) > 0)
                                            @if ($item['id'] == $temp_lead['industry_id'])
                                                {
                                                <option value="{{ $item['id'] }}" selected>{{ $item['industry_name'] }}
                                                </option>
                                                }
                                            @else
                                                <option value="{{ $item['id'] }}">{{ $item['industry_name'] }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $item['id'] }}">{{ $item['industry_name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="contact_person">Contact Person</label>
                                <input type="text" class="form-control" name="contact_person" id="contact_person"
                                       placeholder="Enter Contact Person Details"
                                       value="{{ $temp_lead['contact_person_name'] ?? '' }}">
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="department">Department</label>
                                <input type="text" class="form-control" name="department" id="department"
                                       placeholder="Enter Department Details" value="{{ $temp_lead['department'] ?? '' }}">
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="designation">Designation</label>
                                <input type="text" class="form-control" name="designation" id="designation"
                                       placeholder="Enter Designation Details" value="{{ $temp_lead['designation'] ?? '' }}">
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="contact_email">Contact Email</label>
                                <input type="email" class="form-control" name="contact_email" id="contact_email"
                                       placeholder="Enter Contact Email Details"
                                       value="{{ $temp_lead['contact_person_email'] ?? '' }}" required>
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <span class="text-danger">*</span>
                                <label for="contact_mobile_number">Mobile Number</label>
                                <input type="text" class="form-control" name="contact_mobile_number"
                                       id="contact_mobile_number" placeholder="Enter Contact Mobile Number"
                                       value="{{ $temp_lead['contact_person_phone'] ?? '' }}" required>
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="dob">Date Of Birth</label>
                                <input type="date" class="form-control" name="dob" id="dob"
                                       placeholder="Enter Date Of Birth" value="{{ $temp_lead['dob'] ?? '' }}">
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="address" id="address" placeholder="Enter Address Details" rows='1'>{{ $temp_lead['address'] ?? '' }}</textarea>
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="pbno">Post Box Number</label>
                                <input type="text" class="form-control" name="pbno" id="pbno"
                                       placeholder="Enter Post Box Number" value="{{ $temp_lead['post_box_no'] ?? '' }}">
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <span class="text-danger">*</span>
                                <label for="country">Country</label>
                                <select class="form-control form-select" name="country_id" id="country_id"
                                        placeholder="Select Country" required>
                                    <option value="">Select Country</option>

                                    @foreach ($all_countries as $item)
                                        @if (count($temp_lead) > 0)
                                            @if ($item['id'] == $temp_lead['company_country_id'])
                                                {
                                                <option value="{{ $item['id'] }}" selected>{{ $item['country_name'] }}
                                                </option>
                                                }
                                            @else
                                                <option value="{{ $item['id'] }}">{{ $item['country_name'] }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $item['id'] }}">{{ $item['country_name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <span class="text-danger">*</span>
                                <label for="city">City</label>
                                <select class="form-control form-select" name="city_id" id="city_id"
                                        placeholder="Select City" required>

                                </select>
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="contact_phone_number">Phone Number</label>
                                <input type="number" class="form-control" name="contact_phone_number"
                                       id="contact_phone_number" value="{{ $temp_lead['company_phone_no'] ?? '' }}"
                                       placeholder="Enter Phone Number">
                            </div>
                            <div class="form-group col-sm-6 mt-3">
                                <label for="website">Website</label>
                                <input type="text" class="form-control" name="website"
                                       value="{{ $temp_lead['website_name'] ?? '' }}" id="website"
                                       placeholder="http://www.example.com">
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary submitForm" title="Submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        bindSoftCallerModalEvents();
        city_check();


        // $("input[name='contact_mobile_number']").keypress(
        //     function(event){
        //         if (event.which == '13') {
        //         event.preventDefault();
        //         }
        // });

        // $("input[name='contact_phone_number']").keypress(
        //     function(event){
        //         if (event.which == '13') {
        //         event.preventDefault();
        //         }
        // });

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
        $("input[name='dob']").attr('max', maxDate);
    });


    function bindSoftCallerModalEvents() {

        $('.submitForm').click(function(e) {
            e.preventDefault();
            bindValidationEvent();
        });
    }

    function city_check() {
        var country_id = $('#country_id').val();
        if (country_id == '') {
            $('#country_id').change(function(e) {
                e.preventDefault();
                var country_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('get_city_by_country_id') }}",
                    data: {
                        country_id: country_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#city_id').html('');
                        $('#city_id').html(response.view);
                    }
                });
            });
        } else {
            var selected_city_id = '{{ $temp_lead['company_city_id'] ?? 0 }}';
            $.ajax({
                type: "POST",
                url: "{{ route('get_city_by_country_id') }}",
                data: {
                    country_id: country_id,
                    selected_city_id: selected_city_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#city_id').html('');
                    $('#city_id').html(response.view);
                }
            });

        }



    }


    function bindValidationEvent() {
        if ($("#soft_calling_add").valid()) {
            formSubmit();
        }
    }
    $("#soft_calling_add").validate({
        rules: {
            company_name: {
                required: true,
            },
            industry_id: {
                required: true,
            },
            contact_mobile_number: {
                required: true,
                digits: true,
                //minlength: 10,
                //maxlength: 10,
            },
            contact_email: {
                required: true,
                email: true,
            },
            contact_phone_number: {
                digits: true,
                //minlength: 10,
                //maxlength: 10,
            },
            country_id: {
                required: true,
            },
            city_id: {
                required: true,
            },
        },
        messages: {
            company_name: {
                required: "Please enter company name",
            },
            industry_id: {
                required: "Please select industry name",
            },
            contact_mobile_number: {
                required: "Please enter contact mobile number",
                digits: "Please enter valid contact mobile number",
                //minlength: "Please enter valid contact mobile number",
                // maxlength: "Please enter valid contact mobile number",
            },
            contact_email: {
                required: "Please enter contact email",
                email: "Please enter valid contact email",
            },
            contact_phone_number: {
                digits: "Please enter valid contact phone number",
                //minlength: "Please enter valid contact phone number",
                // maxlength: "Please enter valid contact phone number",
            },
            country_id: {
                required: "Please select country",
            },
            city_id: {
                required: "Please select city",
            },
        },
    });

    function formSubmit() {
        var formData = new FormData($('#soft_calling_add')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('manage_add_edit_soft_calling') }}",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
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
                        $('#soft_calling_add').trigger("reset");
                        $('#soft_calling_add')[0].reset();
                        jQuery("#soft_caller_master_modal").modal('hide');
                        jQuery('#soft_call_master_table').DataTable()
                            .ajax.reload();
                    });

                } else {
                    swal.close();
                    swal("Error", response.message, "error");
                }
            }
        });
    }
</script>

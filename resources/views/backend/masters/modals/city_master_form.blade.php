{{--  @php
echo "<pre>";    
print_r($city);die;
@endphp  --}}
<div class="modal" id="manageCityModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header container">
                <h5 class="modal-title" id="modalFullTitle">
                    {{-- {{ isset($city['id']) && !empty($city['id']) ? 'Edit City' : 'Add City' }} --}}
                    Manage City
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <form id="city_master_form" class="row g-3" method="POST" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="city_id" value="{{ $city['id'] ?? '' }}">
                    <div class="col-md-12">
                        <span class="text-danger">*</span>
                        <label for="country" class="form-label">Country</label>
                        <select id="country" name="country_id" class="form-select">
                            <option value="">Select Country</option>
                            @foreach ($countries as $value)
                                <option value="{{ $value['id'] }}"
                                    {{ isset($city['country_id']) && $city['country_id'] == $value['id'] ? 'selected' : '' }}>
                                    {{ $value['country_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="offset-md-6"></div>
                    <div class="col-md-12">
                        <span class="text-danger">*</span>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $city['city_name'] ?? '' }}"
                            placeholder="Name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" id="save_city" title="Submit">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        bindCityModalEvents();

        //$('#country').select2();
    });

    /**
     * Bind Modal Events
     *
     */
    function bindCityModalEvents() {
        $("#save_city").on('click', function(e) {
            bindCityValidationEvent();
        });
    }

    /**
     * validate city
     *
     */
    $("#city_master_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            country_id: {
                required: true
            },
        },
        messages: {
            name: {
                required: "City name is required",
                minlength: " Your city name must consist of at least 2 characters"
            },
            country_id: {
                required: "Select counry",
            }
        }
    });

    /**
     * Save city
     *
     */
    function bindCityValidationEvent() {
        if ($("#city_master_form").valid()) {
            saveCity();
        }
    }

    function saveCity() {
        jQuery.ajax({
            type: 'POST',
            url: "{{ url('save_city') }}",
            dataType: 'JSON',
            data: jQuery("#city_master_form").serialize(),
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
                    $('#cityDataTableContainer').show();
                    $('#noCityDataTableContainer').hide();
                    jQuery("#manageCityModal").modal('hide');
                    if (!$.fn.dataTable.isDataTable('#city_table')) {
                        initCityTable();
                    } else {
                        jQuery('#city_table').DataTable().ajax.reload();
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

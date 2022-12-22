<div class="modal" id="manageCountryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header container">
                <h5 class="modal-title" id="modalFullTitle">
                    {{-- {{ isset($country->id) && !empty($country->id) ? 'Edit Country' : 'Add Country' }} --}}
                    Manage Country
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <form id="country_master_form" class="row g-3" method="POST" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="country_id" value="{{ $country->id ?? '' }}">
                    <div class="col-md-12">
                        <span class="text-danger">*</span>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                            value="{{ $country->country_name ?? '' }}" placeholder="Name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" id="save_country" title="Submit">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        bindCountryModalEvents();
    });

    /**
     * Bind Modal Events
     *
     */
    function bindCountryModalEvents() {
        // $('#manageCountryModal').on('shown.bs.modal', function() {
        $("#save_country").on('click', function(e) {
            bindCountryValidationEvent();
        });
        // });
    }

    /**
     * validate Country
     *
     */
    $("#country_master_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
        },
        messages: {
            name: {
                required: "Country name is required",
                minlength: " Your country name must consist of at least 2 characters"
            },
        }
    });

    /**
     * Save country
     *
     */
    function bindCountryValidationEvent() {
        if ($("#country_master_form").valid()) {
            saveCountry();
        }
    }

    function saveCountry() {
        jQuery.ajax({
            type: 'POST',
            url: "{{ url('save_country') }}",
            dataType: 'JSON',
            data: jQuery("#country_master_form").serialize(),
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
                    $('#countryDataTableContainer').show();
                    $('#noCountryDataTableContainer').hide();
                    jQuery("#manageCountryModal").modal('hide');
                    if (!$.fn.dataTable.isDataTable('#country_table')) {
                        initCountryTable();
                    } else {
                        jQuery('#country_table').DataTable().ajax.reload();
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

<div class="modal" id="manageIndustryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header container">
                <h5 class="modal-title" id="modalFullTitle">Manage Industry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <form id="industry_master_form" class="row g-3" method="POST" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="industry_id" value="{{ $industry->id ?? '' }}">
                    <div class="col-md-12">
                        <span class="text-danger">*</span>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                            value="{{ $industry->industry_name ?? '' }}" placeholder="Name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" id="save_industry" title="Submit">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        bindIndustryModalEvents();
    });

    /**
     * Bind Modal Events
     *
     */
    function bindIndustryModalEvents() {
        // $('#manageCountryModal').on('shown.bs.modal', function() {
        $("#save_industry").on('click', function(e) {
            bindIndustryValidationEvent();
        });
        // });
    }

    /**
     * validate Country
     *
     */
    $("#industry_master_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
        },
        messages: {
            name: {
                required: "Industry name is required",
                minlength: " Your Industry name must consist of at least 2 characters"
            },
        }
    });

    /**
     * Save country
     *
     */
    function bindIndustryValidationEvent() {
        if ($("#industry_master_form").valid()) {
            saveIndustry();
        }
    }

    function saveIndustry() {
        jQuery.ajax({
            type: 'POST',
            url: "{{ url('save_industry') }}",
            dataType: 'JSON',
            data: jQuery("#industry_master_form").serialize(),
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
                    $('#industryDataTableContainer').show();
                    $('#noIndustryDataTableContainer').hide();
                    jQuery("#manageIndustryModal").modal('hide');
                    if (!$.fn.dataTable.isDataTable('#industry_table')) {
                        initIndustryTable();
                    } else {
                        jQuery('#industry_table').DataTable().ajax.reload();
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

<div class="modal" id="manageMomModeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header container">
                <h5 class="modal-title" id="modalFullTitle">Manage Mode Of Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <form id="mom_mode_master_form" class="row g-3" method="POST" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="mom_mode_id" value="{{ $mom_mode->id ?? '' }}">
                    <div class="col-md-12">
                        <span class="text-danger">*</span>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                            value="{{ $mom_mode->mode_name ?? '' }}" placeholder="Name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" id="save_mom_mode" title="Submit">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        bindMomModeModalEvents();
    });

    /**
     * Bind Modal Events
     *
     */
    function bindMomModeModalEvents() {
        // $('#manageCountryModal').on('shown.bs.modal', function() {
        $("#save_mom_mode").on('click', function(e) {
            bindMomModeValidationEvent();
        });
        // });
    }

    /**
     * validate Country
     *
     */
    $("#mom_mode_master_form").validate({
        rules: {
            name: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter a Mode Of Meeting",
            },
        }
    });

    /**
     * Save country
     *
     */
    function bindMomModeValidationEvent() {
        if ($("#mom_mode_master_form").valid()) {
            saveMomMode();
        }
    }

    function saveMomMode() {
        jQuery.ajax({
            type: 'POST',
            url: "{{ url('save_mom_mode') }}",
            dataType: 'JSON',
            data: jQuery("#mom_mode_master_form").serialize(),
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
                    jQuery("#manageMomModeModal").modal('hide');
                    if (!$.fn.dataTable.isDataTable('#mom_mode_table')) {
                        initIndustryTable();
                    } else {
                        jQuery('#mom_mode_table').DataTable().ajax.reload();
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

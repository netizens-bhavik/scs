<div class="modal" id="manageNotesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header container">
                <h5 class="modal-title" id="modalFullTitle">Manage Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <form id="notes_master_form" class="row g-3" method="POST" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="notes_id" value="{{ $notes->id ?? '' }}">
                    <div class="col-md-6">
                        <span class="text-danger">*</span>
                        <label for="name" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $notes->title ?? '' }}"
                            placeholder="Title" required>
                    </div>
                    <div class="col-md-6">
                        <span class="text-danger">*</span>
                        <label for="name" class="form-label">Reminder At</label>
                        <input type="datetime-local" class="form-control" name="reminder_at"
                            value="{{ $notes->reminder_at ?? '' }}" placeholder="Reminder At" required>
                    </div>
                    <div class="col-md-12">
                        <span class="text-danger">*</span>
                        <label for="name" class="form-label">Note's Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="10"
                            placeholder="Description" required>{{ $notes->description ?? '' }}</textarea>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" id="save_notes" title="Save">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        bindNotesModalEvents();
    });

    function bindNotesModalEvents() {
        $("#save_notes").on('click', function(e) {

            bindNotesValidationEvent();
        });
    }
    $("#notes_master_form").validate({
        rules: {
            title: {
                required: true,
                minlength: 2
            },
            reminder_at: {
                required: true,
            },
            description: {
                required: true,
                minlength: 2
            },
        },
        messages: {
            title: {
                required: "Title is required",
                minlength: "Title must be at least 2 characters long"
            },
            reminder_at: {
                required: "Reminder At is required",
            },
            description: {
                required: "Description is required",
                minlength: "Description must be at least 2 characters long"
            },
        },
    });

    function bindNotesValidationEvent() {
        if ($("#notes_master_form").valid()) {
            saveNotes();
        }
    }

    function saveNotes() {
        var formData = new FormData($('#notes_master_form')[0]);
        $.ajax({
            url: "{{ url('save_notes') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
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
                        allowEnterKey: true,
                    }).then(function() {
                        $('#manageNotesModal').modal('hide');
                        $('#notes_master_form')[0].reset();
                        $('#notes_table').DataTable().ajax.reload();
                    });
                } else {
                    swal.close();
                    swal({
                        title: "Error",
                        text: data.message,
                        icon: "error",
                        button: "OK",
                        allowEnterKey: true,
                    });
                }
            },
            error: function(data) {
                swal.close();
                swal({
                    title: "Error",
                    text: 'Something went wrong',
                    icon: "error",
                    button: "OK",
                    allowEnterKey: true,
                });
            }
        });
    }
</script>

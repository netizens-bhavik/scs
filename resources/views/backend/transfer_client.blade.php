@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">
                            TRANSFER CLIENTS
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="tranfer_client_form" method="POST" action="javascript:void(0)">
                                @csrf
                                <div class="row my-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="transfer_from_user_id">Transfer From</label>
                                            <select name="transfer_from_user_id" id="transfer_from_user_id"
                                                class="form-control form-select" required>
                                                <option value="">Select User</option>
                                                @foreach ($assign_from as $user)
                                                    <option value="{{ $user['id'] }}">
                                                        {{ ucwords($user['roles'][0]['name'] . '-' . $user['country']['country_name'] . '-' . $user['name']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="transfer_to_user_id">Transfer To</label>
                                            <select name="transfer_to_user_id" id="transfer_to_user_id"
                                                class="form-control form-select" required>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="transfer_clients_list"></div>


                                <div class="col-md-12 text-center mt-3">
                                    <button type="submit" class="btn btn-primary" id="transfer_client_btn" title="Transfer Clients">Transfer
                                        Clients</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $('#transfer_from_user_id').change(function(e) {
            e.preventDefault();

            var transfer_from_user_id = $(this).val();
            if (transfer_from_user_id == '') {
                e.preventDefault();
                $('#transfer_to_user_id').html('');
                $('#transfer_clients_list tbody').html('');
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('get_transfer_to_user') }}",
                    data: {
                        transfer_from_user_id: transfer_from_user_id
                    },
                    dataType: "json",

                    success: function(response) {
                        if (response.status == true) {
                            $('#transfer_to_user_id').html(response.transfer_to);
                            $.ajax({
                                type: "POST",
                                url: "{{ route('get_transfer_clients') }}",
                                data: {
                                    transfer_from_user_id: transfer_from_user_id
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.status == true) {
                                        $('#transfer_clients_list').html(response
                                            .clients);
                                    } else {
                                        $('#transfer_clients_list').html('');
                                    }
                                }
                            });
                        } else {
                            $('#transfer_to_user_id').html('');
                            $('#transfer_clients_list tbody').html('');
                        }
                    }
                });

            }

        });

        $('#tranfer_client_form').submit(function(e) {
            e.preventDefault();

            var formdata = $('#tranfer_client_form').serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('transfer_clients') }}",
                data: formdata,
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
                            jQuery('#transfer_clients_list_table').DataTable()
                                .ajax.reload();
                        });
                    } else {
                        swal.close();
                        swal({
                            title: "Error",
                            text: response.message,
                            icon: "error",
                            button: "OK",
                        }).then(function() {
                            jQuery('#transfer_clients_list_table').DataTable()
                                .ajax.reload();
                        });
                    }
                }
            });
        });
    </script>
@endsection

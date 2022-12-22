<table id="transfer_clients_list_table" class="table table-hover table-bordered">
    <thead>
        <tr>
            <th><input type="checkbox" class="form-check-input transfer_clients_checkbox_all"></th>
            <th>#</th>
            <th>Company Name</th>
            <th>Country Name</th>
            <th>City Name</th>
            <th>Industry Name</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script>
    $(document).ready(function() {
        transfer_from_user_id = $('#transfer_from_user_id').val();
        $('#transfer_clients_list_table').DataTable({
            order: [],
            //pageLength: 25,
            paging: false,
            info: false,
            lengthChange: false,
            processing: true,
            serverSide: true,
            responsive: true,
            orderBy: 'ASC',
            shortable: false,
            orderable: false,
            columnDefs: [{
                orderable: false,
                targets: [-1, 0, 1, 2, 3, 4]
            }],
            columnDefs: [{
                orderable: false,
                width: "5%",
                targets: [0, 1]
            }],

            ajax: {
                url: "{{ url('get_transfer_clients_list') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    transfer_from_user_id: transfer_from_user_id
                },
                dataSrc: function(res) {
                    return res.data;
                }
            },
            columns: [{
                    data: 'checkbox'
                },
                {
                    data: 'id'
                },
                {
                    data: 'company_name'
                },
                {
                    data: 'country_name'
                },
                {
                    data: 'city_name'
                },
                {
                    data: 'industry_name'
                },
            ]
        });

        $('#transfer_clients_list_table').on('click', '.transfer_clients_checkbox_all', function() {
            if ($(this).is(':checked')) {
                $('#transfer_clients_list_table tbody tr').find('input[type="checkbox"]').prop(
                    'checked', true);
            } else {
                $('#transfer_clients_list_table tbody tr').find('input[type="checkbox"]').prop(
                    'checked', false);
            }
        });
    });
</script>

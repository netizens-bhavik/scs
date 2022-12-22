@php
// echo $data['meetingFromDate'];
// echo '<pre>';
// print_r($data);
// echo '</pre>';
@endphp


<table id="report_table" class="table table-bordered display responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>User Name</th>
            <th>Busy</th>
            <th>Call Later</th>
            <th>Called</th>
            <th>Do Not Call Again</th>
            <th>No Requirement</th>
            <th>Not Reachable</th>
            <th>Out OF Service</th>
            <th>Ringing</th>
            <th>Switch Off</th>
            <th>Wrong Number</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    $(document).ready(function() {

        $('#report_table').dataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            info: false,
            searching: false,
            responsive: false,
            scrollX: true,
            columnDefs: [{
                orderable: false,
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
            }],
            lengthChange: false,
            ajax: {
                url: "{{ route('get_call_status_uw_report_data') }}",
                type: 'POST',
                data: function(d) {
                    d.call_status_uw_report_from_date = "{{ $data['from_date'] }}";
                    d.call_status_uw_report_to_date = "{{ $data['to_date'] }}";
                    d.call_status_uw_report_country = "{{ $data['country_id'] }}";
                }
            },
            columns: [
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'busy',
                    name: 'busy'
                },
                {
                    data: 'call_later',
                    name: 'call_later'
                },
                {
                    data: 'called',
                    name: 'called'
                },
                {
                    data: 'do_not_call_again',
                    name: 'do_not_call_again'
                },
                {
                    data: 'no_requirements',
                    name: 'no_requirements'
                },
                {
                    data: 'not_reachable',
                    name: 'not_reachable'
                },
                {
                    data: 'out_of_service',
                    name: 'out_of_service'
                },
                {
                    data: 'ringing',
                    name: 'ringing'
                },
                {
                    data: 'swich_off',
                    name: 'swich_off',

                },
                {
                    data: 'wrong_number',
                    name: 'wrong_number'
                },
                {
                    data: 'total',
                    name: 'total'
                },
            ],
            order: [

            ],
        });
    });
</script>

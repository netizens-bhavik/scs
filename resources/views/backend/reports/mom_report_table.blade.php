@php
// echo $data['meetingFromDate'];
// echo '<pre>';
// print_r($data);
// echo '</pre>';
@endphp

<style>
	table.dataTable tbody tr td:first-child{
		max-width:70px;
		min-width: 70px;
	}
	table.dataTable tbody tr td{
		min-width:150px;
		max-width: 350px;
		white-space: unset !important;
	}
</style>

<table id="mom_report_table" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            {{-- <th>Mid</th> --}}
            <th>Meeting Date</th>
            <th>Country</th>
            <th>Assigned To.</th>
            <th>Company</th>
            <th>Contact Person</th>
            <th>Address</th>
            <th>City</th>
            <th>Phone Number</th>
            <th>Email Address</th>
            <th>Minutes Of Meeting</th>
            <th>BDE Feedback</th>
            <th>FollowUp Date</th>
            <th>Added By.</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    $(document).ready(function() {

        $('#mom_report_table').dataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            info: false,
            responsive: false,
            scrollX: true,
            searching: false,
            columnDefs: [{
                orderable: false,
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
            }],
            lengthChange: false,
            ajax: {
                url: "{{ route('get_mom_report_data') }}",
                type: 'POST',
                data: function(d) {
                    d.mom_report_from_date = "{{ $data['meetingFromDate'] }}";
                    d.mom_report_to_date = "{{ $data['meetingToDate'] }}";
                    d.mom_report_company_name = "{{ $data['companyName'] }}";
                    d.mom_report_country = "{{ $data['country'] }}";
                    d.mom_report_user = "{{ $data['user'] }}";
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                // {
                //     data: 'meeting_id',
                //     name: 'meeting_id'
                // },
                {
                    data: 'meeting_date',
                    name: 'meeting_date'
                },
                {
                    data: 'country_name',
                    name: 'country_name'
                },
                {
                    data: 'assigned_to',
                    name: 'assigned_to'
                },
                {
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    data: 'contact_person',
                    name: 'contact_person'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'city_name',
                    name: 'city_name'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'minutes_of_meeting',
                    name: 'minutes_of_meeting',
					width: '20px'
                },
                {
                    data: 'bde_feedback',
                    name: 'bde_feedback',
					width: '20%'
                },
                {
                    data: 'next_followup_date',
                    name: 'next_followup_date'
                },
                {
                    data: 'added_by_username',
                    name: 'added_by_username'
                },
            ],
            order: [

            ],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

@php
// echo $data['meetingFromDate'];
// echo '<pre>';
// print_r($data);
// echo '</pre>';
@endphp


<table id="report_table" class="table table-bordered display responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Country</th>
            <th>Company Name</th>
            <th>Contact Person</th>
            <th>Address</th>
            <th>City</th>
            <th>Phone No.</th>
            <th>Email</th>
            <th>Industry</th>
            <th>BDE Name</th>
            <th>Last MOM Date</th>
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
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            }],
            lengthChange: false,
            ajax: {
                url: "{{ route('get_client_status_report_data') }}",
                type: 'POST',
                data: function(d) {
                    d.client_status_report_country_id = "{{ $data['country_id'] }}";
                    d.client_status_report_city_id = "{{ $data['city_id'] }}";
                    d.client_status_report_user_id = "{{ $data['user_id'] }}";
                    d.client_status_report_activity_status = "{{ $data['activity_status'] }}";
                }
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'country_name',
                    name: 'country_name'
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
                    data: 'industry_name',
                    name: 'industry_name'
                },
                {
                    data: 'manage_by',
                    name: 'manage_by'
                },
                {
                    data: 'last_mom_date',
                    name: 'last_mom_date',

                },
            ],
            order: [

            ],
        });
    });
</script>

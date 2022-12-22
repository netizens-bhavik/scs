@php
// echo "<pre>"; print_r($temp_leads); echo "</pre>";
@endphp
@if ($temp_leads)
    <table class="table table-striped table-bordered table-hover" id="search_result_tbl">
        <thead>
            <th>#</th>
            <th>Country</th>
            <th>Mobile Number</th>
            <th>Company Name</th>
            <th>Email Id</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach ($temp_leads as $temp_lead)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $temp_lead['country_name'] }}</td>
                    <td>{{ $temp_lead['contact_person_phone'] }}</td>
                    <td>{{ $temp_lead['company_name'] }}</td>
                    <td>{{ $temp_lead['contact_person_email'] }}</td>
                    <td>
                        <a href="{{ url('/get-details/') }}/{{ $temp_lead['id'] }}" class="btn btn-primary" title="View"><i
                                class='bx bxs-chevrons-right'></i></a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@else
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                <strong>Sorry!</strong> No Record Found.
            </div>
        </div>
    </div>
@endif

<script>
    $(document).ready(function() {
        $('#search_result_tbl').DataTable({
            responsive: true,
            searching: false,
            pageLength: 25,
            lengthChange: false,
           // paging: false,
            sortable: false,
            info: false,
            ordering: false,

            "order": [
                [0, "desc"]
            ]
        });
    });
</script>

@extends('backend.dashboard_layouts')

@section('styles')
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">{{ $client_data->company_name }}'s Contact Persons
                        </h4>
                        <div class="text-end">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="contact_person_table" class="table table-bordered display responsive nowrap">
                        <thead>
                            <th>No</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Email</th>
                            <th>Mobile No.</th>
                            <th>Date Of Birth</th>
                        </thead>
                        <tbody>
                            @foreach ($contact_person_list_data as $key => $contact_person)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $contact_person->name }}</td>
                                    <td>{{ $contact_person->department }}</td>
                                    <td>{{ $contact_person->designation }}</td>
                                    <td>{{ $contact_person->email }}</td>
                                    <td>{{ $contact_person->mobile_no }}</td>
                                    <td>{{ date('d/m/Y', strtotime($contact_person->dob)) }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {

            $('#contact_person_table').DataTable({
                "order": [
                ],
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }],
                "info": false,
                "searching": false,
                "columnDefs": [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5,6]
                }],
                "lengthChange": false,
                "paging": false,
            });
        });
    </script>
@endsection

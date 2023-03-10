<style>
    table.dataTable tbody tr td:nth-child(4) {
        max-width: 120px;
        white-space: normal !important;
        word-wrap: break-word !important;
    }
</style>


<div class="modal" id="viewClientsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="modalFullTitle">
                    {{-- {{ isset($city['id']) && !empty($city['id']) ? 'Edit City' : 'Add City' }} --}}
                    Country-Wise Clients : <span class="h6">{{ $filter_data['country_name'] }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    title="Close"></button>
            </div>
            <div class="modal-body">
                <span class="text-danger" id="error_msg"></span>
                <!-- data-table -->
                <div class="col-lg-12 col-md-12 mb-4">
                    <div class="card" style="overflow: auto">
                        {{-- <h5 class="card-header">Manage FollowUps</h5> --}}
                        <div class="text-nowrap tab p-3">
                            <table class="table table table-striped table-bordered"
                                id="country_wise_clients_list_datatable">
                                <thead class="table-bordered">
                                    <tr>
                                        <th>No</th>
                                        <th>Country</th>
                                        <th class="all">Company Name</th>
                                        {{-- <th>Contact Person</th> --}}
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>Phone No.</th>
                                        <th>Email</th>
                                        <th>Industry</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--/ data-table -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Close">
                    Close
                </button>

            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#country_wise_clients_list_datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            info: false,
            searching: false,
            responsive: true,

            columnDefs: [{
                orderable: false,
                targets: [0, 5, 6]
            }],
            lengthChange: false,
            ajax: {
                url: "{{ url('get_country_wise_clients_list') }}",
                type: 'POST',
                data: function(d) {
                    d.country_id = "{{ $filter_data['country_id'] }}";
                    d.data_user_id = "{{ $filter_data['data_user_id'] }}";
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'country_name',
                    name: 'country_name'
                },
                {
                    data: 'company_name',
                    name: 'company_name'
                },
                // {
                //     data: 'contact_person',
                //     name: 'contact_person'
                // },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'city_name',
                    name: 'city_name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'industry_name',
                    name: 'industry_name'
                }
            ],
            order: [],
            fnDrawCallback: function(index, rec) {
                // bindTableEvents();
            }

        });

    });
</script>

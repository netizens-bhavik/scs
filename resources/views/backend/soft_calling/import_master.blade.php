@extends('backend.dashboard_layouts')

@section('styles')
<style>
    .import-master{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    .import-master .choose-file,
    .import-master .download-btn{
        margin-bottom: 20px;
    }
    .import-master .choose-file form{
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
    .import-master .choose-file .choose-input{
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .import-master .choose-file .input-btn{
        margin-bottom: 10px;
    }
</style>
@endsection

@section('main_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="main-heading">
                        <h4 class="card-label" style="">IMPORT MASTER
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="import-master">
                        <div class="choose-file">
                            <form id="data_import_form" method="POST" action="javascript:void(0)"
                            enctype="multipart/form-data">
                                <div class="choose-input">
                                    @csrf
                                    <input type="file" id="lead_file" name="lead_file" class="form-control"
                                        accept=".xls,.xlsx,.csv">
                                </div>
                                <div class="input-btn">
                                    <button type="submit" class="btn btn-primary" id="import_data_btn" title="Import Data"><i
                                        class="bx bx-cloud-upload"></i> Import Data</button>
                                </form>
                                </div>
                            </form>
                        </div>
                        <div class="download-btn">
                            <a href="{{ url('/export_data') }}" class="btn btn-info m-2" id="exportBtn"
                            style="display:{{ Session::get('excelData') && !empty(Session::get('excelData')) ? 'inline-block' : 'none' }}" title="Export Latest File"><i
                                class="bx bx-cloud-download"></i> Download Latest File</a>
                        <a href="{{ asset('public/files/demosampleFile.xlsx') }}" class="btn btn-info" download title="Download Sample File"><i
                                class="bx bx-file-blank"></i> Download Sample File</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            bindImportEvent();
            bindExportEvent();
        });

        function bindImportEvent() {
            $('#data_import_form').on('submit', function(event) {
                event.preventDefault();
                if ($("#lead_file").val() == '') {
                    swal("Error", "Select file to upload", "error");
                } else {
                    $.ajax({
                        url: "{{ url('/import_data') }}",
                        method: "POST",
                        data: new FormData(this),
                        dataType: "JSON",
                        contentType: false,
                        cache: false,
                        processData: false,
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
                            if(data.status == true) {
                                $("#lead_file").val('');
                                $("#exportBtn").show();
                                const wrapper = document.createElement('div');
                                wrapper.innerHTML = data.message;
                                swal({
                                    title: "Success",
                                    content: wrapper,
                                    icon: "success",
                                    timer: 10000
                                });
                            } else {
                                if(data.message) {
                                    swal("Error", data.message, "error");
                                } else if(data.errors) {
                                    var error = '';
                                    $.each(data.errors, function (key, value) {
                                        error += value
                                    });
                                    swal("Error", error, "error");
                                } else {
                                    swal("Error", "Something went wrong.Try again after sometime!", "error");
                                }
                            }
                        }
                    })
                }
            });

        }

        function bindExportEvent() {
            $("#export_data_btn").on('click', function(e) {
                jQuery.ajax({
                    type: 'POST',
                    url: "{{ url('export_data') }}",
                    dataType: 'JSON',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
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
                                timer: 3000
                            });
                        } else {
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
            });
        }
    </script>
@endsection

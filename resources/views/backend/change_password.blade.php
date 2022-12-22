@extends('backend.dashboard_layouts')

@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/css/data-table.css') }}" />

    <style>
        .mainDiv {
            display: flex;
            min-height: 100%;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
            font-family: 'Open Sans', sans-serif;
        }

        .cardStyle {
            width: 500px;
            border-color: white;
            background: #fff;
            padding: 36px 0;
            border-radius: 4px;
            margin: 30px 0;
            box-shadow: 0px 0 2px 0 rgba(0, 0, 0, 0.25);
        }

        #signupLogo {
            max-height: 100px;
            margin: auto;
            display: flex;
            flex-direction: column;
        }

        .formTitle {
            font-weight: 600;
            margin-top: 20px;
            color: #2F2D3B;
            text-align: center;
        }

        .inputLabel {
            font-size: 12px;
            color: #555;
            margin-bottom: 6px;
            margin-top: 24px;
        }

        .inputDiv {
            width: 70%;
            display: flex;
            flex-direction: column;
            margin: auto;
        }

        input {
            height: 40px;
            font-size: 16px;
            border-radius: 4px;
            border: none;
            border: solid 1px #ccc;
            padding: 0 11px;
        }

        input:disabled {
            cursor: not-allowed;
            border: solid 1px #eee;
        }

        .buttonWrapper {
            margin-top: 40px;
        }

        .submitButton {
            width: 70%;
            height: 40px;
            margin: auto;
            display: block;
            color: #fff;
            background-color: #065492;
            border-color: #065492;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12);
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .submitButton:disabled,
        button[disabled] {
            border: 1px solid #cccccc;
            background-color: #cccccc;
            color: #666666;
        }

        #loader {
            position: absolute;
            z-index: 1;
            margin: -2px 0 0 10px;
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #666666;
            width: 14px;
            height: 14px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('main_content')
    <div class="mainDiv">
        <div class="cardStyle">
            <form action="" method="post" name="changePswForm" id="changePswForm">

                <img src="{{ asset('public/assets/img/avatars/1.png') }}" id="signupLogo" style="border-radius:50%" />
                <h6 class="formTitle">
                    {{ Auth::user()->name }}
                </h6>

                <h4 class="formTitle">Change Password</h4>


                <div class="row container">
                    <div class="col-12 mt-2">
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="old_password">Old Password</label>

                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="old_password" class="form-control" name="old_password"
                                    required="" autocomplete="current-password" placeholder="············"
                                    aria-describedby="password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="new_password">New Password</label>

                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="new_password" class="form-control" name="new_password"
                                    required="" placeholder="············" aria-describedby="new_password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="confirm_password">Confirm Password</label>

                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                                    required="" placeholder="············" aria-describedby="confirm_password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="buttonWrapper text-center">
                    <button type="submit" id="submitButton" class="btn btn-primary" title="Change Password">
                        <span>Change Password</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('public/assets/js/data-table.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#changePswForm').on('submit', function(e) {
                e.preventDefault();
                var old_password = $('#old_password').val();
                var new_password = $('#new_password').val();
                var confirm_password = $('#confirm_password').val();
                if (old_password == '') {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Old Password is required!',
                    })
                } else if (new_password == '') {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'New Password is required!',
                    })
                } else if (confirm_password == '') {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Confirm Password is required!',
                    })
                } else if (new_password != confirm_password) {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'New Password and Confirm Password does not match!',
                    })
                } else {
                    $.ajax({
                        url: "{{ route('update_password') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            old_password: old_password,
                            new_password: new_password,
                            confirm_password: confirm_password,
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
                        success: function(response) {
                            if (response.status == true) {
                                swal.close();
                                swal({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    allowEnterKey: true
                                })
                                $('#old_password').val('');
                                $('#new_password').val('');
                                $('#confirm_password').val('');
                            } else {
                                swal.close();
                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                })
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection

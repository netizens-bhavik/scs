<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('public/assets/vendor/libs/jquery/jquery.js') }}"></script>

<!-- validation js -->
<script src="{{ asset('public/assets/js/jquery.validate.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.validate.min.js') }}"></script>

<!-- datatable js -->
<script src="{{ asset('public/assets/js/data-table.js') }}"></script>
<script src="{{ asset('public/assets/js/data-table-responsive.js') }}"></script>

<script src="{{ asset('public/assets/js/jquery.nice-select.js') }}"></script>


<script src="{{ asset('public/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('public/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('public/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('public/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('public/assets/js/main.js') }}"></script>
<script src="{{ asset('public/assets/js/swal.js') }}"></script>
<script src="{{ asset('public/assets/js/select2.min.js') }}"></script>




<!-- Page JS -->
<script src="{{ asset('public/assets/js/dashboards-analytics.js') }}"></script>

<script src="{{ asset('public/assets/js/moment.min.js') }}"></script>
<script src="{{ asset('public/assets/js/daterangepicker.min.js') }}"></script>


{{--
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

<!-- Place this tag in your head or just before your close body tag. -->
{{-- <script async defer src="https://buttons.github.io/buttons.js"></script> --}}
<script>
    $(document).ready(function() {

        // jQuery(document).bind("ajaxStart.mine", function() {
        //     $("body").addClass("loading");
        // });

        // jQuery(document).bind("ajaxStop.mine", function() {
        //     $("body").removeClass("loading");
        // });




        var page = "{{ $selected_menu ?? '' }}";
        var main_url = "{{ url('/') }}";
        if (page != '') {
            $('.menu-item').each(function(index) {
                var href = $(this).find('a').attr('href');
                //console.log(href);
                if (href == main_url + '/' + page) {
                    $(this).addClass('active');
                    if ($(this).parent().parent().hasClass('menu-item')) {
                        $(this).parent().parent().addClass('active open');
                    }
                }
            });
        }

        // $.ajaxSetup({
        //     statusCode: {
        //         401: function() {
        //             window.location.href = "{{ route('login') }}";
        //         }
        //         403: function() {
        //             window.location.href = "{{ route('dashboard') }}";
        //         }
        //     }
        // });

        $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
            if (jqxhr.status == 401) {
                window.location.href = "{{ route('login') }}";
            }
            if (jqxhr.status == 403) {
                window.location.href = "{{ route('dashboard') }}";
            }
        });
    });
</script>


<script>
    $(".layout-menu-toggle").click(function() {
        $("html").toggleClass("layout-menu-expanded");
    });
    $(".layout-menu-toggle.menu-link").click(function() {
        $("html").removeClass("layout-menu-expanded");
    });
</script>

{{-- <script>
    $(document).ready(function() {

    });
</script> --}}

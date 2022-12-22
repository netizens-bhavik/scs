<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('public/assets/') }}" data-template="vertical-menu-template-free">

@include('backend.general_includes.header')


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        
        <div class="layout-container">
            <!-- Menu -->

            @include('backend.general_includes.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('backend.general_includes.top_menu')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-fluid flex-grow-1 pt-2">

                        @yield('main_content')

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('backend.general_includes.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    @include('backend.general_includes.scripts')

    @yield('scripts')
</body>

</html>

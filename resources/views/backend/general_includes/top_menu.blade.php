<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->

        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->


            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow d-flex" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('public/assets/img/avatars/1.png') }}" alt
                            class="w-px-30 h-auto rounded-circle" />
                    </div>
                    {{-- <span class="ms-1 d-none d-xl-block text-light">admin</span>
                    <span class="ms-1 h5 mb-0 d-none d-xl-block">{{ Auth::user()->name }}</span> --}}
                    {{-- <div class="flex-grow-1">
                        <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                        <small class="text-muted">{{ Auth::user()->roles[0]->name }}</small>
                        
                    </div> --}}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="#" style="cursor: default;">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="{{ asset('public/assets/img/avatars/1.png') }}" alt
                                    class="w-px-30 h-auto rounded-circle" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                <small class="text-muted">{{ ucwords(Auth::user()->roles[0]->name) }}</small>
                              </div>
                            </div>
                          </a>

                     <li>
                        <a class="dropdown-item" href="{{url('/change_password')}}">
                            <i class="bx bx-lock-alt me-2"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li> 
                    
                    <li>
                    <a class="dropdown-item" href="{{url('/logout')}}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

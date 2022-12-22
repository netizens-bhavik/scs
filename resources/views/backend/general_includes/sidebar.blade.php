<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('public/assets/img/logo.png') }}" alt="logo" width="185px" class="logo-default" />
            </span>
            {{-- <span class="app-brand-text demo menu-text fw-bolder text-uppercase ms-2">SoftCall</span> --}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    {{-- <div class="menu-inner-shadow"></div> --}}

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item" id="dashboard">
            <a href="{{ url('/dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>



        {{-- <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manage Masters</span>
            </li> --}}
        @canany(['user_view', 'user_add', 'user_edit', 'user_delete', 'client_view', 'client_add', 'client_edit',
            'client_delete', 'country_view', 'country_add', 'country_edit', 'country_delete', 'city_view', 'city_add',
            'city_edit', 'city_delete', 'industry_view', 'industry_add', 'industry_edit', 'industry_delete'])
            <li class="menu-item" id="main_menu_container">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class='bx bx-merge'></i> --}}
                    <i class="menu-icon tf-icons bx bx-merge"></i>
                    <div data-i18n="Account Settings">Masters</div>
                </a>
                <ul class="menu-sub">
                    @canany(['user_view', 'user_add', 'user_edit', 'user_delete'])
                        <li class="menu-item" id="users">
                            <a href="{{ url('/users') }}" class="menu-link">
                                <div data-i18n="Account">User Master</div>
                            </a>
                        </li>
                    @endcanany
                    @canany(['client_view', 'client_add', 'client_edit', 'client_delete'])
                        <li class="menu-item">
                            <a href="{{ url('/clients') }}" class="menu-link">
                                <div data-i18n="Notifications">Client Master</div>
                            </a>
                        </li>
                    @endcanany
                    @canany(['country_view', 'country_add', 'country_edit', 'country_delete'])
                        <li class="menu-item">
                            <a href="{{ url('/countries') }}" class="menu-link">
                                <div data-i18n="Connections">Country Master</div>
                            </a>
                        </li>
                    @endcanany
                    @canany(['city_view', 'city_add', 'city_edit', 'city_delete'])
                        <li class="menu-item">
                            <a href="{{ url('/cities') }}" class="menu-link">
                                <div data-i18n="Connections">City Master</div>
                            </a>
                        </li>
                    @endcanany
                    @canany(['industry_view', 'industry_add', 'industry_edit', 'industry_delete'])
                        <li class="menu-item">
                            <a href="{{ url('/industries') }}" class="menu-link">
                                <div data-i18n="Connections">Industries Master</div>
                            </a>
                        </li>
                    @endcanany

                    @canany(['mom_mode_view', 'mom_mode_add', 'mom_mode_edit', 'mom_mode_delete'])
                        <li class="menu-item">
                            <a href="{{ url('/mom_modes') }}" class="menu-link">
                                <div data-i18n="Connections">MOM Modes</div>
                            </a>
                        </li>
                    @endcanany

                </ul>
            </li>
        @endcanany


        @canany(['soft_call_upload', 'soft_call_add', 'soft_call_view', 'soft_call_edit', 'soft_call_delete',
            'soft_call_incoming', 'soft_call_outgoing'])

            <li class="menu-item" id="main_menu_container">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class='bx bx-phone-call'></i> --}}
                    <i class="menu-icon tf-icons bx bx-phone-call"></i>
                    <div data-i18n="Account Settings">Soft Calling</div>
                </a>
                <ul class="menu-sub">
                    @can('soft_call_upload')
                        <li class="menu-item" id="users">
                            <a href="{{ url('/import') }}" class="menu-link">
                                <div data-i18n="Account">Soft Calling Upload</div>
                            </a>
                        </li>
                    @endcan
                    @canany(['soft_call_add', 'soft_call_view', 'soft_call_edit', 'soft_call_delete'])
                        <li class="menu-item">
                            <a href="{{ url('/add_soft_call') }}" class="menu-link">
                                <div data-i18n="Notifications">Soft Calling Add</div>
                            </a>
                        </li>
                    @endcanany
                    @can('soft_call_incoming')
                        <li class="menu-item">
                            <a href="{{ url('/incoming_dashboard') }}" class="menu-link">
                                <div data-i18n="Connections">Incoming Dashboard</div>
                            </a>
                        </li>
                    @endcan
                    @can('soft_call_outgoing')
                        <li class="menu-item">
                            <a href="{{ url('/outgoing_dashboard') }}" class="menu-link">
                                <div data-i18n="Connections">Outgoing Dashboard</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>

        @endcanany



        @can('soft_call_assign')
            <li class="menu-item">
                <a href="{{ url('/assign_leads') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-plus"></i>
                    <div data-i18n="Account Settings">Assign Leads</div>

                </a>
            </li>
        @endcan

        @can('soft_call_view_assigned_leads')
            <li class="menu-item">
                <a href="{{ url('/view_assinged_leads') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-account"></i>
                    <div data-i18n="Account Settings">New Leads</div>
                </a>
            </li>
        @endcan

        @canany(['mom_add', 'mom_view', 'mom_edit', 'mom_delete'])
            <li class="menu-item">
                <a href="{{ url('/moms') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-timer"></i>
                    <div data-i18n="Account Settings">MOM</div>
                </a>
            </li>
        @endcanany

        @can('transfer_lead')
            <li class="menu-item">
                <a href="{{ url('/transfer_client') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-transfer"></i>
                    <div data-i18n="Account Settings">Transfer Client</div>
                </a>
            </li>
        @endcan

        @can('mom_job_status')
            <li class="menu-item">
                <a href="{{ url('/clinet_jobs') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-task"></i>
                    <div data-i18n="Account Settings">Job Status</div>
                </a>
            </li>
        @endcan

        @can('manage_notes')
            <li class="menu-item">
                <a href="{{ url('/notes') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-note"></i>
                    <div data-i18n="Account Settings">Notes</div>
                </a>
            </li>
        @endcan

        @canany(['manage_report', 'mom_report'])
            <li class="menu-item" id="main_menu_container">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-report"></i>
                    <div data-i18n="Account Settings">Reports</div>
                </a>

                <ul class="menu-sub">
                    @can('mom_report')
                        <li class="menu-item" id="users">
                            <a href="{{ url('/mom_report') }}" class="menu-link">
                                <div data-i18n="Account">MOM Report</div>
                            </a>
                        </li>
                    @endcan

                    @can('call_status_report')
                        <li class="menu-item" id="users">
                            <a href="{{ url('/call_status_report') }}" class="menu-link">
                                <div data-i18n="Account">Call Status Report</div>
                            </a>
                        </li>
                    @endcan

                    @can('call_status_uw_report')
                        <li class="menu-item" id="users">
                            <a href="{{ url('/call_status_uw_report') }}" class="menu-link">
                                <div data-i18n="Account">Call Status (UW) Report</div>
                            </a>
                        </li>
                    @endcan
                    @can('client_status_report')
                        <li class="menu-item" id="users">
                            <a href="{{ url('/client_report') }}" class="menu-link">
                                <div data-i18n="Account">Client Report</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

    </ul>
</aside>

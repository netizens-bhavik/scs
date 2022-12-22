<!-- Icons. Uncomment required icon fonts -->
<link rel="stylesheet" href="{{ asset('public/assets/vendor/fonts/boxicons.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('public/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('public/assets/vendor/css/theme-default.css') }}"
      class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('public/assets/css/demo.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/css/nice-select.css') }}" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

<link rel="stylesheet" href="{{ asset('public/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

<!-- Page CSS -->

<!-- Datatables CSS CDN -->
<link rel="stylesheet" href="{{ asset('public/assets/css/data-table.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/css/data-table-responsive.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/css/select2.min.css') }}" />

<link rel="stylesheet" href="{{ asset('public/assets/css/daterangepicker.css') }}" />

<!-- Helpers -->
<script src="{{ asset('public/assets/vendor/js/helpers.js') }}"></script>

<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('public/assets/js/config.js') }}"></script>

<style>

    
    table th {
        text-transform: uppercase !important;
    }

    .dataTables_wrapper {
        min-height: 260px
    }

    .dataTables_filter {
        float: left !important;
        margin-bottom: 10px !important;
    }

    .table.dataTable {
        border-collapse: collapse;
    }

    .table {
        border: #e3e7eb;
    }

    .table.dataTable.no-footer {
        border-bottom: #e3e7eb;
    }

    .table th {
        background: #eee;
        font-size: 0.8rem;
    }

    .dataTables_paginate {
        margin-top: 25px
    }

    .dataTables_filter label {
        font-weight: bold
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 2px 5px;
        margin-left: 10px;
        border: 1px solid #d9dee3;
        background: #fff;
        transition: 0.3s all;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #ed2024;
    }
</style>

<style>
    .layout-menu-fixed:not(.layout-menu-collapsed) .layout-page,
    .layout-menu-fixed-offcanvas:not(.layout-menu-collapsed) .layout-page {
        padding-left: 0;
    }

    .layout-menu-expanded .layout-menu {
        transform: translate3d(0, 0, 0) !important;
    }

    .layout-menu {
        position: fixed !important;
        top: 0 !important;
        height: 100% !important;
        left: 0 !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        transform: translate3d(-110%, 0, 0);
        will-change: transform, -webkit-transform;
        transition: 0.5s all !important;
        z-index: 1100 !important;
    }

    .layout-menu-expanded .layout-overlay {
        position: fixed;
        top: 0;
        right: 0;
        height: 100% !important;
        left: 0;
        display: block;
        background: #435971;
        opacity: 0.5;
        cursor: pointer;
        z-index: 1099;
    }

    .layout-menu-expanded body {
        overflow: hidden;
    }
</style>

<style>
    .permissions-check-listing {
        display: flex;
        flex-wrap: wrap
    }

    .permissions-check-listing .permissions-check-click {
        margin-right: 10px;
    }

    .permissions-check-listing .permissions-check-click:last-child {
        margin-right: 0px;
    }

    @media(max-width: 575px) {
        .modal-body {
            padding: 20px 5px;
        }

        .permission-listing-table tbody td {
            font-size: 14px;
        }
    }
</style>

<style>
    .main-heading {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        padding: 8px 20px;
        background-color: rgba(105, 108, 255, .16) !important;
        border-radius: 8px;
        margin-bottom: 0;
        border-left: 4px solid #ed2024;
    }

    .main-heading h4 {
        margin-bottom: 0;
        font-size: 16px;
        padding: 7px 0;
    }

    .main-heading a {
        display: block;
        line-height: 0;
    }

    .main-heading a span {
        font-size: 16px;
    }

    @media(max-width: 767px) {
        .main-heading h4 {
            font-size: 15px;
        }
    }

    @media(max-width: 480px) {
        .main-heading {
            display: block;
        }

        .main-heading h4 {
            font-size: 14px;
        }
    }
</style>

<style>
    .app-card {
        background-color: #f1f1f1;
        border-radius: 4px;
        display: flex;
        align-items: center;
        padding: 5px;
        margin-bottom: 10px;
        border: 1px solid #e4e4e4;
    }

    .app-card .icon {
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 0;
        margin-right: 5px;
        min-width: 30px;
        height: 30px;
        background-color: #ed2024;
    }

    .app-card .content h5 {
        font-size: 13px;
        font-weight: 400;
        margin-bottom: 0;
        color: #000;
    }

    .app-card .content h6 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 0;
        color: #000;
        word-break: break-word;
    }

    @media(max-width: 1699px) {
        .app-card .content h6 {
            font-size: 16px;
        }
    }

    @media(max-width: 991px) {
        .main-details {
            margin-bottom: 20px;
        }
    }


    @media(max-width: 991px) {
        .card-body {
            padding: 15px;
        }
    }

    @media(max-width: 575px) {
        .app-card .content h5 {
            font-size: 13px;
        }

        .app-card .content h6 {
            font-size: 15px;
        }
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        transition: 0.3s all;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: rgba(105, 108, 255, .16) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ed2024;
        border: 1px solid #ed2024;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 3px 10px;
    }

    div.dataTables_processing>div:last-child>div {
        background-color: #ed2024;
    }

    .swal-icon:first-child {
        margin-top: 20px;
    }

    .swal-title {
        padding: 0 15px;
    }

    .swal-footer {
        margin-top: 0;
    }

    /* .swal-icon{
    margin: 10px auto;
    width: 50px;
    height: 50px;
}
.swal-icon--warning__body{
    height: 25px;
}
.swal-icon--error__line{
    width: 25px;
    top: 23px;
}
.swal-icon--error__line--right{
    right: 12px;
}
.swal-icon--error__line--left{
    left: 13px;
}
.swal-button{
    padding: 8px 15px;
}
.swal-icon--success__ring{
    width: 50px;
    height: 50px;
} */
    .modal-dialog {
        transition: 0.3s all;
    }

    .followups .card {
        height: 100%;
        border: 1px solid transparent;
    }

    .followups .card.active {
        height: 100%;
        border: 1px solid #ed2024;
    }

    .followups .card .card-title {
        color: #000;
        margin-right: 10px;
        margin-bottom: 0;
    }

    .followups .card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        flex-wrap: wrap;
    }

    .followups .card .card-body .count {
        padding: 10px;
        border-radius: 4px;
        background: #e7e7ff;
        color: #ed2024;
    }

    @media(max-width: 1399px) {
        .followups .card .card-body {
            flex-direction: column;
        }

        .followups .card .card-title {
            margin-right: 0;
            margin-bottom: 10px;
            text-align: center
        }
    }

    @media(max-width: 1199px) {

        .followups .card-title h5 {
            font-size: 16px;
        }
    }

    @media(max-width: 991px) {

        .followups .card-title h5 {
            font-size: 15px;
        }
    }
</style>

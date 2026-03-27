<!doctype html>
<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default"
    data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template" data-bs-theme="light">

<!-- Mirrored from demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 Aug 2025 15:41:02 GMT -->

<head>
    <meta charset="utf-8" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>ITMS | {{ isset($title_top) ? $title_top : '' }}</title>

    <meta name="robots" content="noindex, nofollow" />

    <!-- Canonical SEO -->
    <meta name="description"
        content="Sneat is the best bootstrap 5 dashboard for responsive web apps. Streamline your app development process with ease." />

    <meta name="keywords"
        content="Sneat bootstrap dashboard, sneat bootstrap 5 dashboard, themeselection, html dashboard, web dashboard, frontend dashboard, responsive bootstrap theme" />
    <meta property="og:title" content="Sneat Bootstrap 5 Dashboard PRO by ThemeSelection" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/" />
    <meta property="og:image"
        content="../../../../themeselection.com/wp-content/uploads/edd/2024/08/sneat-dashboard-pro-bootstrap-smm-image.png" />
    <meta property="og:description"
        content="Sneat is the best bootstrap 5 dashboard for responsive web apps. Streamline your app development process with ease." />
    <meta property="og:site_name" content="ThemeSelection" />
    <link rel="canonical" href="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/" />




    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->


    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />


    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->


    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }}"></script>



    {{-- DATATABLE TEMPLATE --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-select-bs5/select.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css') }}" />

    {{-- SELECT2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

    {{-- SWEETALERT --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />


    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/loader/loader.css') }}">
</head>

<body>
    <div class="loader"></div>
    <!-- Main Layout Wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Sidebar / Left Menu -->
            @include('web.template.leftmenu')

            <!-- Mobile Menu Toggle -->
            <div class="menu-mobile-toggler d-xl-none rounded-1">
                <a href="javascript:void(0);"
                    class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                    <i class="bx bx-menu icon-base"></i>
                    <i class="bx bx-chevron-right icon-base"></i>
                </a>
            </div>

            <!-- Main Page Content -->
            <div class="layout-page">
                <!-- Header -->
                @include('web.template.header')
                <!-- Content Wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">

                                @foreach($breadcrumbs as $breadcrumb)

                                <li class="breadcrumb-item">

                                    @if($loop->last)
                                    {{ $breadcrumb->menu_name }}
                                    @else
                                    <a href="{{ $breadcrumb->url }}">
                                        {{ $breadcrumb->menu_name }}
                                    </a>
                                    @endif

                                </li>

                                @endforeach

                            </ol>
                        </nav>
                        {!! $view_file !!}
                    </div>
                    <!-- Footer -->
                    @include('web.template.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <!-- Layout Overlay & Drag Target (for sidebar interaction) -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>



    <script src="{{ asset('assets/utils/url.js') }}"></script>
    <script src="{{ asset('assets/utils/message.js') }}"></script>
    <script src="{{ asset('assets/utils/validation.js') }}"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="{{ asset('assets/utils/template.js') }}"></script>


    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bloodhound/bloodhound.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    {{-- TAMBAHAN DATATABLE --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css"
        rel="stylesheet" />
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js">
    </script>

    <!-- DataTables Buttons + Export -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <!-- ColReorder + Resizable Columns -->
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/2.0.2/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/colreorder/2.0.2/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.min.js"></script>

    <!-- StateSave (ingat posisi & lebar kolom) -->
    <script src="https://cdn.datatables.net/stateSave/1.2.0/js/dataTables.stateSave.min.js"></script>

    {{-- SWEETALERT --}}
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    {{-- UI PAGE --}}
    <script src="{{ asset('assets/js/ui-popover.js') }}"></script>

    <script src="{{ asset('assets/js/controllers/auth.js') }}"></script>
    <script src="{{ asset('assets/js/controllers/token.js') }}"></script>


    @if (isset($header_data))
    @php
    $version = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
    @endphp
    @foreach ($header_data as $key => $v_head)
    @php
    $data_key = explode('-', $key);
    @endphp
    @if ($data_key[0] == 'js')
    <script src="{{ $v_head }}?v={{ $version }}"></script>
    @endif
    @endforeach
    @endif


    @yield('scripts')
</body>

</html>
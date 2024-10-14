<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-bs-theme="{{ $themeMode }}" data-body-image="img-1" data-preloader="enable" data-sidebar-visibility="show" data-layout-style="default" data-layout-width="fluid" data-layout-position="fixed">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} | {{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">
    <!--datatable css-->
    <link rel="stylesheet" href="{{ asset('admin/datatables/1.11.5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/datatables/responsive/2.2.9/css/responsive.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/datatables/buttons/2.2.2/css/buttons.dataTables.min.css') }}">
    <link href="{{ asset('admin/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('admin/js/layout.js') }}"></script>
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('admin/css/all.min.css') }}" />

    @stack('styles')
</head>

<body>

    <div id="layout-wrapper">
        <x-admin.header />


        <x-admin.sidebar />


        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">


                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                                <h4 class="mb-sm-0">{{ $heading }}</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('home') }}">@lang('menu.home_page')</a>
                                        </li>
                                        <li class="breadcrumb-item {{ isset($subheading) ? '' : 'active' }}">
                                            {{ $heading }}
                                        </li>
                                        @if(isset($subheading))
                                            <li class="breadcrumb-item active">
                                                {{ $subheading }}
                                            </li>
                                        @endif
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{ $slot }}


                </div>
            </div>

            <x-admin.footer />
        </div>
    </div>



    <button onclick="topFunction()" class="btn btn-primary btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>



    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>



    <!-- JAVASCRIPT -->
    <script src="{{ asset('admin/js/jquery.min.js') }}" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('admin/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('admin/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('admin/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('admin/js/pages/dashboard-analytics.init.js') }}"></script>
    <script src="{{ asset('admin/js/app.js') }}"></script>
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/js/pages/select2.init.js') }}"></script>
    <script src="{{ asset('admin/js/sweetalert.min.js') }}" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--datatable js-->
    <script src="{{ asset('admin/datatables/1.11.5/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/datatables/1.11.5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin/datatables/responsive/2.2.9/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/datatables/buttons/2.2.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/datatables/buttons/2.2.2/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/datatables/buttons/2.2.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/datatables/ajax/libs/pdfmake/0.1.53/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/datatables/ajax/libs/pdfmake/0.1.53/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/datatables/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/js/pages/datatables.init.js') }}"></script>
</body>

{{-- AddForm n EditForm Open/Close jquery --}}
<script>
    $(document).ready(function() {

        $("#btnCancel").click(function() {
            $("#addContainer").slideUp();
            $("#editContainer").slideUp();
            $(this).hide();
            $("#addToTable").show();
        });
    });

    $(document).ready(function() {
        $("#addToTable").click(function(e) {
            e.preventDefault();
            // var id = $(this).attr('data-id');
            $("#addContainer").slideDown();
            $("#editContainer").slideUp();
            $("#btnCancel").show();

        });
    });
</script>

{{-- Add / Update Form validation --}}
<script>
    function resetErrors() {
        var form = document.getElementById('addForm');
        if(form)
        {
            var data = new FormData(form);
            for (var [key, value] of data) {
                var field = key.replace('[]', '');

                $('.' + field + '_err').text('');
                $("[name='"+field+"']").removeClass('is-invalid');
                $("[name='"+field+"']").addClass('is-valid');
                $("[id='"+field+"']").removeClass('is-invalid');
                $("[id='"+field+"']").addClass('is-valid');
            }
        }

        var form = document.getElementById('editForm');
        if(form)
        {
            var data = new FormData(form);
            for (var [key, value] of data) {
                var field = key.replace('[]', '');
                $('.' + field + '_err').text('');
                $("[name='"+field+"']").removeClass('is-invalid');
                $("[name='"+field+"']").addClass('is-valid');
            }
        }
    }

    function printErrMsg(msg) {
        $.each(msg, function(key, value) {
            var field = key.replace('[]', '');
            field = field.replace('.', '\\.');

            $('.' + field + '_err').text(value);
            $("[name='"+field+"']").addClass('is-invalid');
            $("[name='"+field+"']").removeClass('is-valid');
            $("[id='"+field+"']").addClass('is-invalid');
            $("[id='"+field+"']").removeClass('is-valid');
        });
    }

    function editFormBehaviour() {
        $("#addContainer").slideUp();
        $("#btnCancel").show();
        $("#addToTable").hide();
        $("#editContainer").slideDown();
        $("html, body").animate({ scrollTop: 0 }, "slow");
    }
</script>


@stack('scripts')

</html>

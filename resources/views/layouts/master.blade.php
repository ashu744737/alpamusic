<!DOCTYPE html>
@if (App::isLocale('hr'))
<html lang="he">
@else
<html lang="en">
@endif

<head>

    <meta charset="utf-8" />
    {{-- <title> @yield('title')  | {{ config('constants.app_title') }}</title> --}}
    <title> @yield('title')  | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{trans('general.site_description')}}" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('/images/favicon.ico')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    @if (App::isLocale('hr'))
    <!-- App Rtl Css-->
    <link href="{{ URL::asset('/css/app-rtl.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    @else
    <!-- App Css-->
    <link href="{{ URL::asset('/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    @endif
    <!-- Custom Css-->
    <link href="{{ URL::asset('/css/custom.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- headerCss -->
    @yield('headerCss')
    <style>
        @media (max-width: 425px) {
            .navbar-brand-box {
                display: none !important;
            }
        }
    </style>
</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

         @include('layouts/partials/header')

         @include('layouts/partials/sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                  <!-- content -->
                   @yield('content')


                  @include('layouts/partials/footer')

                </div>
                <!-- end main content-->
            </div>
            <!-- END layout-wrapper -->

             @include('layouts/partials/rightbar')
   
            <!-- JAVASCRIPT -->
            <script src="{{ URL::asset('/libs/jquery/jquery.min.js')}}"></script>
            <script src="{{ URL::asset('/libs/bootstrap/bootstrap.min.js')}}"></script>
            <script src="{{ URL::asset('/libs/metismenu/metismenu.min.js')}}"></script>
            <script src="{{ URL::asset('/libs/simplebar/simplebar.min.js')}}"></script>
            <script src="{{ URL::asset('/libs/node-waves/node-waves.min.js')}}"></script>
            <script src="{{ URL::asset('/libs/jquery-sparkline/jquery-sparkline.min.js')}}"></script>
            <script>
                ajax_call();
                function ajax_call(){
                    $.ajax({
                        type: "GET",
                        url:"{{ route('user.getnotification') }}",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            // console.log(data,'data')
                            if(data.status == 1){
                                $('#notificationHTML').html(data.html);   
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                        console.log("XHR",xhr);
                        console.log("status",textStatus);
                        console.log("Error in",errorThrown);
                        }
                    });
                };
                const interval = setInterval(function() {
                    if(!($('#notification-messages').hasClass('show'))){
                        ajax_call();
                    }
                }, 10000);

                function openDropdown() {
                    if($('#notification-messages').hasClass('show')){
                        //$('#notification-messages').toggleClass('open');
                        $('#notification-messages').removeClass('show');
                    } else {
                        $('#notification-messages').toggleClass('open');
                        $('#notification-messages').addClass('show');
                    }
                }

                function closeDropdown(){
                    if($('#notification-messages').hasClass('show')){
                        //$('#notification-messages').toggleClass('open');
                        $('#notification-messages').removeClass('show');
                    }
                }

                $('.vertical-menu').on('click', function(){
                    closeDropdown();
                });
                $('.main-content').on('click', function(){
                    closeDropdown();
                });

                function readNotifications(id, link) {
                    if(link != 'read'){
                        location.href = link;
                    }
                    if(link=='read'){
                        $('.'+id).addClass('d-none');
                            $.ajax({
                            url: '/read-notification/'+id,
                            type: "GET",
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function( response ) {
                                $(`#notification_${id}`).remove();
                                let count = $('#notificationCount').html();
                                if(count > 0){
                                    count = count - 1;
                                    $('#notificationCount').html(count);
                                    let notificationStr = `{{ trans('content.notificationdata.notifications') }} (${count})`
                                    $('#notification_label_count').html(notificationStr);
                                }
                                // ajax_call();
                            }
                        });
                    }
                }
            </script>
            <!-- footerScript -->
             @yield('footerScript')

            <!-- App js -->
            @if (App::isLocale('hr'))
            <script src="{{ URL::asset('/js/apphr.min.js')}}"></script>
            @else
            <script src="{{ URL::asset('/js/app.min.js')}}"></script>
            @endif
</body>

</html>
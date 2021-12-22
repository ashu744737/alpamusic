<!DOCTYPE html>
@if (App::isLocale('hr'))
<html lang="he">
@else
<html lang="en">
@endif

<head>
    <meta charset="utf-8" />
    <title> @yield('title')  | {{ config('constants.app_title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{trans('general.site_description')}}" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('/images/favicon.ico')}}"> 
    
    <!-- headerCss -->
    @yield('headerCss')

    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('/css/custom.css')}}" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>

    @yield('content')
    
  
    <!-- JAVASCRIPT -->
    <script src="{{ URL::asset('/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/metismenu/metismenu.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/node-waves/node-waves.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/jquery-sparkline/jquery-sparkline.min.js')}}"></script>
    <!-- App js -->
    @if (App::isLocale('hr'))
    <script src="{{ URL::asset('/js/apphr.min.js')}}"></script>
    <script src='https://www.google.com/recaptcha/api.js?hl=iw' async defer></script>
    @else
    <script src="{{ URL::asset('/js/app.min.js')}}"></script>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    @endif
   
     @yield('page-js')
</body>

</html>
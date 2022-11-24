<!DOCTYPE html>
<html lang="en" data-theme="light">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-touch-fullscreen" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="default" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Auto Damage Inspection &#8212; The Next Generation Car Scanner</title>
        <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}" />
        <link rel="icon" href="{{asset('assets/img/logo.png')}}" type="image/png" sizes="16x16" />
        <!--PAGE-->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/js/jquery-scrollbar/jquery.scrollbar.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/js/jquery-ui/jquery-ui.min.css')}}" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600" rel="stylesheet" />
        <!--Material Icons-->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/materialdesignicons/materialdesignicons.min.css')}}" />
        <!--Page Specific CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/js/select2/css/select2.css')}}" />
        <!--Hci Admin CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/hci.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/js/datatablesbutton/css/buttons.dataTables.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/js/DataTables/datatables.min.css')}}">
        @yield('css')
        <!-- Additional library for page -->
    </head>
    <!--body with default sidebar pinned -->
   
    @if(auth()->user())
    <body class="sidebar-pinned">

        @include('partial.sidebar')
        <!--sidebar Ends-->
        <main class="admin-main">
            <!--site header begins-->
            @include('partial.header')
            <!--site header ends -->
            <section class="admin-content">
                @yield('content')
            </section>
        </main>
        @include('partial.footer')
        @yield('script')
    </body>
    @else
        <body>
            @yield('content')
            @include('partial.footer')
            @yield('script')
        </body>
    @endif
        <!--sidebar Begins-->
        <!--Page Specific JS-->
        <!--page specific scripts for demo-->
</html>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
    <title>Employee Management System</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body >
<div class="header_container">
    <div class="container-fluid pl-4 pr-4">
        <div class="flex_items">
            <div class="flex_items">
                <div class="mr-2" style="position: relative">
                    <div class="logo_mock_dot"></div>
                    <div class="logo_mock"></div>
                </div>
                <span class="pt-4 pb-4 title">Internia</span>
            </div>
            <div class="flex_items">
                <span class="pt-4 pb-4 mr-5">Calender</span>
                <span class="pt-4 pb-4 mr-5">Statistic</span>
                <span class="pt-4 pb-4 mr-5 nav_active">Employee</span>
                <span class="pt-4 pb-4 mr-5">Client</span>
                <span class="pt-4 pb-4">Equipment</span>
            </div>
            <div class="flex_items mr-3">
                <i class="fa menu_icons fa-bell-o mr-4"></i>
                <div style="position: relative">
                    <i class="fa menu_icons fa-envelope-o mr-4"></i>
                    <div class="notifications_active"></div>
                </div>
                <div class="image">
                    <img src="https://s3.us-east-2.amazonaws.com/glorioustube/images/7669105f27f162dc1ae6b5f061220db7.png" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<section id="app">
    <div class="container mt-5">
        <div class="flex_items">
            <div class="title">
                @yield('title')
            </div>
            <a href="/add-employee">
                <button class="add_btn">
                    add employee
                </button>
            </a>
        </div>
        <div class="flex_items flex_items_flex_start mt-4">
            <div class="side_bar mr-4 p-4">
                <a href="{{ url('/') }}">
                    <div class="flex_items justify_content_start cl_dark p-3">
                        <div class="logo_mock mr-3"></div>
                        All employees
                    </div>
                </a>
                <div class="flex_items p-3 pb-0" style="justify-content: start">
                    PROJECT
                </div>
                <div class="flex_items justify_content_start cl_dark pl-3 pb-3">
                    <div class="initials mr-3">LB</div>
                    Lucas Beta
                </div>
                <div class="btn_wrapper">
                    <button>add project</button>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
</section>
</body>
</html>

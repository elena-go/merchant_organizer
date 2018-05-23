<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ $title }}</title>
        <!---------------------------------------|>
        <|                                       |>
        <| If you need help with this system     |>
        <| Write me: ellenagolubeva@gmail.com    |>
        <| Title: "HELP | Wires"                 |>
        <|                                       |>
        <|---------------------------------------|>
        
        <!-- Icons were downloaded from flaticon.com -->@include('_includes.other')
        <!-- Bootstrap src-->@include('_includes.bootstrap')
        <!-- Data Tables -->@include('_includes.datatable')
        <!-- Google Fonts src-->@include('_includes.fonts')
        <!-- Date Picker src-->@include('_includes.datepicker')
        <!-- Custom iPayTech CSS src-->@include('_includes.ipaytechcss')
        <!-- Custom eWalletWeb CSS src @include('_includes.ewalletwebcss')-->
    </head>
    <body>
        <!-- Top Header | Repeats every page | On small screen transforms to toggle nav (humburger menu) -->
        @include('_includes.header')
        <!-- Left-side navigation | Repeats each page | For different users menu link has different relevant name -->
        <div class="container-fluid">
            <div class="row">@include('_includes.navigation')
                <!-- Main space for content | Every page has its own content -->
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 col-lg-11 col-lg-offset-1 content-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
        <script>
            $('div.sm-alert').delay(8000).slideUp(600);
            $('div.em-wrapper').delay(8000).slideUp(1400);
            $('div.lem-wrapper').delay(8000).slideUp(600);
        </script>
    </body>
</html>
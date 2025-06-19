<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>HC BOBINADOS INDUSTRIAL</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/modules/weather-icon/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/modules/summernote/summernote-bs4.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset(path: 'dist/assets/css/components.css') }}">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <!-- header -->
            @include('shared.header')
            <!-- End header -->

            <!-- Sidebar -->
            @include('shared.aside')
            <!--end Sidebar-->
            <!-- Main Content -->

            <!-- Dasboard comentadot -->
             
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Inicio</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Admin</h4>
                                    </div>
                                    <div class="card-body">
                                        10
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="far fa-newspaper"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>News</h4>
                                    </div>
                                    <div class="card-body">
                                        42
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="far fa-file"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Reports</h4>
                                    </div>
                                    <div class="card-body">
                                        1,201
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Online Users</h4>
                                    </div>
                                    <div class="card-body">
                                        47
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                </section>
            </div


            <!--End Main Content -->
            @include('shared.footer')
            <!--  Footer -->
            
            <!-- End Footer -->
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset(path: 'dist/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/popper.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset(path: 'dist/assets/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/chart.min.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset(path: 'dist/assets/js/page/index-0.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset(path: 'dist/assets/js/scripts.js') }}"></script>
    <script src="{{ asset(path: 'dist/assets/js/custom.js') }}"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
      <!-- Required meta tags -->
      <title>404 Error | Page is not found</title>
      <meta charset="utf-8">
      <link rel="shortcut icon" href="{{asset('assets/images/adilogo.png')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('assets/css/atmos.min.css')}}">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--Material Icons-->
      <link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/materialdesignicons/materialdesignicons.min.css')}}">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css')}}">
      <!-- Custom CSS -->
      <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/helper.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
</head>
<body class="jumbo-page">
    <main class="admin-main  bg-pattern">
        <section class="error_404_main">
        <div class="container-fluid">
            <div class="row m-h-100 align-items-center ">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="error_404_img">
                                <img alt="image" src="{{asset('img/404.png')}}">
                            </div>
                            <!-- <h1>404</h1> -->
                            <h5 class="breadcrumb-text mt-20">Oops, the page you're
                                looking for does not exist.</h5>
                            <p class="small-text">
                                You may want to head back to the homepage.
                                If you think something is broken, report a problem.
                            </p>
                            <div class="mt-20">
                                @if (auth()->user())                                   
                                    <a href="{{route('Dashboard')}}" class="btn btn-lg go-back-btn small-text">Go Back Home</a>
                                @else
                                    <a href="{{route('login')}}" class="btn btn-lg go-back-btn small-text">Go Back Home</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </main>
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/jquery-3.5.1.slim.min.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.32.0/apexcharts.min.js" ></script>
</body>
</html>
<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    {{-- for csrf  --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Concept - Bootstrap 4 Admin Dashboard Template</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link href="{{asset('assets/vendor/fonts/circular-std/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/libs/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/select2/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css')}}">


    <!-- Optional JavaScript -->
    <script src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.js')}}"></script>
    <script src="{{asset('assets/vendor/slimscroll/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('assets/libs/js/main-js.js')}}"></script>
    <script src="{{asset('assets/vendor/select2/js/select2.min.js')}}"></script>


<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
<script src="http://hayageek.github.io/jQuery-Upload-File/4.0.11/jquery.uploadfile.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
<style>
    .mailivery{
        background-image: linear-gradient(135deg, #bc51b1, #16a8e8);
        -webkit-text-fill-color: transparent;
        -webkit-background-clip: text;
    }
    .ck-editor__editable_inline:not(.ck-comment__input *) {
        height: 200px;
        overflow-y: auto;
    }
    .ajax-file-upload{
        height: 32px;
    }
    .year{
        font-size: 77px;
        font-weight: 700;
    }
    .text-underline{
        text-decoration: underline;
    }
    .min-view{
        min-height: calc(100vh - 120px) !important;
    }

</style>
</head>
<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
         <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
         <div class="dashboard-header">

            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="../index.html">
                    <img width="150" src="https://assets-global.website-files.com/5fe49c82d58627c5d87b485b/5fe4a41ca6653507c625e172_Logo%20%2B%20Icon.svg" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                       
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{asset('/assets/images/avatar-1.jpeg')}}" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name">{{ Auth::user()->name }}</h5>
                                    <span class="status"></span><span class="ml-2">Available</span>
                                </div>
                                <div class="d-flex align-content-center py-2">
                                    <i class="fas fa-power-off ml-2 mt-1"></i>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
    
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
      <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            @php
                                $route = Route::currentRouteName();
                            @endphp
                            <li class="nav-item ">
                                <a href="{{Route('dashboard')}}" class="nav-link {{$route == 'dashboard' ? 'active' :''}} ">
                                    <i class="fa fa-fw fa-user-circle"></i>Dashboard 
                                </a>
                            </li>
                            
                            <li class="nav-item ">
                                <a href="{{Route('compose_mail')}}" class="nav-link {{$route == 'compose_mail' ? 'active' :''}} ">
                                    <i class="fas fa-fw fa-inbox"></i>Compose Mail 
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a href="{{Route('mail_histrory')}}" class="nav-link {{$route == 'mail_histrory' ? 'active' :''}}">
                                    <i class="fas fa-fw  fa-envelope"></i>Sent Mail 
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper min-view">
            <div class="container-fluid dashboard-content p-0 px-3">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
          </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            Copyright © 2023. All rights reserved by <a class="alert-link" href="hhttps://mailivery.io/">Mailivery</a>.
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        
        
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
 
</html>

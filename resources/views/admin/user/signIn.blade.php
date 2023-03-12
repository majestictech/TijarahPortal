
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>POS Dash | Responsive Bootstrap 4 Admin Dashboard Template</title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ URL::asset('public/assets/images/favicon.ico') }}">
      <link rel="stylesheet" href="{{ URL::asset('public/assets/css/backend-plugin.min.css') }}">
      <link rel="stylesheet" href="{{ URL::asset('public/assets/css/backend.css?v=1.0.0') }}">
      <link rel="stylesheet" href="{{ URL::asset('public/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
      <link rel="stylesheet" href="{{ URL::asset('public/assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
      <link rel="stylesheet" href="{{ URL::asset('public/assets/vendor/remixicon/fonts/remixicon.css') }}"> 
  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    
      <div class="wrapper">
      <section class="login-content">
         <div class="container">
            <div class="row align-items-center justify-content-center height-self-center">
               <div class="col-lg-8">
                  <div class="card auth-card">
                     <div class="card-body p-0">
                        <div class="d-flex align-items-center auth-content">
                           <div class="col-lg-7 align-self-center">
                              <div class="p-3">
                                 <h2 class="mb-2">{{ __('lang.signin')}}</h2>
                                 <p>{{ __('lang.login')}}123</p>
                                <form class="form" method="post" action="{{ url('users/login') }}">
                                @csrf
                                    <div class="row">
                                       <div class="col-lg-12 ">
                                          <div class="floating-label form-group">
                                             <input class="floating-input form-control" type="email" name="email" placeholder=" ">
                                             <label>{{ __('lang.email')}}</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                             <input class="floating-input form-control" type="password"  name="password" placeholder=" ">
                                             <label>{{ __('lang.password')}}</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="custom-control custom-checkbox mb-3">
                                             <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
                                             <label class="custom-control-label control-label-1" for="customCheck1">{{ __('lang.rememberme')}}</label>
                                          </div>
                                       </div>
                                       <!-- <div class="col-lg-6">
                                          <a href="auth-recoverpw.php" class="text-primary float-right">Forgot Password?</a>
                                       </div> -->
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('lang.signin')}}</button>
                                 <!--    <p class="mt-3">
                                       Create an Account <a href="auth-sign-up.php" class="text-primary">Sign Up</a>
                                    </p> -->
                                 </form>
                              </div>
                           </div>
                           <div class="col-lg-5 content-right">
                              <img src="{{ URL::asset('public/assets/images/login/01.png') }}" class="img-fluid image-right" alt="">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      </div>
    
    <!-- Backend Bundle JavaScript -->
    <script src="{{ URL::asset('public/assets/js/backend-bundle.min.js') }}"></script>
    
    <!-- Table Treeview JavaScript -->
    <script src="{{ URL::asset('public/assets/js/table-treeview.js') }}"></script>
    
    <!-- Chart Custom JavaScript -->
    <script src="{{ URL::asset('public/assets/js/customizer.js') }}"></script>
    
    <!-- Chart Custom JavaScript -->
    <script async src="{{ URL::asset('public/assets/js/chart-custom.js') }}"></script>
    
    <!-- app JavaScript -->
    <script src="{{ URL::asset('public/assets/js/app.js') }}"></script>
  </body>
</html>
<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor3 color-header headercolor4">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ URL::asset('public/assets/images/favicon-32x32.png')}}" type="image/png" />
	<!--plugins-->
	<link href="{{ URL::asset('public/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{ URL::asset('public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{ URL::asset('public/assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ URL::asset('public/assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{ URL::asset('public/assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ URL::asset('public/assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ URL::asset('public/assets/css/app.css')}}" rel="stylesheet">
	<link href="{{ URL::asset('public/assets/css/icons.css')}}" rel="stylesheet">
	<!-- Majestic Theme Style CSS -->
	<link href="{{ URL::asset('public/assets/css/theme.css')}}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ URL::asset('public/assets/css/dark-theme.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('public/assets/css/semi-dark.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('public/assets/css/header-colors.css')}}" />
	
	<link rel="stylesheet" href="{{ URL::asset('public/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
	
	<title>Tijarah POS Company Admin</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="mb-4 text-center">
							<img src="{{ URL::asset('public/assets/images/tijarah-logo.png') }}" width="180" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="text-center">
										<h3 class="">Sign in</h3>
										<p>Don't have an account yet? <a href="authentication-signup.html">Sign up here</a>
										</p>
									</div>
									
									<div class="login-separater text-center mb-4"> <span>OR SIGN IN WITH EMAIL</span>
										<hr/>
									</div>
									<div class="form-body">
										<form class="row g-3" method="POST" action="{{ route('login') }}">
										@csrf
										
										@if ($errors->has('email'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
										
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email Address</label>
												<input type="email" name="email" class="form-control" id="inputEmailAddress" required autofocus placeholder="Email Address">
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Enter Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" name="password" class="form-control border-end-0" required id="inputChoosePassword" value="" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" name="remember" id="flexSwitchCheckChecked" checked>
													<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
												</div>
											</div>
											<div class="col-md-6 text-end">	<a href="resetpass">Forgot Password ?</a>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	
	<!-- Bootstrap JS -->
	<script src="{{ URL::asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
	
	<!--plugins-->
	<script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ URL::asset('public/assets/js/app.js') }}"></script>
	
	<script src="{{ URL::asset('public/assets/plugins/chartjs/js/Chart.min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>

	
	<script src="{{ URL::asset('public/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<!--Morris JavaScript -->
	<script src="{{ URL::asset('public/assets/plugins/raphael/raphael-min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/morris/js/morris.js') }}"></script>
	<script src="{{ URL::asset('public/assets/js/index2.js') }}"></script>
	<!--app JS-->
	<script src="{{ URL::asset('public/assets/js/app.js') }}"></script>
	
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
  </body>
</html>
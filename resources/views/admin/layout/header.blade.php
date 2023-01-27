<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
//$Roles = config('app.Roles');
//print_r($Roles);
//die;
use App\MasRole;
use App\Helpers\AppHelper as Helper;

$userRoles = MasRole::select('id','name','userRights')->where ('id', Auth::user()->roleId )->first();
		
session(['userRights' => $userRoles->userRights]);
		
if(Auth::user()->roleId == 4 )
{
    $storeId = helper::getStoreId();
}

//echo helper::checkUserRights('','');

//die;

	
function page_url(){
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}

function page_url_check($page){
  $checkURL = str_replace('/posadmin','',$_SERVER['REQUEST_URI']);
  $checkURL = str_replace('/admin/','',$checkURL);
  
  $checkURL = explode('/',$checkURL);

  if($checkURL[0] == $page) {
	  return true;
  }
  else { 
	  return false;
  }
}
?>
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
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    
	
	<title>Tijarah POS Company Admin</title>

    <style>
        .navbar-expand .navbar-nav{display:none;}
    </style>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="{{ URL::asset('public/assets/images/tijarah-icon.png')}}" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<img src="{{ URL::asset('public/assets/images/tijarah-name.png')}}" class="logo-icon logo-text" alt="logo icon">
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu mm-show" id="menu">
			<!-- Dashboards Starts -->
			
			<li>
				<a href="{{url('admin')}}">
					<div class="parent-icon"><i class="bx bx-layout"></i>
					</div>
					<div class="menu-title">{{ __('lang.dashboard')}}</div>
				</a>
			</li>
			
		    <?php if(Auth::user()->roleId == 1 ){?>
		    	<li <?php if(page_url() == url('/admin/userroles')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/userroles')}}">
						<div class="parent-icon"><i class="bx bx-user-circle"></i>
						</div>
						<div class="menu-title">{{ __('lang.userroles')}}</div>
					</a>
				</li>
			<?php } ?>
			
			@if(helper::checkUserRights('log_manage'))
		    	<li <?php if(page_url() == url('/admin/logActivity')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/logActivity')}}">
						<div class="parent-icon"><i class="bx bx-book-content"></i>
						</div>
						<div class="menu-title">Log Activity</div>
					</a>
				</li>
			@endif
			
			<?php if(Auth::user()->roleId != 11 ){?>
			@if(helper::checkUserRights('store_manage'))
			   	<li <?php if(page_url_check('store')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/store')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-store-alt"></i>
						</div>
						<div class="menu-title">{{ __('lang.stores')}}</div>
					</a>
				</li>
			
			@endif
			<?php } ?>

			<?php if(Auth::user()->roleId == 11 ){?>
			   	<li <?php if(page_url() == url('/admin/chainstores')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/chainstores')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-store-alt"></i>
						</div>
						<div class="menu-title">{{ __('lang.chainstores')}}</div>
					</a>
				</li>
			<?php } ?>
			

			
			@if(helper::checkUserRights('cat_manage'))
			    <li <?php if(page_url_check('category')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/category')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-category"></i>
						</div>
						<div class="menu-title">{{ __('lang.category')}}</div>
					</a>
				</li>
			@endif
			@if(helper::checkUserRights('globalproducts_manage'))
		    	<li <?php if(page_url_check('globalproducts')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/globalproducts')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-globe"></i>
						</div>
						<div class="menu-title">{{ __('lang.globalproducts')}}</div>
					</a>
				</li>
			@endif
			@if(helper::checkUserRights('brands_manage'))
			    <li <?php if(page_url() == url('/admin/brand')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/brand')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bxl-bootstrap"></i>
						</div>
						<div class="menu-title">{{ __('lang.brands')}}</div>
					</a>
				</li>
			@endif
			
			@if(helper::checkUserRights('storetype_manage'))
			    <li <?php if(page_url_check('storetype')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storetype/')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-store-alt"></i>
						</div>
						<div class="menu-title">{{ __('lang.storetype')}}</div>
					</a>
				</li>
			@endif
			
			@if(helper::checkUserRights('vendor_manage'))
			 	<li <?php if(page_url() == url('/admin/vendor')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/vendor')}}">
						<div class="parent-icon"><i class='bx bx-home-circle'></i>
						</div>
						<div class="menu-title">{{ __('lang.vendors')}}</div>
					</a>
				</li>
			@endif
			<?php if(Auth::user()->roleId == 4 ){?>
			@if(helper::checkUserRights('inventory_manage'))
    			<li <?php if(page_url() == url('/admin/product/'.$storeId)) { ?> class="mm-active" <?php } ?>>
    				<a href="{{url('/admin/product/'.$storeId)}}">
    					<div class="parent-icon"><i class="bx bx-package"></i>
    					</div>
    					<div class="menu-title">{{ __('lang.inventory')}}</div>
    				</a>
    			</li>
			@endif
			<?php } ?>
			@if(helper::checkUserRights('vat_manage'))
			    <li <?php if(page_url_check('vat')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/vat/')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-box"></i>
						</div>
						<div class="menu-title">{{ __('lang.vat')}}</div>
					</a>
				</li>
			@endif
			@if(helper::checkUserRights('orders_manage'))
    		    <li <?php if(page_url_check('order')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/order')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-receipt"></i>
						</div>
						<div class="menu-title">{{ __('lang.orders')}}</div>
					</a>
				</li>
			@endif
			
			<?php if(Auth::user()->roleId == 4 ){?>
			<!--
			@if(helper::checkUserRights('orders_manage'))
    		    <li>
					<a href="{{url('/admin/order/'.$storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-list-ol"></i>
						</div>
						<div class="menu-title">{{ __('lang.orders')}}</div>
					</a>
				</li>
			@endif
			-->
			@if(helper::checkUserRights('consumers_manage'))
    		    <li <?php if(page_url() == url('/admin/customer/'.$storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/customer/'.$storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-group"></i>
						</div>
						<div class="menu-title">{{ __('lang.customers')}}</div>
					</a>
				</li>
			@endif
			
			@if(helper::checkUserRights('cashier_manage'))
    		    <li <?php if(page_url() == url('/admin/cashier/'.$storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/cashier/'.$storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-user-pin"></i>
						</div>
						<div class="menu-title">{{ __('lang.cashier')}}</div>
					</a>
				</li>
			@endif
			
			@if(helper::checkUserRights('cashier_manage'))
    		    <li <?php if(page_url() == url('/admin/customerscreen/'.$storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/customerscreen/'.$storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-slideshow"></i>
						</div>
						<div class="menu-title">{{ __('lang.custscreenslider')}}</div>
					</a>
				</li>
			@endif
			
			@if(helper::checkUserRights('device_manage'))
    		    <li <?php if(page_url() == url('/admin/device/'.$storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/device/'.$storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bxs-devices"></i>
						</div>
						<div class="menu-title">{{ __('lang.devices')}}</div>
					</a>
				</li>
			@endif
			<?php } ?>
			<!-- @if(helper::checkUserRights('loyaltypoint_manage'))
    		    <li>
					<a href="{{url('/admin/loyaltyhistory')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-layer"></i>
						</div>
						<div class="menu-title">{{ __('lang.loyaltypointshistory')}}</div>
					</a>
				</li>
			@endif -->
			@if(helper::checkUserRights('subscription_manage'))
    		    <li <?php if(page_url_check('subscription')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/subscription')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-hive"></i>
						</div>
						<div class="menu-title">{{ __('lang.subscriptionplanmanagement')}}</div>
					</a>
				</li>
			@endif
			<?php if(Auth::user()->roleId == 4 ){?>
			@if(helper::checkUserRights('report_manage'))
			    <li class="menu-label">{{ __('lang.reports')}}</li>
			    @if(helper::checkUserRights('salesreport_manage'))
				<li <?php if(page_url() == url('/admin/storereports/salesreports/' . $storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storereports/salesreports/' . $storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-bar-chart"></i>
						</div>
						<div class="menu-title">{{ __('lang.salesreports')}}</div>
					</a>
				</li>
				@endif
				@if(helper::checkUserRights('vatreport_manage'))
				<li <?php if(page_url() == url('/admin/storereports/vatreports/' . $storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storereports/vatreports/' . $storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-bar-chart-square"></i>
						</div>	
						<div class="menu-title">{{ __('lang.vatreports')}}</div>
					</a>
				</li>
				@endif
				@if(helper::checkUserRights('refundreport_manage'))
				<li <?php if(page_url() == url('/admin/storereports/refundreports/' . $storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storereports/refundreports/' . $storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-chart"></i>
						</div>	
						<div class="menu-title">{{ __('lang.refundreports')}}</div>
					</a>
				</li>
				@endif
				@if(helper::checkUserRights('inventoryreport_manage'))
				<li <?php if(page_url() == url('/admin/storereports/inventoryreports/' . $storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storereports/inventoryreports/' . $storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-pie-chart"></i>
						</div>	
						<div class="menu-title">{{ __('lang.inventoryreports')}}</div>
					</a>
				</li>
				@endif
				@if(helper::checkUserRights('purchasereport_manage'))
				<li <?php if(page_url() == url('/admin/storereports/purchasereports/' . $storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storereports/purchasereports/' . $storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-pie-chart-alt"></i>
						</div>	
						<div class="menu-title">{{ __('lang.purchasereports')}}</div>
					</a>
				</li>
				@endif
				@if(helper::checkUserRights('mediareport_manage'))
				<li <?php if(page_url() == url('/admin/storereports/mediareports/' . $storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storereports/mediareports/' . $storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-line-chart-down"></i>
						</div>	
						<div class="menu-title">{{ __('lang.mediareports')}}</div>
					</a>
				</li>
				@endif
				@if(helper::checkUserRights('cashierreport_manage'))
				<li <?php if(page_url() == url('/admin/storereports/cashierreports/' . $storeId)) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/storereports/cashierreports/' . $storeId)}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-network-chart"></i>
						</div>	
						<div class="menu-title">{{ __('lang.cashierreports')}}</div>
					</a>
				</li>
				@endif
			@endif
			<?php } ?>
			@if(helper::checkUserRights('app_manage'))
			    <li class="menu-label">{{ __('lang.appupdate')}}</li>
				<li <?php if(page_url_check('appupdate/create')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('/admin/appupdate/create')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-devices"></i>
						</div>
						<div class="menu-title">{{ __('lang.appupdate')}}</div>
					</a>
				</li>
			@endif
				
			 <?php  if(Auth::user()->roleId != 11 ){  ?>
				<li class="menu-label">{{ __('lang.settings')}}</li>
			<?php } ?>
			<!-- AdminManagement Starts -->
			<?php if(Auth::user()->roleId == 1 || Auth::user()->roleId == 2){?>
				<li <?php if(page_url() == url('/admin/subscription')) { ?> class="mm-active" <?php } ?>>
					<a href="{{url('admin/usersmanagement')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bxs-user-detail"></i>
						</div>
						<div class="menu-title">{{ __('lang.usersmanagement')}}</div>
					</a>
				</li>
				<?php } ?>

			<!-- AdminManagement Starts 
				<li>
					<a href="{{url('/admin/adminmanagement')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-user-circle"></i>
						</div>
						<div class="menu-title">{{ __('lang.adminmanagement')}}</div>
					</a>
				</li>
				
				// Subadmin Starts 
				@if(helper::checkUserRights('subadmin_manage'))	
				<li>
					<a href="{{url('/admin/subadmin')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-user-circle"></i>
						</div>
						<div class="menu-title">{{ __('lang.subadmin')}}</div>
					</a>
				</li>
			@endif
			 Subadmin End -->
			<!-- @if(helper::checkUserRights('notifications_manage'))	
				<li>
					<a href="{{url('/admin/pushnotification/create')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-notification"></i>
						</div>
						<div class="menu-title">{{ __('lang.pushnotifications')}}</div>
					</a>
				</li>
			@endif
			@if(helper::checkUserRights('faq_manage'))	
				
				<li>
					<a href="{{url('/admin/faq')}}">
						<div class="parent-icon"><i class="fadeIn animated bx bx-help-circle"></i>
						</div>
						<div class="menu-title">{{ __('lang.faqs')}}</div>
					</a>
				</li>
			@endif -->
			
			<!-- FAQ's Ends -->
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		
		<!--start header -->
		<header>
		    
		    
		    
		    
		    
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
				    
				    
				    
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
					<div class="search-bar flex-grow-1">
					<!--	<div class="position-relative search-bar-box">
							<input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
							<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
						</div>-->
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<!--<li class="nav-item mobile-search-icon">
								<a class="nav-link" href="#">	<i class='bx bx-search'></i>
								</a>
							</li>-->
							
											
                        	<?php if(Auth::user()->roleId == 1 || Auth::user()->roleId == 2){?>
							
    							<li class="nav-item dropdown dropdown-large">
    								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">	<i class='bx bx-category'></i>
    								</a>
    								<div class="dropdown-menu dropdown-menu-end">
    									<div class="row row-cols-2 g-3 p-3">
    										<div class="col text-center">
    											<a href="{{url('/admin/store')}}"><div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-store-alt'></i>
    											</div></a>
    											<div class="app-title">Stores</div>
    										</div>
    										<div class="col text-center">
    									    	<a href="{{url('/admin/order')}}"><div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx  bx-list-ol'></i>
    											</div></a>
    											<div class="app-title">Orders</div>
    										</div>
    										<!--<div class="col text-center">
    											<a href="{{url('/admin/customer')}}"><div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-group'></i>
    											</div></a>
    											<div class="app-title">Consumers</div>
    										</div>-->
    										<div class="col text-center">
    											<a href="{{url('/admin/subadmin')}}"><div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-user-circle'></i>
    											</div></a>
    											<div class="app-title">Sub-Admin</div>
    										</div>
    										<!--<div class="col text-center">
    											<a href="{{url('/admin/cashier')}}"><div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-user'></i>
    											</div></a>
    											<div class="app-title">Cashier</div>
    										</div>-->
    										<div class="col text-center">
    											<a href="{{url('/admin/vendor')}}"><div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-home-circle'></i>
    											</div></a>
    											<div class="app-title">Vendors</div>
    										</div>
    									</div>
    								</div>
    							</li>
    							
    						<?php } ?>
    						
    						
                        	<?php if(Auth::user()->roleId == 4){?>
							
    							<li class="nav-item dropdown dropdown-large">
    								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">	<i class='bx bx-category'></i>
    								</a>
    								<div class="dropdown-menu dropdown-menu-end">
    									<div class="row row-cols-2 g-3 p-3">
    										<div class="col text-center">
    									    	<a href="{{url('/admin/order/'.$storeId)}}"><div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx  bx-list-ol'></i>
    											</div></a>
    											<div class="app-title">Orders</div>
    										</div>
    										<div class="col text-center">
    											<a href="{{url('/admin/customer/'.$storeId)}}"><div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-group'></i>
    											</div></a>
    											<div class="app-title">Consumers</div>
    										</div>
    										<div class="col text-center">
    											<a href="{{url('/admin/cashier/'.$storeId)}}"><div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-user'></i>
    											</div></a>
    											<div class="app-title">Cashier</div>
    										</div>
    										<div class="col text-center">
    											<a href="{{url('/admin/vendor/'.$storeId)}}"><div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-home-circle'></i>
    											</div></a>
    											<div class="app-title">Vendors</div>
    										</div>
    									</div>
    								</div>
    							</li>
    							
    						<?php } ?>    						
    						
    						
    						
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
									<i class='bx bx-bell'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
												ago</span></h6>
													<p class="msg-info">5 new user registered</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
												</div>
									
									
												<div class="flex-grow-1">
													<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
												ago</span></h6>
													<p class="msg-info">You have recived new orders</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class="bx bx-file"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
												ago</span></h6>
													<p class="msg-info">The pdf files generated</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
												ago</span></h6>
													<p class="msg-info">5.1 min avarage time response</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-info text-info"><i class="bx bx-home-circle"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Product Approved <span
												class="msg-time float-end">2 hrs ago</span></h6>
													<p class="msg-info">Your new product has approved</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-message-detail"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">New customer comments recived</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
												ago</span></h6>
													<p class="msg-info">Successfully shipped your item</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class='bx bx-user-pin'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
												ago</span></h6>
													<p class="msg-info">24 new authors joined last week</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class='bx bx-door-open'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
												ago</span></h6>
													<p class="msg-info">45% less alerts last 4 weeks</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Notifications</div>
									</a>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
									<i class='bx bx-comment'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Messages</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-message-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
												sec ago</span></h6>
													<p class="msg-info">Many desktop publishing packages</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-3.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
												min ago</span></h6>
													<p class="msg-info">Making this the first true generator</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-5.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
												ago</span></h6>
													<p class="msg-info">Duis aute irure dolor in reprehenderit</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-6.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">The passage is attributed to an unknown</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-7.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">The point of using Lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
												ago</span></h6>
													<p class="msg-info">It was popularised in the 1960s</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-9.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-10.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
												ago</span></h6>
													<p class="msg-info">If you are going to use a passage</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-11.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
												ago</span></h6>
													<p class="msg-info">All the Lorem Ipsum generators</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Messages</div>
									</a>
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="{{ URL::asset('public/assets/images/avatars/avatar-26.jpg') }}" class="user-img" alt="user avatar">
							<div class="user-info ps-3">
								<p class="user-name mb-0"><?php echo auth()->user()->firstName . " " . auth()->user()->lastName; ?></p>
								<p class="designattion mb-0"><?php echo auth()->user()->email; ?></p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<!--
							<li><a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Profile</span></a>
							</li>
							-->
							<li><a class="dropdown-item" href="{{url('/admin/settings/'.auth()->user()->id.'/edit')}}"><i class="bx bx-cog"></i><span>Settings</span></a>
							</li>
							<li><a class="dropdown-item" href="{{url('admin')}}"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<li><a class="dropdown-item" href="{{url('admin/logout')}}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
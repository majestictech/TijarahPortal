<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\AppHelper as Helper;

$Roles = config('app.Roles');
?>

@include('admin.layout.header')

<style type="text/css">
	.dataTables_filter, .dataTables_info, .dataTables_paginate {
     display: none;
}
</style>


<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item">
					<a class="text-primary" href="{{url('/admin/storereports/' . $results->storeId)}}">
						<i class="bx bx-home-alt"></i> {{ __('lang.storereports')}}
					</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a class="text-primary" href="{{url('/admin/storereports/shiftreports/' .  $results->storeId)}}">
						<i class="bx bx-store-alt"></i>{{ __('lang.shiftreports')}}
					</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a class="text-primary" href="{{url('/admin/storereports/shiftdayreport/'.$results->storeId.'/'.$results->dateCreated)}}">
						<i class="bx bx-store-alt"></i>{{ __('lang.shiftdayreports')}}
					</a>
                </li>
				<li class="breadcrumb-item active" aria-current="page">
					<i class="bx bx-store-alt"></i>{{ __('lang.reports')}}
                </li>
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
        <!-- <div class="row">
			<div class="col-6 mx-auto"><h6 class="mb-0 text-uppercase">{{ __('lang.reports')}}</h6></div>
			<div class="col-6 mx-auto text-end">
				<button class="btn btn-primary p-1">{{ __('lang.reprintshift/dayendreport')}}</button>
			</div>
		</div>
		<hr/> -->
		<div class="card">
			<div class="card-body rounded">
            <table class="table mb-0 table-bordered" id="myTable">
				<tbody>
                    <tr>
                        <td class="col-6 ps-3">{{ __('lang.terminaldevice')}}: &nbsp; --<!-- T-Rex --><br>{{ __('lang.shiftnumber')}}: &nbsp;{{$results->shiftId}}<br>{{ __('lang.cashier')}}: &nbsp;{{$results->firstName}} {{$results->lastName}}<br>{{ __('lang.shiftin')}}: &nbsp;{{$results->shiftInTime}}<br>{{ __('lang.shiftend')}}: &nbsp;{{$results->shiftEndTime}}</td>
                        
                        <td class="col-6 ps-3">{{ __('lang.openingbalance')}}(as System): &nbsp;{{$results->shiftInCDBalance}}<br>
						{{ __('lang.openingbalance')}}(as Cashier): &nbsp;{{$results->shiftInBalance}}<br>
						{{ __('lang.closingbalance')}}(as System): &nbsp;{{$results->shiftEndCDBalance}}<br>
						{{ __('lang.closingbalance')}}(as Cashier): &nbsp;{{$results->shiftEndBalance}}<br>
						{{ __('lang.cashsales')}}: &nbsp;--<!-- SAR 4,443 --><br>
						{{ __('lang.cardsales')}}: &nbsp;--<!-- SAR 0 --><br>
						{{ __('lang.creditdeposites')}}: &nbsp;--<!-- SAR 0 --><br>
						{{ __('lang.numberofbills')}}: &nbsp;--<!-- XXXX --><br>
						{{ __('lang.cashrefunds')}}: &nbsp;--<!-- SAR 0 --><br>
						{{ __('lang.purchaseexpenses')}}: &nbsp;--<!-- SAR 0 --><br>
						{{ __('lang.cashadjustments')}}: &nbsp;{{$results->adjustAmount}}</td>
                    </tr>
                </tbody>
            </table><hr/><table class="table mb-0 table-bordered" id="myTable">
            <div class="row">
                <div class="col-6 ps-4">{{ __('lang.adjustmentreason')}} : {{$results->reason}}</div>
                <div class="col-6 d-flex">
                    <div class="col-4 ps-2">{{ __('lang.cashintray')}}</div>
                    <div class="col-2">{{ __('lang.sar')}} {{$results->shiftEndBalance}}</div>
                </div>
            </div>
        </table>
			</div>
		</div>
	</div>
</div>
@include('admin.layout.footer')
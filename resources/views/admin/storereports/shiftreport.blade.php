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

<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
        <div class="row">
		<div class="col-6 mx-auto"><h6 class="mb-0 text-uppercase">{{ __('lang.shiftreports')}}</h6></div>
		<div class="col-6 mx-auto text-end"><button class="btn btn-primary p-1">{{ __('lang.reprintshift/dayendreport')}}</button>
        </div></div>
		<hr/>
		<div class="card">
			<div class="card-body rounded">
            <table class="table mb-0 table-bordered" id="myTable">
				<tbody>
                    <tr>
                        <td class="col-6 ps-3">{{ __('lang.terminaldevice')}}: &nbsp;T-Rex<br>{{ __('lang.shiftnumber')}}: &nbsp;1<br>{{ __('lang.cashier')}}: &nbsp;Scavenger<br>{{ __('lang.shiftin')}}: &nbsp;00:00:01; 00-00-0001<br>{{ __('lang.shiftend')}}: &nbsp;01:01:01; 00-00-0001</td>
                        
                        <td class="col-6 ps-3">{{ __('lang.openingbalance')}}: &nbsp;SAR 01<br>{{ __('lang.closingbalance')}}: &nbsp;SAR 4,444<br>{{ __('lang.cashsales')}}: &nbsp;SAR 4,443<br>{{ __('lang.cardsales')}}: &nbsp;SAR 0<br>{{ __('lang.creditdeposites')}}: &nbsp;SAR 0<br>{{ __('lang.numberofbills')}}: &nbsp;XXXX<br>{{ __('lang.cashrefunds')}}: &nbsp;SAR 0<br>{{ __('lang.purchaseexpenses')}}: &nbsp;SAR 0<br>{{ __('lang.cashadjustments')}}: &nbsp;SAR 0</td>
                    </tr>
                </tbody>
            </table><hr/><table class="table mb-0 table-bordered" id="myTable">
            <div class="row">
                <div class="col-6 ps-4">{{ __('lang.adjustmentreason')}}</div>
                <div class="col-6 d-flex">
                    <div class="col-4 ps-2">{{ __('lang.cashintray')}}</div>
                    <div class="col-2">{{ __('lang.sar')}} 4,444</div>
                </div>
            </div>
        </table>
			</div>
		</div>
	</div>
</div>
@include('admin.layout.footer')
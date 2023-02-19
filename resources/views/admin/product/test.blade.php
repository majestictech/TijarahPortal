@include('admin.layout.header')	
<?php
use App\Helpers\AppHelper as Helper;
?>


<table class="table mb-0 table-striped table-bordered" id="">
	<thead>
		<tr>
			<!-- //<th scope="col">{{ __('lang.orderid')}}</th> -->
			<th scope="col">{{ __('lang.id')}}</th>
			<th scope="col">{{ __('lang.name')}}</th>
			<th scope="col">{{ __('lang.sellingprice')}}</th>
			<th scope="col">{{ __('lang.qty')}}</th>
			<th scope="col">{{ __('lang.totalamount')}}</th>
			
		</tr>
	</thead>
	<tbody>
	
	@foreach($orderDetails as $key =>$orderDetail)
		<tr> 
			<td>{{$orderDetail->id}} </td>
			<td>{{$orderDetail->name}} </td>
			<td>{{$orderDetail->sellingPrice}}</td>
			<td>{{$orderDetail->amount}}</td>
			<td>{{$orderDetail->total}}</td>
		</tr>
	@endforeach
	</tbody>
</table>
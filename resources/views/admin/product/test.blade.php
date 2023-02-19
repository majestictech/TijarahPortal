@include('admin.layout.header')	
<?php
use App\Helpers\AppHelper as Helper;
?>


<table class="table mb-0 table-striped table-bordered" id="">
	<thead>
		<tr>
			<!-- //<th scope="col">{{ __('lang.orderid')}}</th> -->
			<th scope="col">Id</th>
			<th scope="col">Order Id</th>
			<th scope="col">Store Id</th>
			<!--<th scope="col" width="40%">orderDetail</th>-->
			<th scope="col">Total Amount</th>
			<th scope="col">Total Actual Amount</th>
			<th scope="col">Order Placed</th>
		</tr>
	</thead>
	<tbody>
	
	@foreach($errorOrders as $key =>$order)
		<tr> 
			<td>{{$order->id}}</td>
			<td>{{$order->orderId}}</td>
			<td>{{$order->storeId}}</td>
			<!--<td>{{$order->orderDetail}}</td>-->
			<td>{{$order->totalAmount}}</td>
			<td>{{$order->errorTotalCheck}}</td>
			<td>{{$order->created_at}}</td>
		</tr>
	@endforeach
	</tbody>
</table>
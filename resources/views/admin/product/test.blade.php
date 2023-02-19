@include('admin.layout.header')	
<?php
use App\Helpers\AppHelper as Helper;
?>


<table class="table mb-0 table-striped table-bordered" id="">
	<thead>
		<tr>
			<!-- //<th scope="col">{{ __('lang.orderid')}}</th> -->
			<th scope="col">id</th>
			<th scope="col">orderId</th>
			<th scope="col">storeId</th>
			<th scope="col">orderDetail</th>
			<th scope="col">totalAmount</th>
			<th scope="col">errorTotalCheck</th>
			<th scope="col">created_at</th>
		</tr>
	</thead>
	<tbody>
	
	@foreach($errorOrders as $key =>$order)
		<tr> 
			<td>{{$order->id}}</td>
			<td>{{$order->orderId}}</td>
			<td>{{$order->storeId}}</td>
			<td>{{$order->orderDetail}}</td>
			<td>{{$order->totalAmount}}</td>
			<td>{{$order->errorTotalCheck}}</td>
			<td>{{$order->created_at}}</td>
		</tr>
	@endforeach
	</tbody>
</table>
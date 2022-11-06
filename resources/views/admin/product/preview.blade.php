<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Order Detail</title>
	
	<style type="text/css">
		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
		}
		
		table {
			width: 100%
			border: 1px solid #000;
		}
		
		table th {
			background-color: #ccc;
		}
	</style>
</head>

<body>	


	<table cellspacing="0" cellpadding="10" style="width:100%" width="100%">
		<tr>
			<td>#TJ55874254LKFDS</td>
			<td>30 Sep 2021</td>
		</tr>
		<tr >
			<td style="border-bottom:1px solid gray;"></td>
			<td style="border-bottom:1px solid gray;">8:56 AM</td>
		</tr>
		
		
		<tr >
			<td style="border-top:1px solid gray;">Payment Status</td>
			<td style="border-top:1px solid gray;"><b>Paid</b></td>
		</tr>
		<tr>
			<td>Payment Type</td>
			<td><b>Cash</b></td>
		</tr>	
		<tr>
			<td style="border-bottom:1px solid gray;">Total Amount</td>
			<td style="border-bottom:1px solid gray;"><b>SAR <span style="color:green;">150</span></b></td>
		</tr>
		
		<tr>
			<td>Products Count</td>
			<td></td>
		</tr>
			
	</table>
	<div style="margin-top:20px">
		<table cellspacing="0" cellpadding="15" style="width:100%" width="100%">
			<tr style="background-color:lightgray;">
				<th style="text-align:left">Products</th>
				<th style="text-align:left">SP</th>
				<th style="text-align:left">Qty</th>
				<th style="text-align:left">Total Amt.</th>
			</tr>
			<tr>
				<td>Apple Cider<br/>Vinegar<br/><span style="color:lightgray; font-size:12px;">3 units</span></td>
				<td>SAR <span style="color:green;">50.00</span></td>
				<td>3</td>
				<td>SAR <span style="color:green;">150</span></td>
			</tr>
		</table>
	</div>
</body>
</html>
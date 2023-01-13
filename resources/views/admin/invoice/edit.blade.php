@include('admin.layout.header')
<style>
	.pay-mode {
    color: #003a02;
    font-size: 18px;
    font-weight: 500;
  }
    .radio-btn {
      margin-left: 15px;
      color: #003a02;
    font-size: 17px;
    font-weight: 500;
    }

	.ui-menu-item {
		padding: 5px;
		width: 100px;
	}
	.ui-menu  {
		list-style: none;
		background-color: #fff !important;
		width: 100px;
	}
	.input-group 
	{
		flex-wrap: initial;
	}
	.ui-menu-item>div:hover {
      
      color: #fff;
	  
    }

    .ui-menu-item:hover {
      border: none;
      color: #fff !important;
	  width: 100px;
	  background-color: #157d4c !important;
    }

    .ui-menu {
      cursor: pointer;
      
    }
    .ui-helper-hidden-accessible {
      display: none;
    }
</style>
<!--breadcrumb-->
<!--<script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function(){      
    var url = "{{ url('add-remove-input-fields') }}";
	//var i = $('#dynamic_field tr').index($(this).closest('tr'));
    //var i=1;  
	var i ={!! json_encode($orderProductsCount) !!};
	//alert(i);
    $('#add').click(function(){  
    var title = $("#title").val();
    i++;  
    $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><div class="input-group" style="flex-wrap: initial;"><span class="input-group-text bg-transparent"><i class="bx bx-id-card"></i></span><input type="text" id="product_search'+i+'" data-rowindex="'+i+'"><input type="text" id="productId'+i+'" hidden name="productId[]"><input type="text" id="productName'+i+'" class="form-control" hidden name="productName[]" ><input type="text" id="productNameAr'+i+'" class="form-control" hidden name="productNameAr[]"><input type="text" id="barCode'+i+'" class="form-control" hidden name="barCode[]"><input type="text" id="sellingPrice'+i+'" class="form-control" hidden name="sellingPrice[]"></span></div></td><td><input type="number" class="form-control name_list costing cp" name="cp[]" value="" id="cp_' + i + '" placeholder="{{ __('lang.cp')}}" onChange="getValTotal()"  step=".01" required><input type="number" id="tax_' + i + '" class="form-control tax" value="" name="tax[]" hidden></td><td><input style="border-left:1px solid lightgray !important;" type="number" class="form-control border-start-0" name= "quantity[]" placeholder="{{ __('lang.quantity')}}" id="quantity_' + i + '"  step=".01"  onChange="getValTotal()" value="" required></td><td><input style="border-left:1px solid lightgray !important;" type="date" class="form-control border-start-0" name="expiryDate[]" placeholder="{{ __('lang.expirydate')}}" id="expiryDate" ></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
	
	
	//alert(i);
	//var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
	//alert(rowIndex);
	//$( "#product_search" ).autocomplete({
	$( '#product_search'+i).autocomplete({
        source: function( request, response ) {
           // Fetch data
           $.ajax({
             url:"{{route('fetchProduct')}}",
             type: 'post',
             dataType: "json",
			 data: {
				"_token": "{{ csrf_token() }}",
				search: request.term
				},
           
             success: function( data ) {
				console.log(data);
                response( data );
             }
           });
        },
        select: function (event, data) {
          // Set selection
		  console.log(data.item);
		  var id = this.getAttribute('data-rowindex');
		  console.log(('#product_search'+id));
		  console.log(('#productId'+id));
          $('#product_search'+id).val(data.item.label); // display the selected text
          $('#productId'+id).val(data.item.id); // save selected id to input
		  $('#productName'+id).val(data.item.label); // save selected id to input
		  $('#productNameAr'+id).val(data.item.name_ar); // save selected text to input for Product Name Arabic
          $('#barCode'+id).val(data.item.barCode); // save selected text to input for Barcode
          $('#sellingPrice'+id).val(data.item.sellingPrice); // save selected text to input for sellingPrice
          $('#tax'+id).val(data.item.tax); // save selected text to input for tax
		  var price = data.item.value;
		  var tax = data.item.tax;
		  //var rowIndex = $('#productId'+i+'').children('option:selected').data('rowindex');
		   
			//alert(id);
		//var rowVal = rowIndex+1;
			//alert(price);
			//alert(rowIndex);
			//alert($('#cp_'+id).html());
			
			$('#cp_'+id).val(price);
			$('#tax_'+id).val(tax);
			$('#quantity_'+id).val(1);
			getValTotal();
          return false;
        }
     });
	
    });  
    $(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();  
	getValTotal();
    });  
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $('#submit').click(function(){            
    $.ajax({  
    url:"{{ url('add-remove-input-fields') }}",  
    method:"POST",  
    data:$('#add_name').serialize(),
    type:'json',
    success:function(data)  
    {
    if(data.error){
    display_error_messages(data.error);
    }else{
    i=1;
    $('.dynamic-added').remove();
    $('#add_name')[0].reset();
    $(".show-success-message").find("ul").html('');
    $(".show-success-message").css('display','block');
    $(".show-error-message").css('display','none');
    $(".show-success-message").find("ul").append('<li>Todos Has Been Successfully Inserted.</li>');
    }
    }  
    });  
    });  
    function display_error_messages(msg) {
    $(".show-error-message").find("ul").html('');
    $(".show-error-message").css('display','block');
    $(".show-success-message").css('display','none');
    $.each( msg, function( key, value ) {
    $(".show-error-message").find("ul").append('<li>'+value+'</li>');
    });
    }
    });  
	
	
	/* Total Amount and Vat Total Start */
	
	function getValTotal(){
		var cp = (cp = $("input[name='cp[]']").map(function(){return $(this).val();}).get());

		var quantity = $("input[name='quantity[]']").map(function(){return $(this).val();}).get();

		var tax = $("input[name='tax[]']").map(function(){return $(this).val();}).get();

		
		var total = 0;
		var vatTotal = 0;
		for(let i=0; i<cp.length; i++) {
			var totalPre = 0;
			totalPre = parseInt(cp[i]) * parseInt(quantity[i]);

			if(isNaN(totalPre)) {
				var totalPre = 0;
			}

			total = total + totalPre;

			/* Vat Amount Start */
      
			//(p.costPrice - (p.costPrice/(1+p.tax/100)))*p.quantity;

			vatTotalPre =  ( parseFloat(cp[i]) - ( parseFloat(cp[i])/(1+(parseFloat(tax[i])/100))) )*parseFloat(quantity[i]);
			if(isNaN(vatTotalPre)) {
						var vatTotalPre = 0;
					}
			vatTotal = vatTotal+ vatTotalPre;

			//alert(cp[i]);
			//alert(tax[i]);

			vatTotal = vatTotal.toFixed(2);
					vatTotal = parseFloat(vatTotal);
			//alert(vatTotalPre.toFixed(2));

			/* Vat Amount End */
			
		}
		//alert(000); // not call
		$(document).ready(function () {
			//alert(111);
			/* $('input').change(function(){

				$("#getTotalValue").text(total);
			}); */
			$("#getTotalValue").text(total);
			$('#totalAmount').attr('value', total)
			$('#vatAmount').attr('value', vatTotal)
			/* $(":input").on("keyup change click", function(e) {
				// do stuff!
				$("#getTotalValue").text(total);
				alert(222);
			}) */
		});
		//alert(total);
		
	}
	/* Total Amount and Vat Total End */
</script>


<script type="text/javascript">

   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


$(document).ready(function(){
	var i ={!! json_encode($orderProductsCount) !!};
	//alert(i);
    function getVal(){
        var price = $('.productId').children('option:selected').data('cost');
        var rowIndex = $('.productId').children('option:selected').data('rowindex');
        //var rowIndex = $('.productId').children('option:selected').val();
		//var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
		//var rowVal = rowIndex+1;
		//alert(price);
		//alert(rowIndex);
		//alert($('#cp'+ rowIndex).html());
		
		$('#cp_'+rowIndex).val(price);
		$('#tax_'+rowIndex).val(tax);
    }
    $(".productId").on("change", getVal);
	
	function getProductList(){
		//alert('test')
		 var id = this.getAttribute('data-rowindex');
		 //alert(id);
		 //alert('#product_search'+id );
	
	$( '#product_search'+id).autocomplete({
        source: function( request, response ) {
           // Fetch data
           $.ajax({
             url:"{{route('fetchProduct')}}",
             type: 'post',
             dataType: "json",
			 data: {
				"_token": "{{ csrf_token() }}",
				search: request.term
				},
           
             success: function( data ) {
				console.log(data);
                response( data );
             }
           });
        },
        select: function (event, data) {
          // Set selection
		  console.log(data.item);
          //$('#product_search').val(data.item.label); // display the selected text
          //$('#productId').val(data.item.id); // save selected id to input
		   $('#product_search'+id).val(data.item.label); // display the selected text
          $('#productId'+id).val(data.item.id); // save selected id to input
          $('#productName'+id).val(data.item.id); // save selected id to input
		  $('#productNameAr').val(data.item.name_ar); // save selected id to input
          $('#barCode').val(data.item.barCode); // save selected id to input
          $('#sellingPrice').val(data.item.sellingPrice); // save selected id to input
          $('#tax').val(data.item.tax); // save selected id to input
		  var price = data.item.value;
		  var tax = data.item.tax;
		  var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
			var rowVal = rowIndex+1;
			//alert(price);
			//alert(++rowIndex);
			//alert($('#cp_'+rowIndex).html());
			
			$('#cp_'+rowVal).val(price);
			$('#tax_'+rowVal).val(tax);
          return false;
        }
     });
	}
	$(".p_names").on("click", getProductList);

	
});


</script>


<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/invoice').'/'.$invoice->storeId}}"><i class="bx bx-detail"></i> {{ __('lang.allinvoices')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.editinvoice')}}</li>
			</ol>
		</nav>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<div class="card border-top border-0 border-4 border-secondary">
			<div class="card-body p-5 ">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-detail me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.editinvoice')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('invoice.update')}}" data-toggle="validator">
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
				  	<div class="col-4">
						<label for="vendorId" class="form-label">{{ __('lang.selectvendor')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-home-circle'></i>
							</button>
							<select name="vendorId" class="form-select single-select" id="vendorId" aria-label="Example select with button addon" required>
								<option value="">{{ __('lang.selectvendor')}}</option>
									@foreach($vendor as $key=>$value)
									    <option value="{{$value->id}}" {{ ( $value->id == $invoice->vendorId) ? 'selected' : '' }} >{{$value->vendorName}} </option>
							    	@endforeach
							</select>
						</div>
					</div>
					
                	<div class="col-md-4">
						<label for="invoiceNumber" class="form-label">{{ __('lang.invoicenumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="number" class="form-control border-start-0" value="{{$invoice->invoiceNumber}}" name="invoiceNumber" id="invoiceNumber" required>
						</div>
					</div>
					
					<div class="col-md-4">
						<label for="invoiceDate" class="form-label">{{ __('lang.invoicedate')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="date" class="form-control border-start-0" value="{{$invoice->invoiceDate}}" name="invoiceDate" id="invoiceDate" required>
						</div>
					</div>	
					<div class="table-responsive">  
                        <table class="table table-bordered" id="dynamic_field"> 
						@foreach($orderProducts as $key=>$orderProduct)
						<?php $count = $key+1 ?>
                            <tr> 
                           
            					<td>
                			         <div class="col-auto">
                				    	<label for="product" class="form-label">{{ __('lang.product')}} *</label>
                						<div class="input-group"> 
											<span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
                							<!--<select name="productId[]" class="form-select" id="productId" aria-label="Example select with button addon" required>
                								<option value="">{{ __('lang.selectproduct')}}</option>
                									@foreach($products as $key=>$product)
                										<option value="{{$product->id}}" {{ ( $product->id ==  $orderProduct['id'] ) ? 'selected' : '' }}>{{$product->name}} </option>
                									@endforeach
                							</select>-->
											
											@foreach($products as $key=>$product)
											
											@if($product->id == $orderProduct['id'])
												
												
											
											
											<input type="text" id="product_search<?php echo $count ?>" class="form-control p_names" data-rowindex="<?php echo $count ?>" value="@if(isset($orderProduct['name'])) {{$orderProduct['name']}} @endif">
											@endif
											@endforeach
										
											<input type="text" class="form-control" id='productId' hidden name="productId[]" value="{{$orderProduct['id']}}">
											<!-- Extra Hidden Fields Start-->
											<input type="text" id='productName' class="form-control" hidden name="productName[]" value="@if(isset($orderProduct['name'])) {{$orderProduct['name']}} @endif" >
											<input type="text" id='productNameAr' class="form-control" hidden name="productNameAr[]" value="@if(isset($orderProduct['name_ar'])) {{$orderProduct['name_ar']}} @endif">
											<input type="text" id='barCode' class="form-control" hidden name="barCode[]" value="@if(isset($orderProduct['barCode'])) {{$orderProduct['barCode']}} @endif">
											<input type="text" id='sellingPrice' class="form-control" hidden name="sellingPrice[]" value="@if(isset($orderProduct['sellingPrice'])) {{$orderProduct['sellingPrice']}} @endif">
											
                              				 <!-- Extra Hidden Fields End-->
                						</div>
                					</div>
                				</td>
                                <!--<td><input type="text" name="title[]" placeholder="Enter title" class="form-control name_list" / id="title"></td> -->
                                <td>
									<div class="col-auto">
										<label for="cp" class="form-label">{{ __('lang.cp')}} *</label>
										<div class="input-group"> 
											<span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
									<input type="number" class="form-control name_list" name="cp[]" value="{{$orderProduct['costPrice']}}" id="cp_<?php echo $count ?>" placeholder="{{ __('lang.cp')}}" onChange="getValTotal()"  step=".01" required>
									<input type="number" class="form-control"  name="tax[]"   value="{{$orderProduct['tax']}}" id="tax_<?php echo $count ?>" hidden>
								</div>

									</div>
								</td> 
                                <td>
                                    <div class="col-auto">
                						<label for="quantity" class="form-label">{{ __('lang.quantity')}} *</label>
                						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
                							<input type="number" class="form-control border-start-0" name="quantity[]" value="{{$orderProduct['quantity']}}" id="quantity" placeholder="{{ __('lang.quantity')}}" onChange="getValTotal()"  step=".01" required>
                						</div>
            					    </div>
            					</td>
								<td>
									<div class="col-auto">
										<label for="expiryDate" class="form-label">{{ __('lang.expirydate')}}</label>
										<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-file' ></i></span>
										<input type="date" name="expiryDate[]" value="{{$orderProduct['expiryDate']}}" class="form-control border-start-0" id="expiryDate">
										</div>
									</div>
								</td>
								@if($count == 1)
								<td  style="vertical-align: bottom;"><button type="button" name="add" id="add" class="btn btn-success">{{ __('lang.addmore')}}</button></td>
								@endif
								<!--@if($count != 1)
								
								<td style="vertical-align: bottom;"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>
								@endif-->
								
                            </tr>  
							@endforeach
							  
                        </table>  
 
                    </div>				
					
					<!--
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$invoice->id}}">
						<input type="hidden" name="storeId" value = "{{$invoice->storeId}}" />
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.editinvoice')}}</button>
						<button type="reset" class="btn btn-primary px-5">{{ __('lang.reset')}}</button>
					</div> -->
					<div class="col-10"></div>
					<div class="col-2 text-primary d-flex">
						<!-- <button type="button" name="button" id="getTotal">
							Get Total
						</button> -->
						<h4  class="text-primary" style="font-size: 20px;">{{ __('lang.total')}} :
						<span id="getTotalValue">
								{{$invoice->totalAmount}}
						</span></h4>
						<input type="number" id="totalAmount" name="totalAmount" value="{{$invoice->totalAmount}}" hidden>
						<input type="number" id="vatAmount" name="vatAmount" value="{{$invoice->vatAmount}}" hidden>
						
					</div>
					 <!-- Radio Button Start -->
					 <div class="col-12">
						<label for="paymentMode" class="form-label pay-mode">Payment Mode Option*</label>
						
							<span class="radio-btn">
								<input type="radio" id="cash" name="paymentMode" value="Cash" <?php echo ($invoice->paymentMode == 'Cash') ? 'checked' : ''  ?> required>
								<label for="cash">Cash</label>
							</span> 
							<span class="radio-btn">
								<input type="radio" id="other" name="paymentMode" value="Other"  <?php echo ($invoice->paymentMode == 'Other') ? 'checked' : ''  ?> required>
								<label for="other">Other</label>
							</span>
					</div>
					<!-- Radio Button End -->
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$invoice->id}}">
							<input type="hidden" name="storeId" value = "{{$invoice->storeId}}" >
							<button type="submit" class="btn btn-primary px-5" name="status" value="Complete">{{ __('lang.createinvoice')}}</button>
							<button type="submit" class="btn btn-primary px-5" name="status" value="Pending">{{ __('lang.saveinvoice')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 <script>
const el = document.getElementById('product_search');
console.log(el); // 👉️ null

// ⛔️ Cannot read properties of null (reading 'focus')
el.focus();

</script>
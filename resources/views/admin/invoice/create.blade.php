@include('admin.layout.header')
<!--<script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){      
    var url = "{{ url('add-remove-input-fields') }}";
    var i=1;  
    $('#add').click(function(){  
    var title = $("#title").val();
    i++;  
    $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><select style="width: 100% !important;" name="productId[]" class="form-select select2" id="productId" aria-label="Example select with button addon" required><option value="">{{ __('lang.selectproduct')}}</option>@foreach($products as $key=>$product)<option value="{{$product->id}}" >{{$product->name}} @endforeach</option></select></td><td><input type="number" class="form-control name_list" name="cp[]" id="cp" placeholder="{{ __('lang.cp')}}" required></td><td><input style="border-left:1px solid lightgray !important;" type="number" class="form-control border-start-0" name="quantity[]" placeholder="{{ __('lang.quantity')}}" id="quantity" required></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
    });  
    $(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();  
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
</script>

<!--<script>
	$(document).ready(function () {

	// Denotes total number of rows
	var rowIdx = 0;
	// jQuery button click event to add a row
	$('#addBtn').on('click', function () {
	
	
		// Adding a row inside the tbody.
		$('#tbody').append(`<tr id="R${++rowIdx}">
			    <td class="row-index">
					<div class="col-auto">
						<label for="product" class="form-label">{{ __('lang.product')}} *</label>
    						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
    							<select name="productId" class="form-select select2" id="productId" aria-label="Example select with button addon" required>
    								<option value="">{{ __('lang.selectproduct')}}</option>
    									@foreach($products as $key=>$product)
    										<option value="{{$product->id}}" >{{$product->name}} </option>
    									@endforeach
    							</select>
    						</div>
					</div>
				</td>
				<td class="row-index">
					<div class="col-auto">
						<label for="cp" class="form-label">{{ __('lang.cp')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
							<input type="number" class="form-control border-start-0" name="cp" id="cp" required>
						</div>
					</div>
				</td>
    			<td class="row-index">
							<div class="col-auto">
						<label for="quantity" class="form-label">{{ __('lang.quantity')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
							<input type="number" class="form-control border-start-0" name="quantity" id="quantity" required>
						</div>
					</div>
    			</td>
						
			<td class="text-center">
				<button class="btn btn-danger remove mt-4"
				type="button">Remove</button>
				</td>
			</tr>`);
	});

	// jQuery button click event to remove a row.
	$('#tbody').on('click', '.remove', function () {

		// Getting all the rows next to the row
		// containing the clicked button
		var child = $(this).closest('tr').nextAll();

		// Iterating across all the rows
		// obtained to change the index
		child.each(function () {

		// Getting <tr> id.
		var id = $(this).attr('id');

		// Getting the <p> inside the .row-index class.
		var idx = $(this).children('.row-index').children('p');

		// Gets the row number from <tr> id.
		var dig = parseInt(id.substring(1));

		// Modifying row index.
		idx.html(`Row ${dig - 1}`);

		// Modifying row id.
		$(this).attr('id', `R${dig - 1}`);
		});

		// Removing the current row.
		$(this).closest('tr').remove();

		// Decreasing total number of rows by 1.
		rowIdx--;
	});
	});
</script>-->
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addinvoices')}}</li>
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
					<div><i class="bx bx-detail  me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.addinvoice')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('invoice.store')}}" data-toggle="validator">
				@csrf


					<div class="col-6">
						<label for="vendorId" class="form-label">{{ __('lang.selectvendor')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-home-circle'></i>
							</button>
							<select name="vendorId" class="form-select single-select" id="vendorId" aria-label="Example select with button addon" required>
								<option value="">{{ __('lang.selectvendor')}}</option>
									@foreach($vendor as $key=>$vendor)
										<option value="{{$vendor->id}}" >{{$vendor->vendorName}} </option>
									@endforeach
							</select>
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="invoiceNumber" class="form-label">{{ __('lang.invoicenumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="number" class="form-control border-start-0" name="invoiceNumber" id="invoiceNumber" required>
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="invoiceDate" class="form-label">{{ __('lang.invoicedate')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="date" class="form-control border-start-0" name="invoiceDate" id="invoiceDate" required>
						</div>
					</div>

					
					<!--<div class="row">
    			         <table class="table">									
    						<tbody id="tbody">
    							<td class="row-index">
            					<div class="col-auto">
            						<label for="product" class="form-label">{{ __('lang.product')}} *</label>
            						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
            							<select name="productId[]" class="form-select select2" id="productId" aria-label="Example select with button addon" required>
            								<option value="">{{ __('lang.selectproduct')}}</option>
            									@foreach($products as $key=>$product)
            										<option value="{{$product->id}}" >{{$product->name}} </option>
            									@endforeach
            							</select>
            						</div>
            					</div>
            				</td>
            				<td class="row-index">
            					<div class="col-auto">
            						<label for="cp" class="form-label">{{ __('lang.cp')}} *</label>
            						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
            							<input type="number" class="form-control border-start-0" name="cp[]" id="cp" required>
            						</div>
            					</div>
            				</td>
            				<td class="row-index">
            					<div class="col-auto">
            						<label for="quantity" class="form-label">{{ __('lang.quantity')}} *</label>
            						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
            							<input type="number" class="form-control border-start-0" name="quantity[]" id="quantity" required>
            						</div>
            					</div>
            				</td>
            				<td class="text-center">
                				<button class="btn btn-md btn-primary ml-3 mt-3" id="addBtn" type="button">{{ __('lang.addproduct')}}</button>
                				</td>
                			</tbody>
    					</table>
    					
                	</div>-->
                	
                	<div class="table-responsive">  
                        <table class="table table-bordered" id="dynamic_field"> 
                            <tr> 
                            
            					<td>
                			         <div class="col-auto">
                				    	<label for="product" class="form-label">{{ __('lang.product')}} *</label>
                						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
                							<select name="productId[]" class="form-select select2" id="productId" aria-label="Example select with button addon" required>
                								<option value="">{{ __('lang.selectproduct')}}</option>
                									@foreach($products as $key=>$product)
                										<option value="{{$product->id}}" >{{$product->name}} </option>
                									@endforeach
                							</select>
                						</div>
                					</div>
                				</td>
                                <!--<td><input type="text" name="title[]" placeholder="Enter title" class="form-control name_list" / id="title"></td> -->
                                <td><div class="col-auto"><label for="cp" class="form-label">{{ __('lang.cp')}} *</label><div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span><input type="number" class="form-control name_list" name="cp[]" id="cp" required></div></div></td> 
                                <td>
                                    <div class="col-auto">
                						<label for="quantity" class="form-label">{{ __('lang.quantity')}} *</label>
                						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
                							<input type="number" class="form-control border-start-0" name="quantity[]" id="quantity" required>
                						</div>
            					    </div>
            					</td>
								<td>
									<div class="col-auto">
										<label for="expiryDate" class="form-label">{{ __('lang.expirydate')}}*</label>
										<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-file' ></i></span>
										<input type="date" name="expiryDate" value="" class="form-control border-start-0" id="expiryDate">
										</div>
									</div>
								</td>

                                <td  style="vertical-align: middle;"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                            </tr>  
                        </table>  
 
                    </div>
                    	<div class="col-12">
					    <input type="hidden" name="storeId" value = "{{request()->route('id')}}" >
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.addinvoice')}}</button>
					</div>
                    </form>  
                    </div> 
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>

			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 
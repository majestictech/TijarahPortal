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
    .ui-autocomplete-input {
      width: 200px;
    border-radius: 5px !important;
    }
    .ui-menu-item-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        margin-left: -33px !important;
        text-align: center !important;
      }

    .ui-menu-item>div:hover {
      border: none;
      color: #fff;
      background: #157d4c !important;
    }

    .ui-menu  {
			width: 100px;
      list-style: none;
	}
  
  .ui-helper-hidden-accessible {
      display: none;
    }
    .ui-widget-content {
      background: #fff !important;
    }
    .ui-menu {
      cursor: pointer;
   }
   .ui-menu:hover {
     border: 1px solid green;
   }

  </style>
  <script>
  $( function() {
    //var availableTags = [
	var availableTags= @json($Products);
	print_r(availableTags);
     
    //];
     $( "#tags" ).autocomplete({
      source: availableTags
    }); 
  } );
  </script>
</head>
<!--<script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->
<script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script> 
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function(){      
    //var url = "{{ url('add-remove-input-fields') }}";
    var i=1;  
    $('#add').click(function(){  
    var title = $("#title").val();
    i++;  
    $('#dynamic_field').append('<tr id="row_'+i+'" class="dynamic-added"><td><div class="input-group" style="flex-wrap: initial;"><span class="input-group-text bg-transparent"><i class="bx bx-id-card"></i></span><input type="text" id="product_search'+i+'" data-rowindex="'+i+'"><input type="text" id="productId'+i+'" hidden name="productId[]"><input type="text" id="productName'+i+'" class="form-control" hidden name="productName[]"><input type="text" id="productNameAr'+i+'" class="form-control" hidden name="productNameAr[]"><input type="text" id="barCode'+i+'" class="form-control" hidden name="barCode[]"><input type="text" id="sellingPrice'+i+'" class="form-control" hidden name="sellingPrice[]"></span></div></td><td><input type="number" class="form-control name_list costing cp" name="cp[]" value="" id="cp_' + i + '" placeholder="{{ __('lang.cp')}}" onChange="getValTotal()"  step=".01" required><input type="number"  value="" id="tax_'+ i + '" class="form-control tax"  name="tax[]"  hidden></td><td><input style="border-left:1px solid lightgray !important;" type="number" class="form-control border-start-0 quantity" name="quantity[]" placeholder="{{ __('lang.quantity')}}" id="quantity_'+ i + '" onChange="getValTotal()"  step=".01" required></td><td><input style="border-left:1px solid lightgray !important;" type="date" class="form-control border-start-0" name="expiryDate[]" placeholder="{{ __('lang.expirydate')}}" id="expiryDate" ></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>'); 
	//var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
	//alert(i);
	//$( "#product_search" ).autocomplete({
	$( '#product_search'+i ).autocomplete({
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
		  console.log('#product_search'+i);
          var id = this.getAttribute('data-rowindex');
		  console.log(('#product_search'+id));
		  console.log(('#productId'+id));
          $('#product_search'+id).val(data.item.label); // display the selected text
          $('#productId'+id).val(data.item.id); // save selected id to input
          $('#productName'+id).val(data.item.label); // save selected text to input for Product Name
          $('#productNameAr'+id).val(data.item.name_ar); // save selected text to input for Product Name Arabic
          $('#barCode'+id).val(data.item.barCode); // save selected text to input for barCode
          $('#sellingPrice'+id).val(data.item.sellingPrice); // save selected text to input for sellingPrice
          $('#tax'+id).val(data.item.tax); // save selected text to input for tax 
		  var price = data.item.value;
		  var price = data.item.value;
		  var tax = data.item.tax;
		  var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
			var rowVal = rowIndex+1;
			//alert(rowVal);
			//alert(++rowIndex);
			//alert($('#cp_'+rowIndex).html());
			
			$('#cp_'+id).val(price);
			$('#tax_'+id).val(tax);
      $('#quantity_'+id).val(1);
			getValTotal();
          return false;
        }
     });
		
	$(document).ready(function(){
		
    function getVal1(){
        var price = $('.productName').children('option:selected').data('cost');
      
		var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
		var rowVal = rowIndex+1;
		//alert(price);
		//alert(++rowIndex);
		//alert($('#cp_'+rowIndex).html());
		
		$('#cp_'+rowVal).val(price);
		$('#tax_'+rowVal).val(tax);
    $('#quantity_'+rowVal).val(1);
			getValTotal();
    }
    $(".productName").on("change", getVal1);
    getValTotal();
  });
});
	
	
    $(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");
	//alert(button_id);
    $('#row_'+button_id+'').remove();  
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
		//alert(typeof(cp));
		var total = 0;
		var vatTotal = 0;
		for(let i=0; i<cp.length; i++) {
    //alert(typeof(cp[i]));

      var totalPre = 0;
			totalPre = parseFloat(cp[i]) * parseFloat(quantity[i]);
     
			if(isNaN(totalPre)) {
				var totalPre = 0;
			}

     // alert(typeof(totalPre));
      //alert(typeof(total));
			total = total + totalPre;
			total = total.toFixed(2);
			total = parseFloat(total);
     // alert(total);
      //alert(typeof(total));
      /* Vat Amount Start */
      
      //(p.costPrice - (p.costPrice/(1+p.tax/100)))*p.quantity;

      vatTotalPre =  ( parseFloat(cp[i]) - ( parseFloat(cp[i])/(1+(parseFloat(tax[i])/100))) )*parseFloat(quantity[i]);
      if(isNaN(vatTotalPre)) {
				var vatTotalPre = 0;
			}
      vatTotal = vatTotal+ vatTotalPre;

      //alert(vatTotal);

      vatTotal = vatTotal.toFixed(2);
			vatTotal = parseFloat(vatTotal);
      //alert(vatTotalPre.toFixed(2));

      /* Vat Amount End */
     
      
		}
		//alert(total);
		$(document).ready(function () {
      /* $('input').change(function(){

		    $("#getTotalValue").text(total);
		  }); */
    //  $(":input").on("keyup change click", function() {
          // do stuff!
         $("#getTotalValue").text(total);
         //$("#totalAmount").val(total);
         $('#totalAmount').attr('value', total)
         $('#vatAmount').attr('value', vatTotal)
     // })
		});
		//alert(total);
		
	}
	/* Total Amount and Vat Total Start */

  
/* Search Bar Auto Suggestion Start */
	/* let names = [
  "Ayla",
  "Jake",
  "Sean",
  "Henry",
  "Brad",
  "Stephen",
  "Taylor",
  "Timmy",
  "Cathy",
  "John",
  "Amanda",
  "Amara",
  "Sam",
  "Sandy",
  "Danny",
  "Ellen",
  "Camille",
  "Chloe",
  "Emily",
  "Nadia",
  "Mitchell",
  "Harvey",
  "Lucy",
  "Amy",
  "Glen",
  "Peter",
];
//Sort names in ascending order
let sortedNames = names.sort();

//reference
let input = document.getElementById("input");

//Execute function on keyup
input.addEventListener("keyup", (e) => {
  //loop through above array
  //Initially remove all elements ( so if user erases a letter or adds new letter then clean previous outputs)
  removeElements();
  for (let i of sortedNames) {
    //convert input to lowercase and compare with each string

    if (
      i.toLowerCase().startsWith(input.value.toLowerCase()) &&
      input.value != ""
    ) {
      //create li element
      let listItem = document.createElement("li");
      //One common class name
      listItem.classList.add("list-items");
      listItem.style.cursor = "pointer";
      listItem.setAttribute("onclick", "displayNames('" + i + "')");
      //Display matched part in bold
      let word = "<b>" + i.substr(0, input.value.length) + "</b>";
      word += i.substr(input.value.length);
      //display the value in array
      listItem.innerHTML = word;
      document.querySelector(".list").appendChild(listItem);
    }
  }
});
function displayNames(value) {
  input.value = value;
  removeElements();
}
function removeElements() {
  //clear all the item
  let items = document.querySelectorAll(".list-items");
  items.forEach((item) => {
    item.remove();
  });
} */


/*

$(document).ready(function(){
                $("#autocomplete").autocomplete({
                   //source: [{"name":"an apple"},{"name":"a peach"},{"name":"an orange"}],
				   	//source: [{"name":"Crayon","id":53,"costPrice":null},{"name":"Deo","id":52,"costPrice":null},{"name":"Pencil","id":50,"costPrice":null}],
					
				   //data: @json($Products),
				   
					source: @json_encode($Products),
                    minLength: 1,
                    open: function( event, ui ) {
                     console.log('open',event,ui,this);
                    }
                }).data("uiAutocomplete")._renderItem = function (ul, item) {
					//item.push('value');
					//item.push("blue","yellow");

                    console.log('renderItem', item);
                    $(ul).addClass("whatyouneed");
                    var html = "<a>" + item.name + "</a>";
					//var html = '<a>' + item.name  + '<br>' + item.value  + '</a><br><br>'
                    return $("<li></li>").data("item.autocomplete", item.name).append(html).appendTo(ul);
					       

                };
            });
/* Search Bar Auto Suggestion End */
</script>
<!--
<script>
$( function() {
    var projects = @json($Products);
 
    $( "#project" ).autocomplete({
      minLength: 1,
      source: projects,
      focus: function( event, ui ) {
        $( "#project" ).val( ui.item );
		console.log('open',event,ui,this);
        return false;
      },
      select: function( event, ui ) {
        $( "#project" ).val( ui.item.name );
        $( "#project-id" ).val( ui.item.costPrice );

 
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<div>" + item.name+ "</div>" )
        .appendTo( ul );
    };
  } );
</script>
-->

<script type="text/javascript">

   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


$(document).ready(function(){
    function getVal(){
        var price = $('.productId').children('option:selected').data('cost');
        //var tax = $('.productId').children('option:selected').data('cost');
        var rowIndex = $('.productId').children('option:selected').data('rowindex');
        //var rowIndex = $('.productId').children('option:selected').val();
		//var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
		//var rowVal = rowIndex+1;
		//alert(price);
		//alert(rowIndex);
		//alert($('#cp'+ rowIndex).html());
		
		$('#cp_'+rowIndex).val(price);
		$('#tax_'+rowIndex).val(tax);
    $('#quantity_'+rowIndex).val(1);
			getValTotal();
    }
    $(".productId").on("change", getVal);
	
	
	
	$( "#product_search" ).autocomplete({
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
          $('#product_search').val(data.item.label); // display the selected text
          $('#productId').val(data.item.id); // save selected id to input
          $('#productName').val(data.item.label); // save selected id to input
          $('#productNameAr').val(data.item.name_ar); // save selected id to input
          $('#barCode').val(data.item.barCode); // save selected id to input
          $('#sellingPrice').val(data.item.sellingPrice); // save selected id to input
          $('#tax').val(data.item.tax); // save selected id to input
         // $('#totalAmount').val(data.item.total); // save selected id to input
		  var price = data.item.value;
		  var tax = data.item.tax;
		  var rowIndex = $('#dynamic_field tr').index($(this).closest('tr'));
			var rowVal = rowIndex+1;
			//alert(price);
			//alert(++rowIndex);
			//alert($('#cp_'+rowIndex).html());
			
			$('#cp_'+rowVal).val(price);
			$('#tax_'+rowVal).val(tax);
      $('#quantity_'+rowVal).val(1);
			getValTotal();
          return false;
        }
     });
     $( "#product_search" ).autocomplete({ minLength:3 });
	
});



</script>
                            

<style>

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
  
        input[type=number] {
            -moz-appearance: textfield;
        }

</style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}">
          <i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}">
          <i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
          <a class="text-primary" href="{{url('/admin/invoice').'/'.$storeId	}}">
            <i class="bx bx-detail "></i> {{ __('lang.allinvoices')}}
          </a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
          <i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addinvoice')}}</li>
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
        @if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf


					<div class="col-4">
						<label for="vendorId" class="form-label">{{ __('lang.selectvendor')}} *</label>
						<label for="vendorId" class="form-label float-end"><a href="{{url('admin/vendor/create').'/'.$storeId}}" class="text-primary fw-bold"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addvendor')}}	</a>
						</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-home-circle'></i>
							</button>
							<select name="vendorId" class="form-select single-select" id="vendorId" aria-label="Example select with button addon" required>
								<option value="">{{ __('lang.selectvendor')}}</option>
									@foreach($vendors as $key=>$vendor)
										<option value="{{$vendor->id}}" >{{$vendor->vendorName}} </option>
									@endforeach
							</select>
						</div>
					</div>
					
					<div class="col-md-4">
						<label for="invoiceNumber" class="form-label">{{ __('lang.invoicenumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" class="form-control border-start-0" name="invoiceNumber" id="invoiceNumber" placeholder="{{ __('lang.invoicenumber')}}" required>
						</div>
					</div>
					
					<div class="col-md-4">
						<label for="invoiceDate" class="form-label">{{ __('lang.invoicedate')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="date" class="form-control border-start-0" name="invoiceDate" id="invoiceDate" required>
						</div>
					</div>

					
					
                	
                	<div class="table-responsive">  
                      <table class="table table-bordered" id="dynamic_field"> 
                        <tr class="product_1"> 
                            
                          <td>
                                <div class="col-auto">
                                <label for="product" class="form-label">{{ __('lang.product')}} *</label>
                                    <div class="input-group" style="flex-wrap: initial;"> 
                              <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
                              
                                      <!--<select name="productId[]" class="form-select productId" id="productId" aria-label="Example select with button addon" required>
                                        <option value="">{{ __('lang.selectproduct')}}</option>
                                          @foreach($products as $key=>$product)
                                            <option value="{{$product->id}}" class="productName" data-rowindex="1" data-cost="{{$product->costPrice}}">{{$product->name}} </option>
                                    <input type="hidden" name="productCost" value = "{{$product->costPrice}}" >
                                          @endforeach
                                      </select>
                              <input id="project">
                              <input type="hidden" id="project-id">-->
                              <input type="text" id='product_search' class="form-control" style="border-radius: 5px; border-top-left-radius: 0; border-bottom-left-radius: 0;}">
                              <input type="text" id='productId' class="form-control" hidden name="productId[]">
                              <!-- Extra Hidden Fields Start-->
                              <input type="text" id='productName' class="form-control" hidden name="productName[]">
                              <input type="text" id='productNameAr' class="form-control" hidden name="productNameAr[]">
                              <input type="text" id='barCode' class="form-control" hidden name="barCode[]">
                              <input type="text" id='sellingPrice' class="form-control" hidden name="sellingPrice[]">
                           <!--    <input type="text" id='tax' class="form-control" hidden name="tax[]" value="0"> -->
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
                                  <input type="number" step=".01" class="form-control name_list cp" value="" name="cp[]" id="cp_1" placeholder="{{ __('lang.cp')}}" onChange="getValTotal()" required>
                                  <input type="number"  class="form-control border-start-0 tax" name="tax[]" id="tax_1" placeholder="{{ __('lang.tax')}}"  onChange="getValTotal()" value="" hidden>
                                   
                                </div>
                              </div>
                            </td> 
                            <td>
                                <div class="col-auto">
                                  <label for="quantity" class="form-label">{{ __('lang.quantity')}} *</label>
                                  <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
                                    <input type="number" step=".01" class="form-control border-start-0 quantity" name="quantity[]" id="quantity_1" placeholder="{{ __('lang.quantity')}}"  onChange="getValTotal()" required>
                                    
                                  </div>
                                </div>
                            </td>
                            <td>
                              <div class="col-auto">
                                <label for="expiryDate" class="form-label">{{ __('lang.expirydate')}}</label>
                                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-file' ></i></span>
                                <input type="date" name="expiryDate[]" class="form-control border-start-0" id="expiryDate" >
                                </div>
                              </div>
                            </td>

                            <td  style="vertical-align: bottom;">
                              <button type="button" name="add" id="add" class="btn btn-success">{{ __('lang.addmore')}}</button>
                            </td>
                             
                              
                        </tr> 
                         
                        
                      </table>  
            
                </div>
					<div class="col-10"></div>
					<div class="col-2 text-primary d-flex">
						<!-- <button type="button" name="button" id="getTotal">
							Get Total
						</button> -->
						<h4  class="text-primary" style="font-size: 20px;">{{ __('lang.total')}} :
						  <span id="getTotalValue"></span>
            </h4>
            <input type="number" id="totalAmount" name="totalAmount" value="0" hidden>
            <input type="number" id="vatAmount" name="vatAmount" value="0" hidden>
						
					</div>
          <!-- Radio Button Start -->
          <div class="col-12">
            <label for="paymentMode" class="form-label pay-mode">Payment Mode Option*</label>
                    
                            <span class="radio-btn">
                              <input type="radio" id="cash" name="paymentMode" value="Cash" required>
                              <label for="cash">Cash</label>
                            </span> 
                        <span class="radio-btn">
                              <input type="radio" id="other" name="paymentMode" value="other" required>
                              <label for="other">Other</label>
                        </span>
                    
          </div>
          <!-- Radio Button End -->
          <div class="col-12">
            <input type="hidden" name="storeId" value = "{{request()->route('id')}}" >
            <button type="submit" class="btn btn-primary px-5" name="status" value="Complete">{{ __('lang.createinvoice')}}</button>
            <button type="submit" class="btn btn-primary px-5" name="status" value="Pending">{{ __('lang.saveinvoice')}}</button>
          </div>
                </form>  
			</div>
		</div>		
	</div>
</div>

<!--end row-->

<!-- <script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script> -->

@include('admin.layout.footer')
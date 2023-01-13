@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('globalproducts_manage','globalproducts_edit');
?>
<script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
</script>
<script src=
"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js">
</script>
<script src=
"https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
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
						<label for="variation" class="form-label">{{ __('lang.variation')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
							<input type="number" name="variation" class="form-control border-start-0" id="variation" />
						</div>
					</div>
					</td>
    						
                        <td class="row-index">
						<div class="col-auto">
							<label for="weightClassId" class="form-label">{{ __('lang.variationunit')}}</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-map'></i>
								</button>
								<select name="weightClassId" class="form-select single-select" id="weightClassId" aria-label="Example select with button addon" data-error="{{ __('lang.req_weight')}}" required>
									<option value="">{{ __('lang.weightclass')}}</option>
										@foreach($weightdata as $key=>$Weighttype)
											<option value="{{$Weighttype->id}}" >{{$Weighttype->name}}</option>
										@endforeach
								</select>
							</div>
						</div>
    						</td>
    						
    						<td class="row-index">
    						<div class="col-auto">
    							<label for="var_price" class="form-label">{{ __('lang.price')}}</label>
    							<div class="input-group"> <span class="input-group-text bg-transparent">SAR</span>
    								<input type="number" step="any" name="var_price" class="form-control border-start-0" id="var_price" placeholder="{{ __('lang.enterprice')}}" required>
    							</div>
    						</div>
    						</td>
    						
    						
    						<td class="row-index">
    						<div class="col-auto">
    							<label for="minInventory" class="form-label">{{ __('lang.mininventory')}}</label>
    							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
    								<input type="number" name="minInventory" class="form-control border-start-0" id="minInventory" />
    							</div>
    						</div>
    						</td>
    						
    						<td class="row-index">
    						
    						<div class="col-auto">
    							<label for="quantity" class="form-label">{{ __('lang.inventory')}}</label>
    							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
    								<input type="number" name="inventory" class="form-control border-start-0" id="inventory" />
    							</div>
    						</div>
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
</script>
-->
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>

				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-globe"></i> {{ __('lang.editglobalproduct')}}</li>
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
					<div><i class="bx bx-globe me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.editglobalproduct')}}</h5>
				</div>
				<hr>
                        <form class="row g-3 pt-3" method="post" enctype="multipart/form-data" action="{{route('globalproducts.update')}}" data-toggle="validator">
						@if($errors->any())
						<h4 class="error_msg">{{$errors->first()}}</h4>
						@endif
						@csrf
						<div class="col-md-12">
							<label for="productname" class="form-label">{{ __('lang.productname')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-cube'></i></span>
								<input type="text" autofocus="autofocus" onfocus="this.setSelectionRange(this.value.length,this.value.length);" value="{{$ProductData->name}}" name="name" class="form-control border-start-0" id="productname" placeholder="{{ __('lang.enterproductname')}}" required>
							</div>
						</div>
						<div class="col-md-12">
							<label for="productname_ar" class="form-label">{{ __('lang.productname_ar')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-cube'></i></span>
								<input type="text" name="name_ar" value="{{$ProductData->name_ar}}" class="form-control border-start-0" id="productname_ar" placeholder="{{ __('lang.enterproductname_ar')}}" required>
							</div>
						</div>
					<!--	<div class="col-md-12">
							<label for="productname_ar" class="form-label">{{ __('lang.productname_ar')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
								<input type="text" name="name_ar" value="{{$ProductData->name_ar}}" class="form-control border-start-0" id="productname_ar" placeholder="{{ __('lang.enterproductname_ar')}}" />
							</div>
						</div>
						<div class="col-md-12">
							<label for="productname_ur" class="form-label">{{ __('lang.productname_ur')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
								<input type="text" name="name_ur" value="{{$ProductData->name_ur}}" class="form-control border-start-0" id="productname_ur" placeholder="{{ __('lang.enterproductname_ur')}}" />
							</div>
						</div>
						<div class="col-md-12">
							<label for="productname_ml" class="form-label">{{ __('lang.productname_ml')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
								<input type="text" name="name_ml" value="{{$ProductData->name_ml}}" class="form-control border-start-0" id="productname_ml" placeholder="{{ __('lang.enterproductname_ml')}}" />
							</div>
						</div>
						<div class="col-md-12">
							<label for="productname_bn" class="form-label">{{ __('lang.productname_bn')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
								<input type="text" name="name_bn" value="{{$ProductData->name_bn}}" class="form-control border-start-0" id="productname_bn" placeholder="{{ __('lang.enterproductname_bn')}}" />
							</div>
						</div>
						<div class="col-md-6">
							<label for="code" class="form-label">{{ __('lang.code')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
								<input type="text" name="code" value="{{$ProductData->code}}" class="form-control border-start-0" id="code" placeholder="{{ __('lang.entercode')}}" />
							</div>
						</div>-->
						<div class="col-md-6">
							<label for="barCode" class="form-label">{{ __('lang.barcode')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-barcode'></i></span>
								<input type="text" name="barCode" value="{{$ProductData->barCode}}" class="form-control border-start-0" id="barCode" placeholder="{{ __('lang.enterbarcode')}}" required>
							</div>
						</div>
						<!--<div class="col-md-6">
							<label for="category" class="form-label">{{ __('lang.category')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-category'></i></span>
								<?php echo $categoryList; ?>
							</div>
						</div>-->
						<div class="col-6">
							<label for="catgeories" class="form-label">{{ __('lang.category')}}</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-bold'></i>
								</button>
								<select name="categoryId" class="form-select single-select" id="categoryId" aria-label="Example select with button addon" data-error="{{ __('lang.req_brand')}}">
									<option value="">{{ __('lang.category')}}</option>
										@foreach($categoryList as $key=>$category)
											<option value="{{ $category->id }}" {{ ( $category->id == $ProductData->categoryId) ? 'selected' : '' }} >{{$category->name}} </option>
										@endforeach
								</select>
							</div>
						</div>
						
					
						
						<div class="col-md-6">
							<label for="price" class="form-label">{{ __('lang.sellingprice')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent">SAR</span>
								<input type="number" step="any" name="sellingPrice" value="{{$ProductData->sellingPrice}}" class="form-control border-start-0" id="sellingPrice" placeholder="{{ __('lang.entersellingprice')}}" required>
							</div>
						</div>
						<div class="col-md-6">
							<label for="costPrice" class="form-label">{{ __('lang.costprice')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent">SAR</span>
								<input type="number" step="any" name="costPrice" value="{{$ProductData->costPrice}}" class="form-control border-start-0" id="costPrice" placeholder="{{ __('lang.entercostprice')}}" />
							</div>
						</div>
						<div class="col-6">
							<label for="brands" class="form-label">{{ __('lang.brands')}}</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-bold'></i>
								</button>
								<select name="brandId" class="form-select single-select" id="brandId" aria-label="Example select with button addon" data-error="{{ __('lang.req_brand')}}" >
									<option value="">{{ __('lang.brandclass')}}</option>
										@foreach($brands as $key=>$value)
											<option value="{{ $value->id }}" {{ ( $value->id == $ProductData->brandId) ? 'selected' : '' }} >{{$value->brandName}} </option>
										@endforeach
								</select>
							</div>
						</div>

						<div class="col-6">
							<label for="tax" class="form-label">{{ __('lang.vat')}} *</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-box'></i>
								</button>
								
								<select name="taxClassId" class="form-select single-select" id="taxClassId" aria-label="Example select with button addon" data-error="{{ __('lang.req_brand')}}" required>
    								@foreach($taxdata as $key=>$value)
    									<option value="{{ $value->id }}" {{ ( $value->id == $ProductData->taxClassId) ? 'selected' : '' }}> 
    										{{ $value->name }} ({{ $value->value }}% )
    									</option>
    								@endforeach
								</select>
							</div>
						</div>
					<!--	<div class="col-3">
							<label for="weight" class="form-label">{{ __('lang.weight')}} *</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-body' ></i></span>
								<input type="number" step="any" name="weight" value="{{$ProductData->weight}}" class="form-control border-start-0" id="weight" placeholder="{{ __('lang.to')}}" required>
							</div>
						</div>
						<div class="col-4">
							<label for="weightClassId" class="form-label">{{ __('lang.weightclass')}} *</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-body'></i>
								</button>
								<select name="weightClassId" class="form-select single-select" id="weightClassId" aria-label="Example select with button addon" data-error="{{ __('lang.req_weight')}}" required>
									<option value="">{{ __('lang.weightclass')}}</option>
										@foreach ($weightdata as $key => $value)
											<option value="{{ $value->id }}" {{ ( $value->id == $ProductData->weightClassId) ? 'selected' : '' }}> 
												{{ $value->name }} 
											</option>
										  @endforeach 
								</select>
							</div>
						</div>-->
						<div class="col-6">
    						<label for="minInventory" class="form-label">{{ __('lang.mininventory')}}</label>
    						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-check-circle' ></i></span>
    							<input type="number" name="minInventory" value="{{$ProductData->minInventory}}" class="form-control border-start-0" id="minInventory" />
    						</div>
    				    </div>
    					<div class="col-6">
    						<label for="quantity" class="form-label">{{ __('lang.inventory')}}</label>
    						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-check-circle' ></i></span>
    					    	<input type="number" name="inventory" value="{{$ProductData->inventory}}" class="form-control border-start-0" id="inventory" />
    						</div>
    					</div>
						<!--<div class="col-6">
							<label for="minorderquantity" class="form-label">{{ __('lang.minorderquantity')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
								<input type="number" name="minOrderQty" value="{{$ProductData->minOrderQty}}" class="form-control border-start-0" id="minorderquantity" placeholder="{{ __('lang.enterminorderquantity')}}" />
							</div>
						</div>
						<div class="col-md-6">
						<div class="form-group ">
							  <label class="form-label">{{ __('lang.status')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="available" value="Available" <?php if(isset($ProductData)){ if('Available' == $ProductData->status){ echo "checked=checked"; } } ?> name="status">
							  <label class="mb-0" for="available">
								{{ __('lang.available')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="Not Available" id="not_available" <?php if(isset($ProductData)){ if('Not Available' == $ProductData->status){ echo "checked=checked"; } } ?> name="status">
							  <label class="mb-0" for="not_available">
								{{ __('lang.notavailable')}}
							  </label>
						</div>
					</div>-->
						
						
						<div class="col-12">
							<label for="image" class="form-label">{{ __('lang.image')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-file' ></i></span>
								<input type="file" value="{{$ProductData->productImage}}" name="productImage" class="form-control border-start-0" id="image"  />
							</div>
							<div class="col-md-2 row pt-4">
								<!--<img src="{{URL::asset('public/products').'/'.$ProductData->productImage}}" width="100%">-->
								<img src="data:image/png;base64,{{$ProductData->productImgBase64}}">
							</div>
						</div>
					
						
						
						<!--<div class="col-md-12">
							<label for="productdescdetails" class="form-label">{{ __('lang.productdescdetails')}}</label>
							<textarea class="form-control" name="description" id="descriptionml" rows="4">{{$ProductData->description}}</textarea>
						</div>
						<div class="col-md-12">
							<label for="productdescdetails_ar" class="form-label">{{ __('lang.productdescdetails_ar')}}</label>
							<textarea class="form-control" name="desArabic" id="productdescdetails_ar" rows="4">{{$ProductData->desArabic}}</textarea>
						</div>
						<div class="col-md-12">
							<label for="productdescdetails_ur" class="form-label">{{ __('lang.productdescdetails_ur')}}</label>
							<textarea class="form-control" name="desUrdu" id="productdescdetails_ur" rows="4">{{$ProductData->desUrdu}}</textarea>
						</div>
						<div class="col-md-12">
							<label for="productdescdetails_ml" class="form-label">{{ __('lang.productdescdetails_ml')}}</label>
							<textarea class="form-control" name="desMalay" id="productdescdetails_ml" rows="4">{{$ProductData->desMalay}}</textarea>
						</div>
						<div class="col-md-12">
							<label for="productdescdetails_bn" class="form-label">{{ __('lang.productdescdetails_bn')}}</label>
							<textarea class="form-control" name="desBengali" id="productdescdetails_bn" rows="4">{{$ProductData->desBengali}}</textarea>
						</div>
						<div class="col-6">
							<label for="producttags" class="form-label">{{ __('lang.producttags')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
								<input type="text" name="productTags" class="form-control border-start-0" value="{{$ProductData->productTags}}" id="producttags" placeholder="{{ __('lang.enterproducttags')}}" />
							</div>
						</div>
						<div class="col-6">
							<label for="metatagtitle" class="form-label">{{ __('lang.metatagtitle')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
								<input type="text" name="metaTitle" value="{{$ProductData->metaTitle}}" class="form-control border-start-0" id="metatagtitle" placeholder="{{ __('lang.enterproducttags')}}" />
							</div>
						</div>
						<div class="col-6">
							<label for="metatagdescription" class="form-label">{{ __('lang.metatagdescription')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
								<input type="text" value="{{$ProductData->metaDescription}}" name="metaDescription" class="form-control border-start-0" id="metatagdescription" placeholder="{{ __('lang.metatagdescription')}}" />
							</div>
						</div>
						
						<div class="col-6">
							<label for="metatagkeyword" class="form-label">{{ __('lang.metatagkeyword')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
								<input type="text" name="metaKeyword" value="{{$ProductData->metaKeyword}}" class="form-control border-start-0" id="metatagkeyword" placeholder="{{ __('lang.metatagkeyword')}}" />
							</div>
						</div>-->
						<div class="col-6">
    						<div class="form-group ">
    							  <label>{{ __('lang.status')}}</label><br/>
    							  <input class="radio-input mr-2" type="radio" id="available" value="Available" name="status" <?php if(isset($ProductData)){ if('Available' == $ProductData->status){ echo "checked"; } } ?>>
    							  <label class="mb-0" for="yes">
    								{{ __('lang.available')}}
    							  </label>
    							
    							  <input class="checkbox-input mr-2 ml-2" type="radio" value="Not Available" id="notavailable" name="status" <?php if(isset($ProductData)){ if('Not Available' == $ProductData->status){ echo "checked"; } } ?>>
    							  <label class="mb-0" for="no">
    								{{ __('lang.notavailable')}}
    							  </label>
    						</div>
						</div>
						
						<div class="col-md-6">
        					<div class="form-group ">
    							  <label>{{ __('lang.looseitem')}}</label><br/>
    							  <input class="radio-input mr-2" type="radio" id="looseyes" value="Yes" name="looseItem" <?php if(isset($ProductData)){ if('Yes' == $ProductData->looseItem){ echo "checked"; } } ?>>
    							  <label class="mb-0" for="yes">
    								{{ __('lang.yes')}}
    							  </label>
    							
    							  <input class="checkbox-input mr-2 ml-2" type="radio" value="No" id="looseno" name="looseItem" <?php if(isset($ProductData)){ if('No' == $ProductData->looseItem){ echo "checked"; } } ?>>
    							  <label class="mb-0" for="no">
    								{{ __('lang.no')}}
    							  </label>
    						</div>
    					</div>
						
						
						
                       	<div class="col-12">
							<!--<input type="hidden" name="storeId" value = "{{request()->route('id')}}" //>-->
							<input type="hidden" name="id" value = "{{$ProductData->id}}">
							<button type="submit" class="btn btn-secondary px-5">{{ __('lang.editglobalproduct')}}</button>
						</div>							
                        </form>
                   
                </div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')  

@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
                <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.productdetails')}}</h4>
                        <p class="mb-0">{{ __('lang.productdetailsdesc')}}</p>
                    </div>
                    <a href="{{url('/admin/product')}}" class="btn btn-primary add-list">{{ __('lang.productlist')}}</a>
                </div>
            </div>
			
			   <div class="col-lg-9">
			  <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                 
                    <tbody >
					
                        <tr class="table-active">
                            <td><b>Product</b></td> 
                            <td>{{$ProductData->name}}</td> 
							
						</tr>
						<tr class="table-active">
                            <td><b>Product Category</b></td> 
                            <td>{{$ProductData->catName}}</td> 
							
						</tr>
						<tr >
                            <td><b>Product(Arabic)</b></td> 
                            <td>{{$ProductData->name_ar}}</td> 
							</div>
						</tr>
						<tr class="table-active">
                            <td><b>Product(Urdu)</b></td> 				
                            <td>{{$ProductData->name_ur}}</td> 
				
						</tr>
						<tr >
                            <td><b>Product(Malayalam)</b></td> 
                            <td>{{$ProductData->name_ml}}</td> 
					
						</tr>
						<tr class="table-active">
                            <td><b>Product(Bengali)</b></td> 
                            <td>{{$ProductData->name_bn}}</td> 
						</tr>
						
						<tr>
						<td><b>Product Image</b></td> 
						<td><img src="{{URL::asset('public/products').'/'.$ProductData->productImage}}"  class="img-fluid rounded avatar-100" alt="image"></td>
						</tr>
						
						<!--<tr>
                            <td><b>Code</b></td> 
                            <td>{{$ProductData->code}}</td> 
						</tr>-->
						<tr class="table-active">
                            <td><b>BarCode</b></td> 
                            <td>{{$ProductData->barCode}}</td> 
						</tr>
						<tr>
                            <td><b>Price</b></td> 
                            <td>SAR {{$ProductData->price}}</td> 
						</tr>
						<!--<tr class="table-active">
                            <td><b>Min. Order Quantity</b></td> 
                            <td>{{$ProductData->minOrderQty}}</td> 
						</tr>-->
						<tr>
                            <td><b>Description</b></td> 
                            <td>{{$ProductData->description}}</td> 
						</tr>
						
						<tr class="table-active">
                            <td><b>Description (Arabic)</b></td> 
                            <td>{{$ProductData->desArabic}}</td> 
						</tr>
						
						<tr>
                            <td><b>Description (Urdu)</b></td> 
                            <td>{{$ProductData->desUrdu}}</td> 
						</tr>
						
						<tr class="table-active">
                            <td><b>Description (Malayalam)</b></td> 
                            <td>{{$ProductData->desMalay}}</td> 
						</tr>
						
						<tr>
                            <td><b>Description (Bengali)</b></td> 
                            <td>{{$ProductData->desBengali}}</td> 
						</tr>
						
						
						                          
                    </tbody>
                </table>
                </div>
      
            </div>
			
        </div>
        <!-- Page end  -->
    </div>
    
      </div>
    </div>
    <!-- Wrapper End-->
@include('admin.layout.footer')
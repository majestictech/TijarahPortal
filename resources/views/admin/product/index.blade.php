@include('admin.layout.header')	
<?php
use App\Helpers\AppHelper as Helper;
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				@if(Auth::user()->roleId != 4)
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				@endif
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-book-content"></i> {{ __('lang.productlist')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
		    @if(helper::checkUserRights('inventory_manage','inventory_add'))
			<a href="{{url('admin/product/create/').'/'.$storeId}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addproduct')}}</a>
			@endif
			
		    <form method="post" class="d-flex justify-content-end" enctype="multipart/form-data" action="{{route('product.export')}}">
				{{ csrf_field() }}
				<div class="form-group">
				    <input type="hidden" name="storeId" value="{{$storeId}}" />
					<input type="submit" name="upload" class="btn btn-primary" style="height: 45px; border-radius: 2px; margin-left: 20px;" value="Export Products">
				</div>
			</form>
			
			<form method="post" class="d-flex justify-content-end" enctype="multipart/form-data" action="{{route('product.downloadPDF')}}">
				{{ csrf_field() }}
				<div class="form-group">
					<input type="submit" name="upload" class="btn btn-primary" style="height: 45px; border-radius: 2px; margin-left: 20px;" value="Download PDF">
				</div>
			</form>
	
			<!--<a href="{{url('product/export?requ=post&name=$storeId')}}" class="btn btn-primary add-list"  style="margin-left:20px;font-size: 14px;"><i class="las la-plus mr-3" ></i>Export Products</a>-->
			
			<a href="{{url('storage/productscsv.csv')}}" class="btn btn-primary add-list" style="margin-left:20px;"><i class="las la-plus mr-3"></i>Download Sample File</a>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.productlist')}}</h6>
		<hr/>
		<div class="col-lg-6">
			<form method="post" class="d-flex justify-content-end" enctype="multipart/form-data" action="{{route('product.import')}}">
				{{ csrf_field() }}
				<div class="form-group">
				
					<input type="file" name="file" accept=".xlsx, .xls, .csv"/>
				    
				    <input type="hidden" name="storeId" value="{{$storeId}}" />
					<input type="submit" name="upload" class="btn btn-primary" value="Import Products" disabled>
				</div>
			</form>
		
		</div>

		<div class="card mt-3">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
                            <th>{{ __('lang.product')}}</th>
                           <!-- <th>{{ __('lang.code')}}</th>-->
                            <th>{{ __('lang.category')}}</th>
                            <th>{{ __('lang.price')}}</th>
                            <!--<th>{{ __('lang.minorderquantity')}}</th>-->
                            <th style="width:10%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($product as $key =>$ProductData)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!--<img src="{{URL::asset('public/products/125x125').'/'.$ProductData->productImage}}"  class="img-fluid proimage rounded avatar-50 mr-3" alt="image">-->
                                   <?php if($ProductData->productImgBase64 == '' ||  $ProductData->productImgBase64 == null){?>
                                        <img src="{{URL::asset('public/images/no-image.jpg')}}" style="border:1px solid;">
                                    <?php } ?>
                                   
                                   
                                   <?php if($ProductData->productImgBase64 != '' ){?>
                                        <img src="data:image/png;base64,{{$ProductData->productImgBase64}}">
                                    <?php } ?>
                                </div>
									<div>
                                        {{$ProductData->name}}
                                    </div>
                            </td>
                            <!--<td>{{$ProductData->code}}</td>-->
                            <td>{{$ProductData->catName}}</td>
                            <td>SAR {{$ProductData->sellingPrice}}</td>
                            <!--<td>{{$ProductData->minOrderQty}}</td>-->
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									
										<a class="dropdown-item" href="{{url('/admin/product/'.$ProductData->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										
										<a class="dropdown-item" href="{{url('/admin/product/expirydate')}}"><i class="fadeIn animated bx bx-edit"></i> Expiry Date </a>
									
										<a class="dropdown-item" href="{{route('product.destroy',['id'=>$ProductData->id])}}" onclick="return confirm('Are you sure you want to delete the Product?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
										
									</div>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')
<script>
var table = $('#myTable').DataTable({
   "order": [[ 1, "asc" ]],
              'columnDefs': [{
                    "targets": [2,3],
                    "orderable": false
                }]
          });
          
$(document).ready(
    function(){
        $('input:file').change(
            function(){
                if ($(this).val()) {
                    $('input:submit').attr('disabled',false); 
                } 
            }
            );
    });
</script>
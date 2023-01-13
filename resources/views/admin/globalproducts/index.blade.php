@include('admin.layout.header')							
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('globalproducts_manage','');

?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-globe"></i> {{ __('lang.globalproducts')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
        @if(helper::checkUserRights('globalproducts_manage','globalproducts_import'))
    	 <form method="post" class="d-flex justify-content-end" enctype="multipart/form-data" action="{{route('globalproducts.globalimport')}}">
			{{ csrf_field() }}
			<div class="form-group">
			
				<input type="file" name="file" accept=".xlsx, .xls, .csv"/>
			    
				<input type="submit" name="upload" class="btn btn-primary" value="Import Global Products">
			</div>
		</form>
		@endif

	</div><a href="{{url('storage/globalproducts.csv')}}" class="btn btn-primary add-list" style="margin-left:20px;"><i class="las la-plus mr-3"></i>Download Sample</a>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.globalproducts')}}</h6>
		<hr/>

		<div class="card mt-3">
			<div class="card-body">
			    
			    <form action="" method="GET" id ="filter_results">
			       <div class="row"> 
                	<div class="col-md-4 mb-3">
    					<label for="storeFilter" class="form-label">{{ __('lang.filterby')}}</label>
    					<div class="input-group">
    						<button class="btn btn-outline-secondary" type="button"><i class='bx bx-store'></i>
    						</button>
    						<select name="storeFilter" class="form-select single-select" id="storeFilter" onChange="this.form.submit();">
    							<option value="" @if(empty($storeFilter)) selected="selected" @endif>{{ __('lang.storetype')}}</option>
    								@foreach($storetype as $key=>$storetypes)
    								    <option value="{{ $storetypes->id }}" @if($storetypes->id==$storeFilter) selected="selected" @endif >{{ $storetypes->name }}</option>
    								@endforeach
    						</select>
    					</div>
    				</div>
    		        <div class="col-md-4 mb-3 ">
    				    <label for="search" class="form-label">Search</label>
    				     <input type="text" name="search" class="form-control form-control-sm" value="{{$search}}"/>
                       
    				</div>
    				<div class="col-md-3 mb-3 pt-4">
    				      <label for="" class="form-label"></label>
    				     <button type="submit" class="btn btn-primary px-5">Search</button>
    				</div>
    				
    		        
    		        
    		        
    		        
                    </div>
			    </form>
			    
			    
			    
			    
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
                            <th>{{ __('lang.product')}}</th>
                           <!-- <th>{{ __('lang.code')}}</th>-->
                            <th>{{ __('lang.category')}}</th>
                            <th>{{ __('lang.price')}}</th>
                            <!--<th>{{ __('lang.minorderquantity')}}</th>-->
                            @if(helper::checkUserRights('globalproducts_manage','globalproducts_edit') || helper::checkUserRights('globalproducts_manage','globalproducts_del'))
                            <th style="width:10%">{{ __('lang.action')}}</th>
                            @endif
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
								    @if(helper::checkUserRights('globalproducts_manage','globalproducts_edit') || helper::checkUserRights('globalproducts_manage','globalproducts_del'))
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										@if(helper::checkUserRights('globalproducts_manage','globalproducts_edit'))
										<a class="dropdown-item" href="{{url('/admin/globalproducts/'.$ProductData->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										@if(helper::checkUserRights('globalproducts_manage','globalproducts_del'))
										<a class="dropdown-item" href="{{route('globalproducts.destroy',['id'=>$ProductData->id])}}" onclick="return confirm('Are you sure you want to delete the Product?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
										@endif
									</div>
									@endif
									
								</div>
							</td>
							
						</tr>
					@endforeach
					</tbody>
				</table>
                @if ($productcount > 0)
    				<div class="pagination_links">
    				{{$product->appends(array('search' => $search))->links()}}
    				</div>
				@endif
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
                    "targets": [0,2,3],
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


<style>
    .dataTables_filter,.dataTables_info,.dataTables_paginate {display:none;}
    
</style>
@include('admin.layout.header')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/invoice')}}"><i class="bx bx-detail"></i> {{ __('lang.invoices')}}</a>
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
				@csrf
				  	<div class="col-12">
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
					
                	<div class="col-md-6">
						<label for="invoiceNumber" class="form-label">{{ __('lang.invoicenumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="number" class="form-control border-start-0" value="{{$invoice->invoiceNumber}}" name="invoiceNumber" id="invoiceNumber" required>
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="invoiceDate" class="form-label">{{ __('lang.invoicedate')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="date" class="form-control border-start-0" value="{{$invoice->invoiceDate}}" name="invoiceDate" id="invoiceDate" required>
						</div>
					</div>					
					
					
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$invoice->id}}">
						<input type="hidden" name="storeId" value = "{{$invoice->storeId}}" //>
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.editinvoice')}}</button>
						<button type="reset" class="btn btn-primary px-5">{{ __('lang.reset')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 
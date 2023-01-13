@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('faq_manage','faq_add');
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/faq')}}"><i class="bx bx-help-circle"></i> {{ __('lang.faqs')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addfaq')}}</li>
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
					<div><i class="bx bx-help-circle me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.addfaq')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('faq.store')}}" data-toggle="validator">
				@csrf
					<div class="col-md-12 ">
						<label for="question" class="form-label">{{ __('lang.question')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-help-circle'></i></span>
							<input type="text" autofocus="autofocus" name="question" class="form-control border-start-0" id="question" placeholder="{{ __('lang.enterquestion')}}" required />
						</div>
					</div>
					
					
					<div class="col-md-12">
						<label for="answer" class="form-label">{{ __('lang.answer')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent" style="align-items:flex-start !important;"><i class='bx bx-comment-detail' ></i></span>
							<textarea type="text" name="answer" class="form-control border-start-0" id="answer" placeholder="{{ __('lang.req_answer')}}" required /></textarea>
						</div>
					</div>
					
					<div class="col-12">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.addfaq')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 
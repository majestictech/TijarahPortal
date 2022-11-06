
@include('admin.layout.header')
  <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.bannerlist')}}</h4>
                        <p class="mb-0">{{ __('lang.bannerdesc1')}}<br> 
						{{ __('lang.bannerdesc2')}}</p>
                    </div>
                    <a href="{{url('/admin/banner/create')}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addbanner')}}</a>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input selectall" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>{{ __('lang.bannertitle')}}</th>
                            <th>{{ __('lang.bannerimage')}}</th>
							<th>{{ __('lang.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach($banner as $key =>$BannerData)
						
						<tr>						
                            <td>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox2">
                                    <label for="checkbox2" class="mb-0"></label>
                                </div>
                            </td>
							<td>{{$BannerData->bannerTitle}}</td>
                            <td>                             
                                <img src="{{URL::asset('public/images').'/'.$BannerData->bannerImage}}" class="img-responsive mr-3" width="200"alt="image">
							</td>        
                            <td>
                                <div class="d-flex align-items-center list-action">
									 <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top"  data-original-title="Edit" href="{{url('/admin/banner/'.$BannerData->id.'/edit')}}" class="btn btn-success"><i class="ri-pencil-line mr-0"></i></a>	
									<a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"
                                        href="{{route('banner.destroy',['id'=>$BannerData->id])}}" onclick="return confirm('Are you sure you want to delete this banner?');"><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach   
               
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
@include('admin.layout.header')

  <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add Store</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{url('admin/stores/store')}}" method="post" data-toggle="validator">
                        @csrf    
                            <div class="row">                                
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>Store Name *</label>
                                        <input type="text" name="storeName" class="form-control" placeholder="Enter Store Name" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>                                 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Store Type *</label>
                                        <select class="selectpicker form-control" data-style="py-0" name="storeType">
                                            <option value=""></option>
                                              @foreach($storetypes as $type)
                                              <option value="{{$type->id}}">{{$type->name}}</option>
                                              @endforeach
                                        </select>
                                    </div>
                                </div> 
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Registration Number *</label>
                                        <input type="text" name="regNo" class="form-control" placeholder="Enter Registration Name" required>

                                    </div>
                                </div> 

								<!-- <div class="col-md-6">
                                    <div class="form-group">                                        
										<label>Email</label>
                                        <input type="text" name="email" class="form-control" placeholder="Enter Email">
                                    </div>
                                </div> --> 								
								
								<!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Name *</label>
                                        <input type="text" name="contactName" class="form-control" placeholder="Enter Contact Name" required>
									    <div class="help-block with-errors"></div>
                                    </div>
                                </div> -->  
								
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Number *</label>
                                        <input type="text" name="contactNo" class="form-control" placeholder="Enter Contact Number" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

								<div class="col-md-12">
                                    <div class="form-group">
                                		<label>Store Address *</label>
                                        <textarea type="text"  name="address" rows="4" cols="50" class="form-control" placeholder="Enter Store Address" required></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip/Postal Code *</label>
                                        <input type="text" name="postalCode" class="form-control" placeholder="Enter Zip/Postal Code" required>
									    <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>City *</label>
                                        <input type="text" name="city" class="form-control" placeholder="Enter City Name" required>
									    <div class="help-block with-errors"></div>
                                    </div>
                                </div> 									
                            </div>                            
                            <button type="submit" class="btn btn-primary mr-2">Add Store</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
      </div>
    </div>
    <!-- Wrapper End-->
  
@include('admin.layout.footer')
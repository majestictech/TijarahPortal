
@include('admin.layout.header')

 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add Order</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="page-list-purchase.php" data-toggle="validator">
                            <div class="row">
							<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>Order No *</label>
                                        <input type="text" class="form-control" placeholder="Order No" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>  
								
								  <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Vendor</label>
                                        <select name="type" class="selectpicker form-control" data-style="py-0">
                                            <option></option>							
											<option>Abisoft</option>
                                            <option>Abeel</option>
											<option>Abidia</option>
											<option>Abcpp</option>
											<option>Abine</option>
                                        </select>
                                    </div>
                                </div> 
								
								<div class="col-md-6"> 
                                    <div class="form-group">
                                        <label>Order Status</label>
                                        <select name="type" class="selectpicker form-control" data-style="py-0">				
                                            <option></option>
											<option>Received</option>
											<option>Packed</option>
											<option>On the way</option>
											<option>Delivered</option>
                                           
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dob">Date *</label>
                                        <input type="date" class="form-control" id="dob" name="dob" />
                                    </div>
                                </div>  
                                  
                              
                                
                         
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Discount</label>
                                        <input type="text" class="form-control" placeholder="Discount">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Shipping Charges</label>
                                        <input type="text" class="form-control" placeholder="Shipping">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Payment *</label>
                                        <input type="text" class="form-control" placeholder="Payment" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Note *</label>
                                        <div id="quill-tool">
                                            <button class="ql-bold" data-toggle="tooltip" data-placement="bottom" title="Bold"></button>
                                            <button class="ql-underline" data-toggle="tooltip" data-placement="bottom" title="Underline"></button>
                                            <button class="ql-italic" data-toggle="tooltip" data-placement="bottom" title="Add italic text <cmd+i>"></button>
                                            <button class="ql-image" data-toggle="tooltip" data-placement="bottom" title="Upload image"></button>
                                            <button class="ql-code-block" data-toggle="tooltip" data-placement="bottom" title="Show code"></button>
                                        </div>
                                        <div id="quill-toolbar">
                                        </div>
                                    </div>
                                </div>                                
                            </div>                            
                            <button type="submit" class="btn btn-primary mr-2">Add Order</button>
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
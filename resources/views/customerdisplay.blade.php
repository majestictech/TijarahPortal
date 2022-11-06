<style>
	*,html{margin:0px;padding:0px;}
	#billDetails p {color:#fff; line-height:28px; font-size:16px;}
	#billDetails span {font-weight:bold;}
	#billDetails { display: flex; flex-direction:row;}
	.first_div,.second_div,.third_div { flex: 1 1 0px;}
	.second_div p {font-size:30px !important; margin-top:10px; text-align:center;}
	.third_div {text-align:right;}
	
	.powered {float: left; width:49%; padding-top:45px; padding-left: 1%;}
	.barcode {float: right; width:50%; text-align: right; padding-top:10px;}
	
	.slideshow-container {
	  max-width: 1000px;
	  /*position: relative;*/
	  margin: auto;
	}

	/* Fading animation */
	.fade {
	  animation-name: fade;
	  animation-duration: 1.5s;
	}

	@keyframes fade {
	  from {opacity: .4} 
	  to {opacity: 1}
	}
</style>
<div id="billDetails" style="height:16vh; background:#006C35; padding:10px;">
	<div class="first_div">
		<p>Welcome <b><span id="customer"></span></b></p>
		<p>Total Items: <span id="totItems">0</span></p>
		<p>Quantity: <span id="qty">0</span></p>
	</div>
	<div class="second_div">
		<p>Bill Amount (Inc. VAT)</p>
		<p><span id="billAmount">SAR 0.00</span></p>
	</div>
	<div class="third_div">
		<p>Total Tax: <span id="totTax"> SAR 0.00</span></p>
		<p>Discount: <span id="discount">SAR 0.00</span></p>
		<p>Balance: <span id="balance">SAR 0.00</span></p>
	</div>
</div>

<div style="width: 100%; height:22vh; background:rgba(0,108,53,0.5);  color:#FFF; position: absolute; z-index: 999999; bottom:0;">
	<div class="powered">Powered By <b>Tijarah</b></div>
	<div class="barcode"><img src="https://majestictechnosoft.com/posadmin/public/assets/images/websitelink-new.png" height="100" width="100"></div>
</div>

<div style="height:80vh;" style="position: absolute; z-index: 99;">
	<div class="slideshow-container">
        @foreach($results as $key =>$slide)
    	<div class="mySlides fade">
    		<img src="data:image/png;base64,{{$slide->image}}" style="width:100%">
      	</div>
      	@endforeach
    </div>
</div>

<script type="text/javascript">
    const params = new URLSearchParams(window.location.search);
	var billAmount = params.get('billAmount');
	var customer = params.get('customer');
	var totTax = params.get('totTax');
	var discount = params.get('discount');
	var balance = params.get('balance');
	var totItems = params.get('totItems');
	var qty = params.get('qty');
	var storeId = params.get('storeId');
	
	document.getElementById('customer').innerHTML = customer;
	document.getElementById('totItems').innerHTML = totItems;
	document.getElementById('qty').innerHTML = qty;
	document.getElementById('billAmount').innerHTML = billAmount;
	document.getElementById('totTax').innerHTML = totTax;
	document.getElementById('discount').innerHTML = discount;
	document.getElementById('balance').innerHTML = balance;
		
	let slideIndex = 0;
	showSlides();

	function showSlides() 
	{
	  let i;
	  let slides = document.getElementsByClassName("mySlides");
	  for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none";
	  }
	  slideIndex++;
	  if (slideIndex > slides.length) {slideIndex = 1}
	  slides[slideIndex-1].style.display = "block";
	  setTimeout(showSlides, 4000); // Change image every 2 seconds
	}
</script>
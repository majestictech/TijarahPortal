<style>
	*,html{margin:0px;padding:0px;}
	.slideshow-container {
	  /*max-width: 1000px;
	  position: relative;*/
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

@if(count($results) > 0)
<div class="slideshow-container">
    @foreach($results as $key =>$slide)
	<div class="mySlides fade">
		<img src="data:image/png;base64,{{$slide->image}}" style="width:100%">
  	</div>
  	@endforeach
</div>
@else
	<img src="{{ URL::asset('public/assets/images/customerdisplay.png')}}" style="width:100%">
@endif

<script type="text/javascript">
	let slideIndex = 0;
	
	<?php
	if (count($results) > 0)
	{
	?>
	    showSlides();
    <?php
    }
    ?>
    
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
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<!--<p class="mb-0">Copyright © 2022. All right reserved.</p>-->
			Copyright © <span id="copyright-year">2020</span>. All right reserved.

			<script>
				document.querySelector('#copyright-year').innerText = new Date().getFullYear();
			</script>
		</footer>
	</div>
	<!--end wrapper-->
	
	<!-- Bootstrap JS -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>

	<script src="{{ URL::asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
	
	<!--plugins-->
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->

	<!-- <script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script> -->
	<script src="{{ URL::asset('public/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ URL::asset('public/assets/js/app.js') }}"></script>
	
	<script src="{{ URL::asset('public/assets/plugins/chartjs/js/Chart.min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>
	
	<script src="{{ URL::asset('public/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<!--Morris JavaScript -->
	<script src="{{ URL::asset('public/assets/plugins/raphael/raphael-min.js') }}"></script>
	<script src="{{ URL::asset('public/assets/plugins/morris/js/morris.js') }}"></script>
	<script src="{{ URL::asset('public/assets/js/index2.js') }}"></script>
	

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js" type="text/javascript"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
	<!-- <script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script> -->
	
	<script>
		new PerfectScrollbar('.chat-list');
		new PerfectScrollbar('.chat-content');
	</script>


	<script>
$(function() {
  $(document).ready(function() {
    $('#myTable').DataTable();
  });
});
</script>


	
  </body>
</html>
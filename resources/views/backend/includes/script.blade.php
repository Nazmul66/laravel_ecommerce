	<!-- Bootstrap JS -->
	<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }} "></script>

	<!--plugins-->
	{{-- <script src="{{ asset('backend/js/jquery.min.js') }} "></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="{{ asset('backend/plugins/simplebar/js/simplebar.min.js') }} "></script>
	<script src="{{ asset('backend/plugins/metismenu/js/metisMenu.min.js') }} "></script>
	<script src="{{ asset('backend/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }} "></script>
	<script src="{{ asset('backend/plugins/chartjs/chart.min.js') }} "></script>
	<script src="{{ asset('backend/plugins/peity/jquery.peity.min.js') }} "></script>
	<script src="{{ asset('backend/plugins/datatable/js/jquery.dataTables.min.js') }} "></script>
	<script src="{{ asset('backend/plugins/datatable/js/dataTables.bootstrap5.min.js') }} "></script>
	<script src="{{ asset('backend/js/dashboard-eCommerce.js') }} "></script>
	
	<!--app JS-->
	<script src="{{ asset('backend/js/app.js') }}"></script>
	<script>
		new PerfectScrollbar('.product-list');
		new PerfectScrollbar('.customers-list');
	</script>
	
	<!-- toaster Js plugins  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

	<script type="text/javascript">
		toastr.options = {
			"closeButton": true,
			"debug": false,
			"newestOnTop": false,
			"progressBar": true,
			"positionClass": "toast-top-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		}
	</script>

	<script type="text/javascript">	
	   @if ( Session::has( 'message' ) )

		  var type = "{{ Session::get('alert-type', 'info') }}";

		  switch( type ){
			  case 'info':
			     toastr.info("{{ Session::get('message') }}");
                break;

			  case 'success':
			      toastr.success("{{ Session::get('message') }}");
                break;

			  case 'warning':
			      toastr.warning("{{ Session::get('message') }}");
                break;

			  case 'error':
			      toastr.error("{{ Session::get('message') }}");
				break;
		  }

	   @endif
	</script>

	@yield('script')
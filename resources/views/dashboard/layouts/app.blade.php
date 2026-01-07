<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ Helper::getStoreInfo()->appname }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="mobile-web-app-capable" content="yes">
    <link href="{{asset('assets/font/css.css')}}" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/skins/all.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/owl-carousel/owl-carousel/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/owl-carousel/owl-carousel/owl.theme.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/owl-carousel/owl-carousel/owl.transitions.css')}}">
    
    <!-- Favicon -->
    @if(Helper::getStoreInfo()->favicon)
        <link rel="shortcut icon" href="{{ asset('uploads/images/settings/'.Helper::getStoreInfo()->favicon) }}" type="image/x-icon">
    @endif
    <link rel="stylesheet" href="{{asset('assets/plugins/summernote/dist/summernote.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/DataTables/media/css/DT_bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/css/datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/styles-responsive.css')}}">
		
		<!-- Favicon -->
		@if(Helper::getStoreInfo()->favicon)
			<link rel="shortcut icon" href="{{ asset('uploads/images/settings/'.Helper::getStoreInfo()->favicon) }}" type="image/x-icon">
		@endif
    <link rel="stylesheet" href="{{asset('assets/css/plugins.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/themes/theme-default.css')}}" type="text/css" id="skin_color">
    <link rel="stylesheet" href="{{asset('assets/css/print.css')}}" type="text/css" media="print"/>
    <link rel="shortcut icon" href="favicon.ico" />
    @stack('stylesheet')
</head>
<body class="{{ Route::is('sales.order.create') ? 'sidebar-close' : '' }}"> 
    <div class="main-wrapper">
        @include('dashboard.layouts.header')
        <a class="closedbar inner hidden-sm hidden-xs {{ Route::is('sales.order.create') ? 'open' : '' }}" href="#">
        </a>

        @include('dashboard.layouts.navbar')

        <div class="main-container inner">
            <div class="main-content">
                @yield('content')
            </div>
        </div>
        @include('dashboard.layouts.footer')
    </div>
    <script src="{{asset('assets/plugins/jQuery/jquery-2.1.1.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/plugins/blockUI/jquery.blockUI.js')}}"></script>
    <script src="{{asset('assets/plugins/iCheck/jquery.icheck.min.js')}}"></script>
    <script src="{{asset('assets/plugins/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js')}}"></script>
    <script src="{{asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/plugins/bootbox/bootbox.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery.scrollTo/jquery.scrollTo.min.js')}}"></script>
    <script src="{{asset('assets/plugins/ScrollToFixed/jquery-scrolltofixed-min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery.appear/jquery.appear.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-cookie/jquery.cookie.js')}}"></script>
    <script src="{{asset('assets/plugins/velocity/jquery.velocity.min.js')}}"></script>
    <script src="{{asset('assets/plugins/TouchSwipe/jquery.touchSwipe.min.js')}}"></script>
    <script src="{{asset('assets/plugins/owl-carousel/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-mockjax/jquery.mockjax.js')}}"></script>
    <script src="{{asset('assets/plugins/toastr/toastr.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-modal/js/bootstrap-modal.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js')}}"></script>
    <script src="{{asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{asset('assets/plugins/DataTables/media/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/DataTables/media/js/DT_bootstrap.js')}}"></script>
    <script src="{{asset('assets/plugins/truncate/jquery.truncate.js')}}"></script>
    <script src="{{asset('assets/plugins/summernote/dist/summernote.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/js/subview.js')}}"></script>
    <script src="{{asset('assets/js/subview-examples.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js')}}"></script>
    <script src="{{asset('assets/plugins/autosize/jquery.autosize.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-maskmoney/jquery.maskMoney.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-colorpicker/js/commits.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jQuery-Tags-Input/jquery.tagsinput.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{asset('assets/plugins/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('assets/plugins/ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('assets/js/form-elements.js')}}"></script>
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.APP_CURRENCY = "{{ Helper::getStoreInfo()->currency }}";
    </script>
    @stack('javascript')
    <script>
        jQuery(document).ready(function() {
            Main.init();
            SVExamples.init();
            Index.init();
        });
    </script>
</body>
</html>

<!-- start: TOPBAR -->
                
<header class="topbar navbar navbar-inverse navbar-fixed-top inner">
    <!-- start: TOPBAR CONTAINER -->
    <div class="container">
        <div class="navbar-header">
            <a class="sb-toggle-left" href="#main-navbar">
                <i class="fa fa-bars"></i>
            </a>
            <!-- start: LOGO -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; padding: 10px 15px; height: 100%; text-decoration: none;">

                    <div style="display: flex; align-items: center; margin-top: 10px;">

                        <span style='font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 800; color: #fff; letter-spacing: -0.5px; font-size: 18px; text-transform: uppercase;'>
                            @php $nameParts = explode(' ', Helper::getStoreInfo()->appname); @endphp
                            <span style="color: #5D9CEC;">{{ $nameParts[0] ?? '' }}</span>{{ $nameParts[1] ?? '' }}
                        </span>
                    </div>
            </a>
            <!-- end: LOGO -->
        </div>
        <div class="topbar-tools">
            <!-- start: TOP NAVIGATION MENU -->
            <ul class="nav navbar-right">
                <!-- start: USER DROPDOWN -->
                <li class="dropdown current-user">
                    <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                        <span class="username hidden-xs">{{ Auth::guard('admin')->user()->name }}</span> <i class="fa fa-caret-down "></i>
                    </a>
                    <ul class="dropdown-menu dropdown-dark">
                        <li>
                            <a href="{{ route('admin.edit',Auth::guard('admin')->user()->id) }}">
                                {{ __('My Profile') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.edit',Auth::guard('admin')->user()->id) }}">
                                {{ __('Change Password') }}
                            </a>
                        </li>									
                        <li>
                            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- end: TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- end: TOPBAR CONTAINER -->
</header>
<!-- end: TOPBAR -->
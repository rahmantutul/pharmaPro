<nav id="pageslide-left" class="pageslide inner" style="{{ Route::is('sales.order.create') ? 'z-index: 0;' : '' }}">
    <div class="navbar-content">
        <!-- start: SIDEBAR -->
        <div class="main-navigation left-wrapper transition-left">
            <!-- start: MAIN NAVIGATION MENU -->
            <ul class="main-navigation-menu">
                <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> <span class="title"> {{__('Dashboard')}} </span></a>
                </li>
                
                <!-- General Settings (Standalone) -->
                @if(auth()->guard('admin')->user()->can('software-settings'))
                <li class="{{ Route::is('settings.index') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}">
                        <i class="fa fa-cog"></i> <span class="title">{{__('General Settings')}}</span>
                    </a>
                </li>
                @endif
                
                <!-- Users & Roles Management -->
                @if(auth()->guard('admin')->user()->can('user-management') || auth()->guard('admin')->user()->can('role-permission'))
                <li class="{{ Route::is('admin.index') || Route::is('role.index') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-users-cog"></i> <span class="title">{{__('Users & Roles')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('user-management'))
                        <li class="{{ Route::is('admin.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.index') }}">
                                <span class="title">{{__('User Management')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->guard('admin')->user()->can('role-permission'))
                        <li class="{{ Route::is('role.index') ? 'active' : '' }}">
                            <a href="{{ route('role.index') }}">
                                <span class="title">{{__('Role & Permission')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                
                <!-- Customers (Standalone) -->
                @if(auth()->guard('admin')->user()->can('customers'))
                <li class="{{ Route::is('customer.*') ? 'active' : '' }}">
                    <a href="{{ route('customer.index') }}">
                        <i class="fa fa-user"></i> <span class="title">{{__('Customers')}}</span>
                    </a>
                </li>
                @endif
                
                <!-- Suppliers (Standalone) -->
                @if(auth()->guard('admin')->user()->can('suppliers'))
                <li class="{{ Route::is('supplier.*') ? 'active' : '' }}">
                    <a href="{{ route('supplier.index') }}">
                        <i class="fa fa-truck"></i> <span class="title">{{__('Suppliers')}}</span>
                    </a>
                </li>
                @endif
                
                <!-- Vendors (Standalone) -->
                @if(auth()->guard('admin')->user()->can('vendors'))
                <li class="{{ Route::is('vendor.index') ? 'active' : '' }}">
                    <a href="{{ route('vendor.index') }}">
                        <i class="fa fa-users"></i> <span class="title">{{__('Vendors')}}</span>
                    </a>
                </li>
                @endif
                
                <!-- Payment Methods (Standalone) -->
                @if(auth()->guard('admin')->user()->can('payment-method'))
                <li class="{{ Route::is('method.index') ? 'active' : '' }}">
                    <a href="{{ route('method.index') }}">
                        <i class="fa fa-credit-card"></i> <span class="title">{{__('Payment Methods')}}</span>
                    </a>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('purchase-module'))
                <li class="{{ Route::is('purchase.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-shopping-cart"></i> <span class="title">{{__('Purchase Module')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('purchase-order-create'))
                        <li class="{{ Route::is('purchase.order.create') ? 'active' : '' }}">
                            <a href="{{ route('purchase.order.create') }}">
                                <span class="title">{{__('Purchase Order')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('purchase-list'))
                        <li class="{{ Route::is('purchase.order.index') ? 'active' : '' }}">
                            <a href="{{ route('purchase.order.index') }}">
                                <span class="title">{{__('Purchase List')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('purchase-direct-invoice'))
                        <li class="{{ Route::is('purchase.order.direct.invoice') ? 'active' : '' }}">
                            <a href="{{ route('purchase.order.direct.invoice') }}">
                                <span class="title">{{__('Direct Invoice')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('purchase-invoice-list'))
                        <li class="{{ Route::is('purchase.order.invoice.list') ? 'active' : '' }}">
                            <a href="{{ route('purchase.order.invoice.list') }}">
                                <span class="title">{{__('Invoice List')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('expense-module'))
                <li class="{{ Route::is('expense.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-money"></i> <span class="title">{{__('Expense Module')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('expense-category'))
                        <li class="{{ Route::is('expense.category.index') ? 'active' : '' }}">
                            <a href="{{ route('expense.category.index') }}">
                                <span class="title">{{__('Expense Category')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('expense-list'))
                        <li class="{{ Route::is('expense.index') ? 'active' : '' }}">
                            <a href="{{ route('expense.index') }}">
                                <span class="title">{{__('Expenses')}} </span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('demurrage-module'))
                <li class="{{ Route::is('demurrage.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-clock-o"></i> <span class="title">{{__('Demurrage')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('demurrage-list'))
                        <li class="{{ Route::is('demurrage.index') ? 'active' : '' }}"> 
                            <a href="{{route('demurrage.index')}}">
                                <span class="title">{{__('Demurrage List')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('demurrage-create'))
                        <li class="{{ Route::is('demurrage.create') ? 'active' : '' }}"> 
                            <a href="{{route('demurrage.create')}}">
                                <span class="title">{{__('Demurrage Create')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('medicine-module'))
                <li class="{{ Route::is('medicine.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-medkit"></i> <span class="title">{{__('Medicine Module')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('medicine-category'))
                        <li class="{{ Route::is('medicine.category.index') ? 'active' : '' }}">
                            <a href="{{ route('medicine.category.index') }}">
                                <span class="title">{{__('Med Category')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('medicine-unit'))
                        <li class="{{ Route::is('medicine.unit.index') ? 'active' : '' }}">
                            <a href="{{ route('medicine.unit.index') }}">
                                <span class="title">{{__('Med Unit')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('medicine-leaf'))
                        <li class="{{ Route::is('medicine.leaf.index') ? 'active' : '' }}">
                            <a href="{{ route('medicine.leaf.index') }}">
                                <span class="title">{{__('Med Leaf')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('medicine-type'))
                        <li class="{{ Route::is('medicine.type.index') ? 'active' : '' }}">
                            <a href="{{ route('medicine.type.index') }}">
                                <span class="title">{{__('Med Type')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('medicine-list'))
                        <li class="{{ Route::is('medicine.index') ? 'active' : '' }}">
                            <a href="{{ route('medicine.index') }}">
                                <span class="title">{{__('Medicines')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('sales-module'))
                <li class="{{ Route::is('sales.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-usd"></i> <span class="title">{{__('Sales Module')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('sales-order-create'))
                        <li class="{{ Route::is('sales.order.create') ? 'active' : '' }}">
                            <a href="{{ route('sales.order.create') }}">
                                <span class="title">{{__('Sales Order')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('sales-list'))
                        <li class="{{ Route::is('sales.order.index') ? 'active' : '' }}">
                            <a href="{{ route('sales.order.index') }}">
                                <span class="title">{{__('Sales List')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('return-module'))
                <li class="{{ Route::is('return.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-retweet"></i> <span class="title">{{__('Return Module')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('sales-return'))
                        <li class="{{ Route::is('return.sales.create') ? 'active' : '' }}">
                            <a href="{{ route('return.sales.create') }}">
                                <span class="title">{{__('Sales Return')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="{{ Route::is('return.sales.index') ? 'active' : '' }}">
                            <a href="{{ route('return.sales.index') }}">
                                <span class="title">{{__('Sales Return List')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('purchase-return'))
                        <li class="{{ Route::is('return.purchase.create') ? 'active' : '' }}">
                            <a href="{{ route('return.purchase.create') }}">
                                <span class="title">{{__('Purchase Return')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="{{ Route::is('return.purchase.index') ? 'active' : '' }}">
                            <a href="{{ route('return.purchase.index') }}">
                                <span class="title">{{__('Purchase Return List')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('stock-module'))
                <li class="{{ Route::is('stock.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-cubes"></i> <span class="title"> {{__('Stock Info')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('in-stock-medicines'))
                        <li class="{{ Route::is('stock.in_stock') ? 'active' : '' }}"> 
                            <a href="{{route('stock.in_stock')}}">
                                <span class="title">{{__('In Stock Medicines')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('low-stock-medicines'))
                        <li class="{{ Route::is('stock.low_stock') ? 'active' : '' }}"> 
                            <a href="{{route('stock.low_stock')}}">
                                <span class="title">{{__('Low Stock Medicines')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('stock-out-medicines'))
                        <li class="{{ Route::is('stock.stock_out') ? 'active' : '' }}"> 
                            <a href="{{route('stock.stock_out')}}">
                                <span class="title">{{__('Stock Out Medicines')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('upcoming-expired'))
                        <li class="{{ Route::is('stock.upcoming_expired') ? 'active' : '' }}">
                             <a href="{{route('stock.upcoming_expired')}}">
                                <span class="title">{{__('Upcomig Expired')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('expired-medicines'))
                        <li class="{{ Route::is('stock.expired_medicine') ? 'active' : '' }}"> 
                            <a href="{{route('stock.expired_medicine')}}">
                                <span class="title">{{__('Expired Medicines')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('reports-module'))
                <li class="{{ Route::is('report.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0)"><i class="fa fa-file-text"></i> <span class="title"> {{__('Reports')}}</span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        @if(auth()->guard('admin')->user()->can('sales-report'))
                        <li class="{{ Route::is('report.sales') ? 'active' : '' }}">
                             <a href="{{route('report.sales')}}">
                                <span class="title">{{__('Sales Report')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('purchase-report'))
                        <li class="{{ Route::is('report.purchase') ? 'active' : '' }}"> 
                            <a href="{{route('report.purchase')}}">
                                <span class="title">{{__('Purchase Report')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('customer-due-report'))
                        <li class="{{ Route::is('report.customer_due') ? 'active' : '' }}"> 
                            <a href="{{route('report.customer_due')}}">
                                <span class="title">{{__('Customer Due Report')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->can('supplier-due-report'))
                        <li class="{{ Route::is('report.supplier_due') ? 'active' : '' }}"> 
                            <a href="{{route('report.supplier_due')}}">
                                <span class="title">{{__('Supplier Due Report')}}</span> <i class="fa fa-circle menu-icon" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->can('clear-cache'))
                <li class="{{ Route::is('cache.clear') ? 'active' : '' }}">
                    <a href="{{ route('cache.clear') }}"><i class="fa fa-refresh"></i> <span class="title">{{__('Clear Cache')}} </span><i class="fa fa-circle menu-icon" aria-hidden="true"></i></span></a>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <a class="closedbar inner hidden-sm hidden-xs" href="#">
    </a>
    <div class="slide-tools">
        <div class="col-xs-12 text-center no-padding">
            <a class="btn btn-sm log-out text-center">
               {{ Helper::getStoreInfo()->appname }}
            </a>
        </div>
    </div>
</nav>
<!-- end: PAGESLIDE LEFT -->
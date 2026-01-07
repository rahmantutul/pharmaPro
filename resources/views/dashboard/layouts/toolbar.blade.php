<style>
.toolbar {
    background: #ffffff;
    border-bottom: 1px solid #eef2f5;
    padding: 12px 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.toolbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.toolbar-title h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.toolbar-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.toolbar-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 15px;
    background: #f8f9fa;
    border: 1px solid #e0e6ed;
    border-radius: 4px;
    color: #4c5667;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.toolbar-btn:hover {
    background: #eef2f5;
    color: #5D9CEC;
    text-decoration: none;
}

.toolbar-dropdown {
    position: relative;
    display: inline-block;
}

.toolbar-dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 5px;
    background: #fff;
    border: 1px solid #e0e6ed;
    border-radius: 4px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    min-width: 180px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s;
    z-index: 1000;
}

.toolbar-dropdown:hover .toolbar-dropdown-menu {
    opacity: 1;
    visibility: visible;
}

.toolbar-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    color: #555;
    text-decoration: none;
    font-size: 13px;
}

.toolbar-dropdown-item:hover {
    background: #f8f9fa;
    color: #5D9CEC;
    text-decoration: none;
}

.pos-btn {
    background: #5D9CEC;
    border: 1px solid #5D9CEC;
    color: #fff;
}

.pos-btn:hover {
    background: #4a89dc;
    color: #fff;
}
</style>

<div class="toolbar">
    <div class="toolbar-container">
        <div class="toolbar-title">
            <h4>{{ __('Panel Workspace') }}</h4>
        </div>
        <div class="toolbar-actions">
            <!-- Create Dropdown -->
            @if(auth()->guard('admin')->user()->can('customers') || auth()->guard('admin')->user()->can('suppliers') || auth()->guard('admin')->user()->can('medicine-list'))
            <div class="toolbar-dropdown">
                <a href="javascript:void(0)" class="toolbar-btn">
                    <i class="fa fa-plus"></i> {{ __('Create') }} <i class="fa fa-caret-down"></i>
                </a>
                <div class="toolbar-dropdown-menu">
                    @if(auth()->guard('admin')->user()->can('customers'))
                    <a href="{{ route('customer.index') }}" class="toolbar-dropdown-item"><i class="fa fa-users"></i> {{ __('Customer') }}</a>
                    @endif
                    @if(auth()->guard('admin')->user()->can('suppliers'))
                    <a href="{{ route('supplier.index') }}" class="toolbar-dropdown-item"><i class="fa fa-truck"></i> {{ __('Supplier') }}</a>
                    @endif
                    @if(auth()->guard('admin')->user()->can('medicine-list'))
                    <a href="{{ route('medicine.index') }}" class="toolbar-dropdown-item"><i class="fa fa-medkit"></i> {{ __('Medicine') }}</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Reports Dropdown -->
            @if(auth()->guard('admin')->user()->can('reports-module'))
            <div class="toolbar-dropdown">
                <a href="javascript:void(0)" class="toolbar-btn">
                    <i class="fa fa-file-text-o"></i> {{ __('Reports') }} <i class="fa fa-caret-down"></i>
                </a>
                <div class="toolbar-dropdown-menu">
                    @if(auth()->guard('admin')->user()->can('sales-report'))
                    <a href="{{ route('report.sales') }}" class="toolbar-dropdown-item"><i class="fa fa-line-chart"></i> {{ __('Sales Report') }}</a>
                    @endif
                    @if(auth()->guard('admin')->user()->can('purchase-report'))
                    <a href="{{ route('report.purchase') }}" class="toolbar-dropdown-item"><i class="fa fa-file-text"></i> {{ __('Purchase Report') }}</a>
                    @endif
                    @if(auth()->guard('admin')->user()->can('customer-due-report'))
                    <a href="{{ route('report.customer_due') }}" class="toolbar-dropdown-item"><i class="fa fa-credit-card"></i> {{ __('Customer Due') }}</a>
                    @endif
                    @if(auth()->guard('admin')->user()->can('supplier-due-report'))
                    <a href="{{ route('report.supplier_due') }}" class="toolbar-dropdown-item"><i class="fa fa-truck"></i> {{ __('Supplier Due') }}</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Security/System Dropdown -->
            @if(auth()->guard('admin')->user()->can('user-management') || auth()->guard('admin')->user()->can('role-permission'))
            <div class="toolbar-dropdown">
                <a href="javascript:void(0)" class="toolbar-btn">
                    <i class="fa fa-cog"></i> {{ __('Manage') }} <i class="fa fa-caret-down"></i>
                </a>
                <div class="toolbar-dropdown-menu">
                    @if(auth()->guard('admin')->user()->can('user-management'))
                    <a href="{{ route('admin.index') }}" class="toolbar-dropdown-item"><i class="fa fa-user"></i> {{ __('Admins') }}</a>
                    @endif
                    @if(auth()->guard('admin')->user()->can('role-permission'))
                    <a href="{{ route('role.index') }}" class="toolbar-dropdown-item"><i class="fa fa-shield"></i> {{ __('Roles') }}</a>
                    @endif
                    @if(auth()->guard('admin')->user()->can('clear-cache'))
                    <a href="{{ route('cache.clear') }}" class="toolbar-dropdown-item"><i class="fa fa-refresh"></i> {{ __('Clear Cache') }}</a>
                    @endif
                </div>
            </div>
            @endif

            @if(auth()->guard('admin')->user()->can('sales-order-create'))
            <a href="{{ route('sales.order.create') }}" class="toolbar-btn pos-btn">
                <i class="fa fa-calculator"></i> {{ __('POS') }}
            </a>
            @endif
        </div>
    </div>
</div>
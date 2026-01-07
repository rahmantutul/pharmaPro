@extends('dashboard.layouts.app')
@push('stylesheet')
<style>
    /* Custom Modern Dashboard Styles */
    :root {
        --primary-color: #5D9CEC;
        --success-color: #8CC152;
        --info-color: #4AA3DF;
        --warning-color: #F6BB42;
        --danger-color: #DA4453;
        --purple-color: #967ADC;
        --text-muted: #656565;
        --card-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    
    .dashboard-header-title {
        font-family: 'Raleway', sans-serif;
        font-weight: 700;
        color: #444;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        font-size: 16px;
        border-left: 4px solid var(--primary-color);
        padding-left: 10px;
    }

    /* Modern Cards */
    .dashboard-card {
        background: #fff;
        border-radius: 8px;
        border: none;
        box-shadow: var(--card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .card-body {
        padding: 20px;
    }
    
    /* Stats Layout */
    .stat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
    }
    .stat-content h5 {
        margin: 0 0 5px 0;
        color: var(--text-muted);
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .stat-content h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #333;
    }

    /* Colors */
    .stat-icon.bg-primary { background-color: #5D9CEC !important; background-color: var(--primary-color) !important; }
    .stat-icon.bg-success { background-color: #8CC152 !important; background-color: var(--success-color) !important; }
    .stat-icon.bg-info { background-color: #4AA3DF !important; background-color: var(--info-color) !important; }
    .stat-icon.bg-warning { background-color: #F6BB42 !important; background-color: var(--warning-color) !important; }
    .stat-icon.bg-danger { background-color: #DA4453 !important; background-color: var(--danger-color) !important; }
    .stat-icon.bg-purple { background-color: #967ADC !important; background-color: var(--purple-color) !important; }
    
    /* Table Panels */
    .table-panel {
        background: #fff;
        border-radius: 8px;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 25px;
    }
    .table-panel .panel-heading {
        background: transparent;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }
    .table-panel .panel-title {
        font-weight: 700;
        color: #333;
        display: flex;
        align-items: center;
    }
    .table-panel .panel-title i, .table-panel .panel-title img {
        margin-right: 10px;
    }
    
    .table-modern thead th {
        border-top: none;
        border-bottom: 2px solid #eee;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        color: #888;
        padding: 12px;
    }
    .table-modern tbody td {
        padding: 12px;
        border-top: 1px solid #f9f9f9;
        vertical-align: middle;
        font-size: 13px;
    }
    .table-modern tbody tr:hover {
        background-color: #fcfcfc;
    }

    /* Utilities for BS3 compatibility */
    .mb-0 { margin-bottom: 0 !important; }
    .mb-1 { margin-bottom: 5px !important; }
    .mb-2 { margin-bottom: 10px !important; }
    .mb-3 { margin-bottom: 15px !important; }
    .mt-3 { margin-top: 15px !important; }
    .mt-4 { margin-top: 20px !important; }
    .p-3 { padding: 15px !important; }
</style>
@endpush

@section('content')
    <div class="container">
        @include('dashboard.layouts.toolbar')
        
        {{-- Overall Data Section --}}
        <div class="row">
            <div class="col-md-12">
                <hr style="border-top: 1px solid #eaeaea;">
                <h4 class="dashboard-header-title">{{ __('Overview Statistics') }}</h4>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <div class="stat-row">
                            <div class="stat-content">
                                <h5>{{ __('Total Purchase') }}</h5>
                                <h3>{{ Helper::getStoreInfo()->currency }} {{ number_format($purchase, 2) }}</h3>
                            </div>
                            <div class="stat-icon bg-info">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <div class="stat-row">
                            <div class="stat-content">
                                <h5>{{ __('Total Sales') }}</h5>
                                <h3>{{ Helper::getStoreInfo()->currency }} {{ number_format($sale, 2) }}</h3>
                            </div>
                            <div class="stat-icon bg-success">
                                <i class="fa fa-money"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <div class="stat-row">
                            <div class="stat-content">
                                <h5>{{ __('Total Due') }}</h5>
                                <h3>{{ Helper::getStoreInfo()->currency }} {{ number_format($due, 2) }}</h3>
                            </div>
                            <div class="stat-icon bg-warning">
                                <i class="fa fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <div class="stat-row">
                            <div class="stat-content">
                                <h5>{{ __('Total Expense') }}</h5>
                                <h3>{{ Helper::getStoreInfo()->currency }} {{ number_format($expense, 2) }}</h3>
                            </div>
                            <div class="stat-icon bg-danger">
                                <i class="fa fa-minus-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly & Yearly Data Row --}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="dashboard-header-title mt-4">{{ __('Periodic Breakdown') }}</h4>
            </div>

            {{-- This Month --}}
            <div class="col-md-6">
                <div class="table-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-calendar-check-o text-success"></i> {{ __('This Month') }}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6 mb-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Purchase') }}</h5>
                                        <b class="text-info">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_month_purchase, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 mb-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Sale') }}</h5>
                                        <b class="text-success">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_month_sale, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 mt-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Due') }}</h5>
                                        <b class="text-warning">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_month_due, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 mt-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Expense') }}</h5>
                                        <b class="text-danger">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_month_expense, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- This Year --}}
            <div class="col-md-6">
                <div class="table-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-calendar text-primary"></i> {{ __('This Year') }}</h4>
                    </div>
                    <div class="panel-body">
                         <div class="row">
                            <div class="col-xs-6 mb-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Purchase') }}</h5>
                                        <b class="text-info">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_year_purchase, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 mb-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Sale') }}</h5>
                                        <b class="text-success">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_year_sale, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 mt-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Due') }}</h5>
                                        <b class="text-warning">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_year_due, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 mt-3">
                                <div class="dashboard-card mb-0" style="background: #f9f9f9; box-shadow: none; border: 1px solid #eee;">
                                    <div class="card-body text-center p-3">
                                        <h5 class="text-muted mb-1">{{ __('Expense') }}</h5>
                                        <b class="text-danger">{{ Helper::getStoreInfo()->currency }} {{ number_format($this_year_expense, 2) }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tables Row --}}
        <div class="row">
            {{-- Expired Medicines Table --}}
            <div class="col-md-6">
                <div class="table-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title text-danger">
                            <i class="fa fa-exclamation-triangle"></i>
                            {{ __('Expired Medicines') }}
                        </h4>
                    </div>
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('Medicine')}}</th>
                                        <th>{{__('Batch')}}</th>
                                        <th class="text-center">{{__('Qty')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expired_medicine as $key=>$data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><b class="text-dark">{{$data?->medicine->name}}</b> <br> <small class="text-muted">{{$data?->medicine->strength}}</small></td>
                                        <td>{{date('d M Y',strtotime($data->expire_date))}}</td>
                                        <td class="text-center"><span class="label-modern bg-danger text-black">{{$data->total_qty}}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center p-3">
                            {{ $expired_medicine->appends(request()->except('page_expired'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming Expiry Table --}}
            <div class="col-md-6">
                <div class="table-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title text-warning">
                            <i class="fa fa-clock-o"></i>
                             {{ __('Expiring Soon') }} ({{ Helper::getStoreInfo()->expiryalert }} days)
                        </h4>
                    </div>
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Medicine') }}</th>
                                        <th>{{ __('Supplier') }}</th>
                                        <th class="text-center">{{ __('Qty') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcoming_expire_medicine as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><b class="text-dark">{{ $data?->medicine->name }}</b> <br> <small class="text-muted">{{ $data?->medicine->strength }}</small></td>
                                        <td>{{ $data?->medicine?->supplier?->name }}</td>
                                        <td class="text-center"><span class="label-modern bg-warning text-white">{{ $data->total_qty }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center p-3">
                            {{ $upcoming_expire_medicine->appends(request()->except('page_upcoming'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Top Sold Medicines --}}
            <div class="col-md-6">
                <div class="table-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title text-success"><i class="fa fa-trophy"></i> {{ __('Top Sold Medicines') }}</h4>
                    </div>
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Medicine') }}</th>
                                        <th>{{ __('Supplier') }}</th>
                                        <th class="text-right">{{ __('Total Qty') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($top_sold_medicines as $med)
                                    <tr>
                                        <td><b class="text-dark">{{ $med->medicine?->name }}</b> <br> <small class="text-muted">{{ $med->medicine?->strength }}</small></td>
                                        <td>{{ $med->medicine?->supplier->name }}</td>
                                        <td class="text-right"><b>{{ number_format($med->total_quantity, 0) }}</b></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center">{{ __('No data found') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center p-3">
                            {{ $top_sold_medicines->appends(request()->except('page_sold'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Customers --}}
            <div class="col-md-6">
                <div class="table-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title text-info"><i class="fa fa-users"></i> {{ __('Top Customers') }}</h4>
                    </div>
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Contact') }}</th>
                                        <th class="text-right">{{ __('Total Spent') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($top_buying_customers as $customer)
                                    <tr>
                                        <td><b class="text-dark">{{ $customer->customer?->name }}</b></td>
                                        <td>{{ $customer->customer?->phone }}</td>
                                        <td class="text-right"><b>{{ Helper::getStoreInfo()->currency }} {{ number_format($customer->total_spent, 2) }}</b></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center">{{ __('No data found') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center p-3">
                            {{ $top_buying_customers->appends(request()->except('page_customers'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection


@push('javascript')
    <script>
        $(document).ready(function() {
            // Show success message
            @if (session('success'))
                toastr.success("{{ session('success') }}", 'Success');
            @endif

            // Show validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", 'Error');
                @endforeach
            @endif
        });
    </script>
@endpush

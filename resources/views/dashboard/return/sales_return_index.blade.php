@extends('dashboard.layouts.app')

@push('stylesheet')
<style>
    .filter-card { background: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #eef2f7; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 25px; }
    .stats-card { background: #ffffff; color: #333; padding: 25px; border-radius: 12px; border: 1px solid #eef2f7; box-shadow: 0 4px 6px rgba(0,0,0,0.03); margin-bottom: 25px; }
    .stat-item { text-align: center; border-right: 1px solid #f1f5f9; }
    .stat-item:last-child { border-right: none; }
    .stat-value { font-size: 28px; font-weight: 700; color: #1e293b; }
    .stat-label { font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 5px; }
    .action-btn-group { display: flex; gap: 4px; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    .bg-light-professional { background-color: #fcfdfe !important; }
    .table thead th { background-color: #f1f5f9 !important; color: #475569 !important; font-weight: 600 !important; border-bottom: 2px solid #e2e8f0 !important; }
    .badge-info { background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @include('dashboard.layouts.toolbar')
    
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="#">Sales Management</a></li>
                <li class="active">Sales Returns</li>
            </ol>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-12">
            <div class="stats-card">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-value">{{ $totalReturns ?? $dataList->total() }}</div>
                            <div class="stat-label">Total Returns</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                                    <div class="stat-value">{{ Helper::getStoreInfo()->currency }}{{ number_format($totalAmount ?? 0, 2) }}</div>
                            <div class="stat-label">Total Amount</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-value">{{ $dataList->count() }}</div>
                            <div class="stat-label">Showing Results</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-undo"></i> Sales Returns List
                        <a href="{{ route('return.sales.create') }}" class="btn btn-sm btn-success pull-right">
                            <i class="fa fa-plus"></i> Create New Return
                        </a>
                    </h4>
                </div>
                
                <div class="panel-body">
                    <!-- Filter Section -->
                    <div class="filter-card">
                        <form action="{{ route('return.sales.index') }}" method="GET" id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Medicine</label>
                                        <select name="medId" class="form-control" id="medicineFilter">
                                            <option value="">-- All Medicines --</option>
                                            @foreach ($medicines as $med)
                                                <option value="{{ $med->id }}" 
                                                    {{ request('medId') == $med->id ? 'selected' : '' }}>
                                                    {{ $med->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Customer</label>
                                        <select name="customerId" class="form-control" id="customerFilter">
                                            <option value="">-- All Customers --</option>
                                            @foreach ($customers as $cust)
                                                <option value="{{ $cust->id }}"
                                                    {{ request('customerId') == $cust->id ? 'selected' : '' }}>
                                                    {{ $cust->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Invoice No</label>
                                        <input type="text" name="invNo" class="form-control" 
                                               placeholder="Search invoice" value="{{ request('invNo') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="date_from" class="form-control" 
                                               value="{{ request('date_from') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="date_to" class="form-control" 
                                               value="{{ request('date_to') }}">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('return.sales.index') }}" class="btn btn-sm btn-default">
                                        <i class="fa fa-refresh"></i> Reset Filters
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success" id="exportExcel">
                                        <i class="fa fa-file-excel-o"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Invoice No</th>
                                    <th width="10%">Date</th>
                                    <th width="18%">Medicine</th>
                                    <th width="12%">Customer</th>
                                    <th width="8%">Qty</th>
                                    <th width="10%">Price</th>
                                    <th width="10%">Total</th>
                                    <th width="10%">Reason</th>
                                    <th width="5%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataList as $key => $item)
                                    <tr>
                                        <td>{{ $dataList->firstItem() + $key }}</td>
                                        <td>
                                            <strong>{{ $item->inv_no }}</strong>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($item->return_date)) }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $item->medicine->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $item->medicine->generic_name ?? '' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->customer)
                                                <div>
                                                    {{ $item->customer->name }}<br>
                                                    <small class="text-muted">{{ $item->customer->phone }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Walk-in</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $item->qty }}</span>
                                        </td>
                                        <td class="text-right">{{ Helper::getStoreInfo()->currency }}{{ number_format($item->price, 2) }}</td>
                                        <td class="text-right">
                                            <strong>{{ Helper::getStoreInfo()->currency }}{{ number_format($item->total, 2) }}</strong>
                                        </td>
                                        <td>
                                            <small>{{ $item->return_reason ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="action-btn-group">
                                                <a href="{{ route('return.sales.show', $item->id) }}" 
                                                   class="btn btn-xs btn-info" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <form action="{{ route('return.sales.destroy', $item->id) }}" 
                                                      method="POST" class="delete-form" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-xs btn-danger delete-btn" 
                                                            title="Delete">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div style="padding: 40px;">
                                                <i class="fa fa-inbox fa-3x text-muted"></i>
                                                <p class="text-muted" style="margin-top: 10px;">No sales returns found</p>
                                                <a href="{{ route('return.sales.create') }}" class="btn btn-sm btn-success">
                                                    <i class="fa fa-plus"></i> Create First Return
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($dataList->hasPages())
                        <div class="text-center">
                            {{ $dataList->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    // Show messages
    @if (session('success'))
        toastr.success("{{ session('success') }}", 'Success');
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}", 'Error');
    @endif

    // Initialize Select2
    $('#medicineFilter, #customerFilter').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });

    // Delete confirmation
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('.delete-form');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the return and restore stock!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Export to Excel
    $('#exportExcel').on('click', function() {
        const table = document.getElementById('dataTable');
        const wb = XLSX.utils.table_to_book(table, {sheet: "Sales Returns"});
        XLSX.writeFile(wb, 'sales_returns_' + new Date().getTime() + '.xlsx');
    });

    // Print functionality
    window.onbeforeprint = function() {
        document.querySelector('.filter-card').style.display = 'none';
        document.querySelector('.panel-heading').style.display = 'none';
    };

    window.onafterprint = function() {
        document.querySelector('.filter-card').style.display = 'block';
        document.querySelector('.panel-heading').style.display = 'block';
    };
});
</script>

<!-- Include XLSX library for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush
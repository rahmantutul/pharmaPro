@extends('dashboard.layouts.app')

@push('stylesheet')
<style>
    td{
        padding: 2px !important;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        @include('dashboard.layouts.toolbar')
        <!-- end: TOOLBAR -->
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="#">
                            Sales Management
                        </a>
                    </li>
                    <li class="active">
                        Sales List
                    </li>
                </ol>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <h4>Sales  <span class="text-bold"> List</span></h4>
                            <div class="table-responsive">
                                <div class="row" >
                                    <form action="" method="GET"> @csrf
                                        <div class="form-group col-md-4">
                                            <select id="medList" name="medId" class="form-control single-select">
                                                <option value="">Select Medicine </option>
                                                @foreach($medicines as $item)
                                                    <option {{ ($item->id == request()->medId) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }} || {{ $item->strength }} || {{ $item->supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select id="invoiceList" name="invNo" class="form-control single-select">
                                                <option value="">Select Invoice </option>
                                                @foreach($invList as $item)
                                                    <option {{ ($item->id == request()->invNo) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->invoice_no }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select id="customerList" name="customer" class="form-control single-select">
                                                <option value="">Select Customer </option>
                                                @foreach($customers as $cust)
                                                    <option {{ ($cust->id == request()->customer) ? 'selected' : '' }} value="{{ $cust->id }}">{{ $cust->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp; Search</button>
                                            <a class="btn btn-sm btn-warning" href="{{ route('sales.order.index') }}"><i class="fa fa-times"></i>&nbsp;Clear</a>
                                        </div>
                                    </form>
                                </div>
                                <table class="table table-striped" >
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('#') }}</th>
                                            <th scope="col">{{__('Date')}}</th>
                                            <th scope="col">{{__('Invoice')}}</th>
                                            <th scope="col">{{__('Customer')}}</th>
                                            <th style="text-align:right;" scope="col">{{__('Subtotal')}}</th>
                                            <th style="text-align:right;" scope="col">{{__('Invoice Discount')}}</th>
                                            <th style="text-align:right;" scope="col">{{__('Total')}}</th>
                                            <th style="text-align:right;" scope="col">{{__('Paid Amount')}}</th>
                                            <th style="text-align:right;" scope="col">{{__('Invoice Due')}}</th>
                                            <th scope="col">{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="salesTableBody">
                                        @include('dashboard.sale.sales_list_partial')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Add Modal -->
                <div class="modal fade" id="medicineModal" tabindex="-1" role="dialog" aria-labelledby="medicineModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="medicineModalLabel">{{__('Purchase Details')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('Image')}}</th>
                                            <th>{{__('Med Name')}}</th>
                                            <th>{{__('Price')}}</th>
                                            <th>{{__('Qty')}}</th>
                                            <th>{{__('Subtotal')}}</th>
                                            <th>{{__('Discount')}}</th>
                                            <th>{{__('Total')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicineTableBody">
                                        <!-- AJAX loaded data will be inserted here -->
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            <!-- End Add Modal -->
        </div>
    </div>
@endsection
@push('javascript')
    <script>
        // Initialize the first medicine dropdown
        $(`#medList`).select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Initialize the first medicine dropdown
        $(`#invoiceList`).select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Initialize the first medicine dropdown
        $(`#customerList`).select2({
            placeholder: "Select an option",
            allowClear: true
        });

        $(document).ready(function() {
            // Show success message
            @if (session('success'))
                toastr.success("{{ session('success') }}", 'Success');
            @endif
            @if (session('errors'))
                toastr.success("{{ session('errors') }}", 'Errors');
            @endif
            // Show validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", 'Error');
                @endforeach
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            // event delegation for dynamic content
            $(document).on('click', '.actionButton', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('sales.order.details', ':id') }}'.replace(':id', id),
                    method: 'GET',
                    success: function(data) {
                        var tableBody = $('#medicineTableBody');
                        tableBody.empty();
                        if (data.success && data.details) {
                            data.details.forEach(function(details) {
                                var row = `<tr>
                                                <td><img src="/uploads/images/medicine/${details.medicine.image}" alt="${details.medicine.name}" width="50"> </td>
                                                <td>${details.medicine.name}</td>
                                                <td>${window.APP_CURRENCY} ${details.sell_price}</td>
                                                <td>${details.qty}</td>
                                                <td>${window.APP_CURRENCY} ${details.subtotal}</td>
                                                <td>${details.discount}</td>
                                                <td>${window.APP_CURRENCY} ${details.total}</td>
                                            </tr>`;
                                tableBody.append(row);
                            });
                            $('#medicineModal').modal('show');
                        } else {
                            alert('Failed to load details data.');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch medicine list.');
                    }
                });
            });

            // AJAX Search Logic
            function fetchSalesData() {
                let medId = $('#medList').val();
                let params = {
                    medId: medId,
                    customer: $('#customerList').val()
                };

                // Specific handling for Invoice List which uses text matching in controller
                // If value is present (ID), we get the text. If cleared/empty, send empty string.
                let invVal = $('#invoiceList').val();
                if (invVal) {
                     params.invNo = $('#invoiceList option:selected').text().trim();
                } else {
                     params.invNo = '';
                }

                $.ajax({
                    url: "{{ route('sales.order.index') }}",
                    method: 'GET',
                    data: params,
                    success: function(response) {
                        $('#salesTableBody').html(response);
                    },
                    error: function() {
                        toastr.error('Failed to filter data');
                    }
                });
            }

            // Listen for changes
            $('#medList, #invoiceList, #customerList').on('change', function() {
                fetchSalesData();
            });
            
            $('form').on('submit', function(e) {
                e.preventDefault();
                fetchSalesData();
            });

            // AJAX Pagination link clicks
            $(document).on('click', '#salesTableBody .pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let medId = $('#medList').val();
                let params = {
                    medId: medId,
                    customer: $('#customerList').val()
                };
                let invVal = $('#invoiceList').val();
                if (invVal) {
                     params.invNo = $('#invoiceList option:selected').text().trim();
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: params,
                    success: function(response) {
                        $('#salesTableBody').html(response);
                        // Scroll top to show new results
                        $('html, body').animate({
                            scrollTop: $(".panel-white").offset().top - 100
                        }, 500);
                    },
                    error: function() {
                        toastr.error('Failed to load page');
                    }
                });
            });
        });
    </script>
@endpush

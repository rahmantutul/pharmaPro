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
                            {{ __('Report')}}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Sales Report')}}
                    </li>
                </ol>
            </div>
            <div class="row" style="display:flex;">
                <div class="col-sm-10" style="margin:auto !important;">
                    <div class="panel panel-white">
                        <div class="panel-body">
                          <h4>{{ __('Sales Report')}}</h4>
                          <div class="row">
                            <form action="" method="GET" id="myform" style="padding:6px;"> @csrf
                              <div class="row bg-secondary p-3">
                                  <div class="col-md-2">
                                      <input type="date" class="form-control" id="fromDate" value="{{request()->fromDate}}" name="fromDate">
                                  </div>
                                  <div class="col-md-2">
                                      <input type="date" class="form-control" id="toDate" name="toDate" value="{{request()->toDate}}">
                                  </div>
                                  <div class="col-md-4">
                                      <select id="customer" class="form-control single-select" name="customerId">
                                          <option value="">Select Customer</option>
                                          @foreach($customers as $customer)
                                              <option {{(request()->customerId == $customer->id ? 'selected' : '')}} value="{{ $customer->id }}">{{ $customer->name }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                    <div class="col-md-2">
                                      <button id="btn1" type="submit" name="submit" value="search" class="btn btn-sm btn-primary" title="Search"><i class="fa fa-search"></i></button>
                                      <button id="btn2" type="submit" name="submit" value="pdf" target="__blank" class="btn btn-sm btn-warning" title="Download PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                      <a href="{{route('report.sales')}}" class="btn btn-sm btn-danger" title="Reset"><i class="fa fa-times"></i></a>
                                  </div>
                              </div>
                          </form>
                          </div>
                          <table class="table table-striped table-data table-view">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('#') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Date') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Invoice') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Customer') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Subtotal') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Inv. Discount') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Total') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Paid Amount') }}</th>
                                    <th scope="col" style="text-align: center;">{{ __('Invoice Due') }}</th>
                                </tr>
                            </thead>
                            @php
                                $total = 0;
                                $totalDiscount = 0;
                                $payableTotal = 0;
                                $paidTotal = 0;
                                $dueTotal = 0;
                            @endphp
                            <tbody>
                                @foreach ($dataList as $key => $data)
                                    @php
                                        $total += $data->grand_total;
                                        $totalDiscount += $data->invoice_discount;
                                        $payableTotal += $data->payable_total;
                                        $paidTotal += $data->paid_amount;
                                        $dueTotal += $data->due_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="text-bold-500 text-center">
                                            {{ date('j F Y', strtotime($data->invoice_date)) }}</td>
                                        <td align="center">{{ $data->invoice_no }}</td>
                                        <td align="center">{{ $data->customer?->name ?? 'Walking customer' }}</td>
                                        <td align="center">{{ number_format($data->grand_total, 2) }}</td>
                                        <td align="center">{{ number_format($data->invoice_discount, 2) }}</td>
                                        <td align="center">{{ number_format($data->payable_total, 2) }}</td>
                                        <td align="center">{{ number_format($data->paid_amount, 2) }}</td>
                                        <td align="center">{{ number_format($data->due_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                        </table>
                        <div class="text-center">
                            {{ $dataList->appends(request()->input())->links() }}
                        </div>
                                <!-- Professional Summary Card -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="panel panel-white" style="border-left: 4px solid #3498db;">
                                            <div class="panel-heading" style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                                <h5 class="panel-title" style="color: #2c3e50; font-weight: 600;">
                                                    <i class="fa fa-calculator"></i> {{ __('Summary (All Matching Records)') }}
                                                </h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-2 col-sm-6 mb-3">
                                                        <div class="summary-item" style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                                            <small class="text-muted d-block mb-1">{{ __('Subtotal') }}</small>
                                                            <h4 class="mb-0" style="color: #34495e; font-weight: 700;">{{ number_format($grandStats->total_amount, 2) }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-6 mb-3">
                                                        <div class="summary-item" style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 8px;">
                                                            <small class="text-muted d-block mb-1">{{ __('Invoice Discount') }}</small>
                                                            <h4 class="mb-0" style="color: #856404; font-weight: 700;">{{ number_format($grandStats->total_discount, 2) }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-6 mb-3">
                                                        <div class="summary-item" style="text-align: center; padding: 15px; background: #d1ecf1; border-radius: 8px; border: 2px solid #3498db;">
                                                            <small class="text-muted d-block mb-1">{{ __('Payable Total') }}</small>
                                                            <h4 class="mb-0" style="color: #0c5460; font-weight: 700;">{{ number_format($grandStats->payable_total, 2) }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-6 mb-3">
                                                        <div class="summary-item" style="text-align: center; padding: 15px; background: #d4edda; border-radius: 8px;">
                                                            <small class="text-muted d-block mb-1">{{ __('Paid Amount') }}</small>
                                                            <h4 class="mb-0" style="color: #155724; font-weight: 700;">{{ number_format($grandStats->paid_total, 2) }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-6 mb-3">
                                                        <div class="summary-item" style="text-align: center; padding: 15px; background: #f8d7da; border-radius: 8px;">
                                                            <small class="text-muted d-block mb-1">{{ __('Total Due') }}</small>
                                                            <h4 class="mb-0" style="color: #721c24; font-weight: 700;">{{ number_format($grandStats->due_total, 2) }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            $('#customer').select2({
              placeholder: "Select an option", // Optional placeholder
              allowClear: true // Allows user to clear selection
          });
        });
    </script>
@endpush

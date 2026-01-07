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
                            {{ __('Purchase Management')}}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Create Purchase')}}
                    </li>
                </ol>
            </div>
            <div class="row" style="display:flex;">
                <div class="col-sm-10" style="margin:auto !important;">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <div class="row" style="padding: 6px;">
                                <form action="" method="GET" id="myform"> @csrf
                                    <div class="row bg-secondary p-3">
                                        <div class="col-md-2">
                                            <input type="date" class="form-control" id="fromDate" value="{{ request()->fromDate }}"
                                                name="fromDate">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" class="form-control" id="toDate" name="toDate"
                                                value="{{ request()->toDate }}">
                                        </div>
                                        <div class="col-md-4">
                                            <select id="supplier" class="form-control single-select" name="supplierId">
                                                <option value="">{{ __('Select Supplier')}}</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option {{ request()->supplierId == $supplier->id ? 'selected' : '' }}
                                                        value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="btn1" type="submit" name="submit" value="search" class="btn btn-sm btn-primary" title="Search"><i class="fa fa-search"></i></button>
                                            <button id="btn2" type="submit" name="submit" value="pdf" target="__blank" class="btn btn-sm btn-warning" title="Download PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                            <a href="{{route('report.purchase')}}" class="btn btn-sm btn-danger" title="Reset"><i class="fa fa-times"></i></a>
                                        </div>
                
                                    </div>
                                </form>
                            </div>
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col"{{ __('SL') }}</th>
                                        <th scope="col" class="text-center">{{ __('Date') }}</th>
                                        <th scope="col" class="text-center">{{ __('Invoice') }}</th>
                                        <th scope="col" class="text-center">{{ __('Supplier') }}</th>
                                        <th scope="col" class="text-center">{{ __('Subtotal') }}</th>
                                        <th scope="col" class="text-center">{{ __('Invoicer Discount') }}</th>
                                        <th scope="col" class="text-center">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                @php
                                    $total = 0;
                                    $totalDiscount = 0;
                                    $payableTotal = 0;
                                    $paidTotal = 0;
                                    $dueTotal = 0;
                                    $subTotal = 0;
                                @endphp
                                <tbody>
                                    @foreach ($dataList as $key => $data)
                                        @php
                                            $total += $subTotal;
                                            $totalDiscount += $data->total_discount;
                                            $payableTotal += $data->total_amount;
                                        @endphp
                                        <tr>
                                            @php
                                                $subTotal = $data->total_discount+$data->total_amount;
                                            @endphp
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-bold-500 text-center">{{ date('j F Y', strtotime($data->invoice_date)) }}
                                            </td>
                                            <td class="text-center">{{ $data->invoice_no }}</td>
                                            <td class="text-center">{{ $data?->supplier->name }}</td>
                                            <td class="text-center">{{ number_format($subTotal, 2) }}</td>
                                            <td class="text-center">{{ number_format($data->total_discount, 2) }}</td>
                                            <td class="text-center">{{ number_format($data->total_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $dataList->appends(request()->input())->links() }}
                            </div>
                            <!-- Professional Summary Card -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-white" style="border-left: 4px solid #e74c3c;">
                                        <div class="panel-heading" style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                            <h5 class="panel-title" style="color: #2c3e50; font-weight: 600;">
                                                <i class="fa fa-calculator"></i> {{ __('Summary (All Matching Records)') }}
                                            </h5>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="summary-item" style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                                        <small class="text-muted d-block mb-1">{{ __('Subtotal') }}</small>
                                                        <h4 class="mb-0" style="color: #34495e; font-weight: 700;">{{ number_format($grandStats->total_amount + $grandStats->total_discount, 2) }}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="summary-item" style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 8px;">
                                                        <small class="text-muted d-block mb-1">{{ __('Invoice Discount') }}</small>
                                                        <h4 class="mb-0" style="color: #856404; font-weight: 700;">{{ number_format($grandStats->total_discount, 2) }}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="summary-item" style="text-align: center; padding: 15px; background: #f8d7da; border-radius: 8px; border: 2px solid #e74c3c;">
                                                        <small class="text-muted d-block mb-1">{{ __('Total Purchase Amount') }}</small>
                                                        <h4 class="mb-0" style="color: #721c24; font-weight: 700;">{{ number_format($grandStats->total_amount, 2) }}</h4>
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

            $('#supplier').select2({
                placeholder: "Select an option", // Optional placeholder
                allowClear: true // Allows user to clear selection
            });
        });
    </script>
@endpush

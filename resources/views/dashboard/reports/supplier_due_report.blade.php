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
                        {{ __('Supplier Due Report')}}
                    </li>
                </ol>
            </div>
            <div class="row" style="display:flex;">
                <div class="col-sm-10" style="margin:auto !important;">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <div class="row" style="padding:6px;">
                                <form action="" method="GET" id="myform"> @csrf
                                    <div class="row bg-secondary p-3">
                                        <div class="col-md-4">
                                            <select id="supplier" class="form-control single-select" name="supplierId">
                                                <option value="">{{ __('Select Customer')}}</option>
                                                @foreach ($suppliers as $sup)
                                                    <option {{ request()->supplierId == $sup->id ? 'selected' : '' }}
                                                        value="{{ $sup->id }}">{{ $sup->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="btn1" type="submit" name="submit" value="search" class="btn btn-sm btn-primary" title="Search"><i class="fa fa-search"></i></button>
                                            <button id="btn2" type="submit" name="submit" value="pdf" target="__blank" class="btn btn-sm btn-warning" title="Download PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                            <a href="{{route('report.supplier_due')}}" class="btn btn-sm btn-danger" title="Reset"><i class="fa fa-times"></i></a>
                                        </div>
                
                                    </div>
                                </form>
                            </div>
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('#') }}</th>
                                        <th scope="col" class="text-center">{{ __('Supplier') }}</th>
                                        <th scope="col" class="text-center">{{ __('Total Purchase Amount') }}</th>
                                        <th scope="col" class="text-center">{{ __('Paid Amount') }}</th>
                                        <th scope="col" class="text-center">{{ __('Invoice Due') }}</th>
                                    </tr>
                                </thead>
                                @php
                                    $payableTotal = 0;
                                    $paidTotal = 0;
                                    $dueTotal = 0;
                                @endphp
                                <tbody>
                                    @foreach ($dataList as $key => $data)
                                        @php
                                            $payableTotal += $data->grand_total;
                                            $paidTotal += $data->paid_amount;
                                            $dueTotal += $data->total_due;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $data->name }}</td>
                                            <td class="text-center">{{ number_format($data->grand_total, 2) }}</td>
                                            <td class="text-center">{{ number_format($data->paid_amount, 2) }}</td>
                                            <td class="text-center">{{ number_format($data->total_due, 2) }}</td>
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
                                    <div class="panel panel-white" style="border-left: 4px solid #9b59b6;">
                                        <div class="panel-heading" style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                            <h5 class="panel-title" style="color: #2c3e50; font-weight: 600;">
                                                <i class="fa fa-calculator"></i> {{ __('Summary (All Matching Records)') }}
                                            </h5>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="summary-item" style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                                        <small class="text-muted d-block mb-1">{{ __('Total Purchase Amount') }}</small>
                                                        <h4 class="mb-0" style="color: #34495e; font-weight: 700;">{{ number_format($grandStats->payable_total, 2) }}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="summary-item" style="text-align: center; padding: 15px; background: #d4edda; border-radius: 8px;">
                                                        <small class="text-muted d-block mb-1">{{ __('Paid Amount') }}</small>
                                                        <h4 class="mb-0" style="color: #155724; font-weight: 700;">{{ number_format($grandStats->paid_total, 2) }}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="summary-item" style="text-align: center; padding: 15px; background: #e8daef; border-radius: 8px; border: 2px solid #9b59b6;">
                                                        <small class="text-muted d-block mb-1">{{ __('Total Due') }}</small>
                                                        <h4 class="mb-0" style="color: #6c3483; font-weight: 700;">{{ number_format($grandStats->due_total, 2) }}</h4>
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

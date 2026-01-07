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
                            {{ __('Stock') }}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Stock Out Medicines') }}
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
                                        <div class="col-md-3">
                                            <select id="supplier" class="form-control single-select" name="supplierId">
                                                <option value="">{{ __('Select Supplier') }}</option>
                                                @foreach($suppliers as $supplier)
                                                    <option {{(request()->supplierId == $supplier->id ? 'selected' : '')}} value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="medicine" class="form-control single-select" name="medicineId">
                                                <option value="">{{ __('Select Medicine') }}</option>
                                                @foreach($medicines as $medicine)
                                                    <option {{(request()->medicineId == $medicine->id ? 'selected' : '')}} value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="btn1" type="submit" name="submit" value="search" class="btn btn-sm btn-primary" title="Search"><i class="fa fa-search"></i></button>
                                            <a href="{{route('stock.stock_out')}}" class="btn btn-sm btn-danger" title="Reset"><i class="fa fa-times"></i></a>                                        
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('#') }}</th>
                                        <th scope="col" class="text-center">{{ __('Medicine') }}</th>
                                        <th scope="col" class="text-center">{{ __('Strength') }}</th>
                                        <th scope="col" class="text-center">{{ __('Category') }}</th>
                                        <th scope="col" class="text-center">{{ __('Supplier') }}</th>
                                        <th scope="col" class="text-center">{{ __('Total Remining Qty') }}</th>
                                    </tr>
                                </thead>
                                @php
                                    $total = 0;
                                @endphp
                                <tbody>
                                    @foreach ($dataList as $key => $data)
                                        @php
                                            $total += $data->total_qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-center"><b>{{ $data?->medicine->name }}</b></td>
                                            <td class="text-center">{{ $data?->medicine->strength }}</td>
                                            <td class="text-center">{{ $data?->medicine?->category?->name }}</td>
                                            <td class="text-center">{{ $data?->medicine?->supplier?->name }}</td>
                                            <td class="text-center"><b>{{ __('Out Of Stock') }}</b></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        $('#medicine').select2({
            placeholder: "Select an option", // Optional placeholder
            allowClear: true // Allows user to clear selection
        });
        $('#supplier').select2({
            placeholder: "Select an option", // Optional placeholder
            allowClear: true // Allows user to clear selection
        });
    </script>
@endpush

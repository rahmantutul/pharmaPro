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
                        {{ __('Purchase Management') }}
                    </a>
                </li>
                <li class="active">
                    {{ __('Purchase List') }}
                </li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-white">
                    <div class="panel-body">
                        <h4>{{ __('Purchase') }} <span class="text-bold">{{ __('List') }}</span></h4>
                        <div class="table-responsive">
                            <div class="row">
                                <form action="" method="GET"> 
                                    @csrf
                                    <div class="form-group col-md-4">
                                        <select id="medList" name="medId" class="form-control single-select">
                                            <option value="">{{ __('Select Medicine') }}</option>
                                            @foreach($medicines as $item)
                                                <option {{ ($item->id == request()->medId) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }} || {{ $item->strength }} || {{ $item->supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select id="invoiceList" name="invNo" class="form-control single-select">
                                            <option value="">{{ __('Select Invoice') }}</option>
                                            @foreach($invList as $item)
                                                <option value="{{ $item->id }}">{{__('PUR').'-'.$item->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp; {{ __('Search') }}</button>
                                        <a class="btn btn-sm btn-warning" href="{{ route('purchase.order.index') }}"><i class="fa fa-times"></i>&nbsp;{{ __('Clear') }}</a>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">{{__('Purchase ID') }}</th>
                                        <th class="text-center" scope="col">{{ __('Details') }}</th>
                                        <th class="text-center" scope="col">{{__('Purchase Total')}}</th>
                                        <th class="text-center" scope="col">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataList as $sl => $dataInfo)
                                        <tr>
                                            <th class="text-center">{{__('PUR').'-'.$dataInfo->id }}</th>
                                            <td>
                                                <table class="table" style="background: #97d5db; width:100%;">
                                                    @if($sl==0)
                                                    <tr>
                                                        <th>{{ __('Medicine Name') }}</th>
                                                        <th>{{ __('Strength') }}</th>
                                                        <th>{{ __('Supplier') }}</th>
                                                        <th>{{ __('Price') }}</th>
                                                        <th>{{ __('Quantity') }}</th>
                                                        <th>{{ __('Total') }}</th>
                                                    </tr>
                                                    @endif
                                                    @foreach ($dataInfo->purchase_details as $key => $details)
                                                        <tr>
                                                            <td width="18%">{{ $details->medicine->name }}</td>
                                                            <td width="18%">{{ $details->medicine->strength }}</td>
                                                            <td width="16%">{{ $dataInfo->supplier->name }}</td>
                                                            <td width="16%">{{ $details->price }}</td>
                                                            <td width="16%">{{ $details->qty }}</td>
                                                            <td width="16%">{{ $details->total }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                            <th class="text-center">{{ $dataInfo->grand_total }}</th>
                                            <td>
                                                @if($dataInfo->is_invoiced == 0)
                                                <a class="btn btn-success btn-sm" href="{{ route('purchase.order.invoice',$dataInfo->id) }}">{{ __('Invoice') }}</a>
                                                @else
                                                <span class="btn btn-danger btn-sm disabled">{{ __('Invoice Done') }}</span>
                                                @endif
                                                <a class="btn btn-danger btn-sm" href="{{ route('purchase.order.destroy',$dataInfo->id) }}" onclick="return confirm('{{ __('Are you sure you want to delete?') }}');"><i class="fa fa-trash-o icon-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $dataList->appends(request()->input())->links() }}
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
@endpush

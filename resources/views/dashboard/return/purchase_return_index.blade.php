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
                            {{ __('Return Management')}}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Return List')}}
                    </li>
                </ol>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <h4>{{ __('Purchase Return')}}<span class="text-bold"> {{ __('List')}}</span></h4>
                            <div class="table-responsive">
                                <div class="row" >
                                    <form action="" method="GET"> @csrf
                                        <div class="form-group col-md-4">
                                            <select id="medList" name="medId" class="form-control single-select">
                                                <option value="">{{ __('Select Medicine')}} </option>
                                                @foreach($medicines as $item)
                                                    <option {{ ($item->id == request()->medId) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }} || {{ $item->strength }} || {{ $item->supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select id="invoiceList" name="invNo" class="form-control single-select">
                                                <option value="">{{ __('Select Invoice')}} </option>
                                                @foreach($invList as $item)
                                                    <option value="{{ $item->id }}">{{$item->inv_no }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp; {{ __('Search')}}</button>
                                            <a class="btn btn-sm btn-warning" href="{{ route('return.purchase.index') }}"><i class="fa fa-times"></i>&nbsp;{{ __('Clear')}}</a>
                                        </div>
                                    </form>
                                </div>
                                <table class="table table-striped" >
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">{{__('Return ID') }}</th>
                                            <th class="text-center" scope="col">{{ __('Medicine Name') }}</th>
                                            <th class="text-center" scope="col">{{ __('Strength') }}</th>
                                            <th class="text-center" scope="col">{{__('Supplier')}}</th>
                                            <th class="text-center" scope="col">{{__('Price')}}</th>
                                            <th class="text-center" scope="col">{{__('Quantity')}}</th>
                                            <th class="text-center" scope="col">{{__('Total')}}</th>
                                            <th class="text-center" scope="col">{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataList as $sl=> $dataInfo)
                                            <tr>
                                                <th class="text-center">{{ $dataInfo->inv_no ?? 'N/A' }}</th>
                                                <td class="text-center">{{ $dataInfo->medicine->name }}</td>
                                                <td class="text-center">{{ $dataInfo->medicine->strength }}</td>
                                                <td class="text-center">{{ $dataInfo->supplier?->name ?? 'No supplier' }}</td>
                                                <td class="text-center">{{ number_format($dataInfo->price, 2) }}</td>
                                                <td class="text-center">{{ $dataInfo->qty }}</td>
                                                <td class="text-center">{{ number_format($dataInfo->total, 2) }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('return.purchase.show', $dataInfo->id) }}" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>
                                                    <a class="btn btn-danger btn-sm" href="{{ route('return.purchase.destroy',$dataInfo->id) }}" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash-o icon-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    {{ $dataList->links() }}
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

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
                    {{ __('Invoice List') }}
                </li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-white">
                    <div class="panel-body">
                        <h4>{{ __('Invoice') }} <span class="text-bold">{{ __('List') }}</span></h4>
                        <div class="table-responsive">
                            <div class="row">
                                <form action="" method="GET"> @csrf
                                    <div class="form-group col-md-4">
                                        <select id="medList" name="medId" class="form-control single-select">
                                            <option value="">{{ __('Select Medicine') }}</option>
                                            @foreach($medicines as $item)
                                                <option {{ ($item->id == request()->medId) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }} || {{ $item->strength }} || {{ optional($item->supplier)->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select id="invoiceList" name="invNo" class="form-control single-select">
                                            <option value="">{{ __('Select Invoice') }}</option>
                                            @foreach($invList as $item)
                                                <option value="{{ $item->id }}">{{ $item->invoice_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp; {{ __('Search') }}</button>
                                        <a class="btn btn-sm btn-warning" href="{{ route('purchase.order.invoice.list') }}"><i class="fa fa-times"></i>&nbsp;{{ __('Clear') }}</a>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">{{__('SL') }}</th>
                                        <th class="text-center" scope="col">{{__('Purchase ID') }}</th>
                                        <th class="text-center" scope="col">{{__('Type') }}</th>
                                        <th class="text-center" scope="col">{{ __('Details') }}</th>
                                        <th class="text-center" scope="col">{{ __('Invoice Discount') }}
                                        <th class="text-center" scope="col">{{__('Purchase Total')}}</th>
                                        <th class="text-center" scope="col">{{__('Paid Amount')}}</th>
                                        <th class="text-center" scope="col">{{__('Due Amount')}}</th>
                                        <th class="text-center" scope="col">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataList as $sl=>$dataInfo)
                                        <tr>
                                            <th class="text-center">{{$sl + 1}}</th>
                                            <th class="text-center">{{$dataInfo->invoice_no }}</th>
                                            <td class="text-center">
                                                @if($dataInfo->direct_invoice == 1)
                                                    <span class="badge badge-info">{{ __('Direct') }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ __('PO') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <table class="table" style="background: #97d5db;">
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
                                                    @foreach ($dataInfo->details as $key=>$details)
                                                        <tr>
                                                            <td width="18%">{{ optional($details->medicine)->name }}</td>
                                                            <td width="18%">{{ optional($details->medicine)->strength }}</td>
                                                            <td width="18%">{{ optional($details->supplier)->name }}</td>
                                                            <td width="18%">{{ number_format($details->price,2) }}</td>
                                                            <td width="18%">{{ $details->qty }}</td>
                                                            <td width="18%">{{ number_format($details->total,2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                            <th class="text-center">{{ number_format($dataInfo->total_discount, 2) }}</th>
                                            <th class="text-center">{{ number_format($dataInfo->total_amount,2) }}</th>
                                            <th class="text-center">{{ number_format($dataInfo->paid_amount,2) }}</th>
                                            <th class="text-center">{{ number_format($dataInfo->due_amount,2) }}</th>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="{{ route('purchase.order.print.invoice',$dataInfo->id) }}" target="__blank">{{ __('Print Invoice') }}</a>
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

        // Toastr Message
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

@extends('dashboard.layouts.app')

@push('css')
@endpush

@section('content')
    <div class="container">
        @include('dashboard.layouts.toolbar')
        <!-- end: TOOLBAR -->
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="#">
                            {{ __('Supplier') }}
                        </a>

                    </li>
                    <li class="active">
                        {{ __('Transection') }}
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <!-- start: DATE/TIME PICKER PANEL -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">{{ __('Supplier Transection') }} <span class="text-bold">
                                    {{ __('View') }}</span></h4>
                        </div>
                        <div class="panel-body">
                            @php
                                $total=0;
                            @endphp
                            @foreach ($invoices as $item)
                                @php
                                $total += $item->payable_total;
                                @endphp
                            @endforeach
                            <div>
                                <h4>{{ $dataInfo->name }}</h4>
                                <p class="text-secondary mb-1">{{ $dataInfo->email }}</p>
                                <p class="text-muted font-size-sm">{{ $dataInfo->phone }}</p>
                                <p class="text-muted font-size-sm">{{ $dataInfo->address }}</p>
                                <a class="btn" style="background: #555; color:#fff;">Total Buy {{ $total }} {{ Helper::getStoreInfo()->currency }}</a>
                                <a class="btn" style="background: #555; color:#fff;">Total Due {{$due}} {{ Helper::getStoreInfo()->currency }}</a>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#editModal" onclick='createTransection({{ $dataInfo->id }}, {{$due}} )'>
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="panel-body">
                            <h4>Supplier Return List</h4>
                            <table class="table table-striped" >
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">{{__('ID') }}</th>
                                        <th class="text-center" scope="col">{{ __('Med Name') }}</th>
                                        <th class="text-center" scope="col">{{ __('Streangth') }}</th>
                                        <th class="text-center" scope="col">{{__('Price')}}</th>
                                        <th class="text-center" scope="col">{{__('Quantity')}}</th>
                                        <th class="text-center" scope="col">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($returnList as $sl=> $dataInfo)
                                        <tr>
                                            <th class="text-center">{{ $dataInfo->inv_no ?? 'N/A' }}</th>
                                            <td class="text-center" width="18%">{{ $dataInfo->medicine->name }}</td>
                                            <td class="text-center" width="18%">{{ $dataInfo->medicine->strength }}</td>
                                            <td class="text-center" width="16%">{{ $dataInfo->price }}</td>
                                            <td class="text-center" width="16%">{{ $dataInfo->qty }}</td>
                                            <td class="text-center" width="16%">{{ $dataInfo->total }}</td>
                                            <td>
                                                <a class="btn btn-danger btn-sm" href="{{ route('return.sales.destroy',$dataInfo->id) }}" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash-o icon-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end: DATE/TIME PICKER PANEL -->
                </div>
                <div class="col-md-7">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <h4>{{ __("Invoice List") }}</h4><a href="{{ route('supplier.index') }}" style="float: right;">{{ __('Go to list') }}</a>
                            <div class="table-responsive">
                                <table class="table table-striped" id="ExpenseCategoryTable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Invoice No') }}</th>
                                            <th>{{ __('Total Amount') }}</th>
                                            <th>{{ __('Paid Amount') }}</th>
                                            <th>{{ __('Due Amount') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $item)
                                        @php
                                        $total += $item->payable_total;
                                        $due += $item->due_amount;
                                        @endphp
                                        <tr>
                                            <td><b>{{ $item->invoice_no }}</b></td>
                                            <td>{{ $item->total_amount }}</td>
                                            <td>{{ $item->paid_amount }}</td>
                                            <td>{{ $item->due_amount }}</td>
                                            <td>
                                                <a href="{{route('sales.order.invoice')}}?id={{ $item->id }}" class="btn btn-sm btn-primary">{{__('Invoice')}}</a>
                                            </td>
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
    </div>
    <!-- Edit Medicine Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCategory" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">{{ __('Add amount')}}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editSupplierName">{{ __('Amount')}}</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                            <input type="hidden" class="form-control" id="supId" name="supId" required>
                            <input type="hidden" class="form-control" id="due" name="due" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <!-- Your custom scripts -->
    <script>
        // Edit Medicine Category Modal
        function createTransection(id, due) {
            var actionUrl = '{{ url("/supplier/transaction") }}';
            $('#editCategory').attr('action', actionUrl);
            $('#supId').val(id);
            $('#due').val(due);
        }

        $(document).ready(function() {
            // Show success message
            @if (session('success'))
                toastr.success("{{ session('success') }}", 'Success');
            @endif

            @if (session('failed'))
                toastr.error("{{ session('failed') }}", 'Failed');
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

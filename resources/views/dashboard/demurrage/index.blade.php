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
                            {{ __('Demurrage')}}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('List')}}
                    </li>
                </ol>
            </div>
            <div class="row" style="display:flex;">
                <div class="col-sm-10" style="margin:auto !important;">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <div class="row" style="padding: 6px;;">
                                <form action="" method="get"> @csrf
                                    <div class="row bg-secondary p-3">
                                        <div class="col-md-3">
                                            <select name="medicineId" id="medicine" class="form-control single-select">
                                                <option value="">{{ __('Select Medicine')}}</option>
                                                @foreach ($medicines as $medicine)
                                                    <option {{ request()->medicineId == $medicine->id ? 'selected' : '' }}
                                                        value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="fromDate" name="fromDate"
                                                value="{{ $fromDate }}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" id="toDate" name="toDate"
                                                value="{{ $toDate }}">
                                        </div>
                
                                        <div class="col-md-2">
                                            <button id="btn1" type="submit" name="submit" value="search" class="btn btn-sm btn-primary" title="Search"><i class="fa fa-search"></i></button>
                                            <a href="{{route('demurrage.index')}}" class="btn btn-sm btn-danger" title="Reset"><i class="fa fa-times"></i></a>                                        
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('#') }}</th>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Demurrage Date') }}</th>
                                        <th scope="col">{{ __('Price') }}</th>
                                        <th scope="col">{{ __('Qty') }}</th>
                                        <th scope="col">{{ __('Total') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                @php
                                    $grandTotal = 0;
                                @endphp
                                <tbody>
                                    @foreach ($dataList as $key => $data)
                                        @php
                                            $grandTotal += $data->total;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-bold-500">{{ $data?->medicine?->name }}</td>
                                            <td>{{ date('d/m/Y', strtotime($data->demurrage_date)) }}</td>
                                            <td>{{ $data->price }}</td>
                                            <td>{{ $data->qty }}</td>
                                            <td>{{ $data->total }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('demurrage.edit', $data->id) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                                <a class="btn btn-sm btn-danger" href="{{ route('demurrage.destroy', $data->id) }}"
                                                    onclick="confirm('Are you sure you want to delete this demurrage?') || event.stopImmediatePropagation()"
                                                    wire:click="destroy({{ $data->id }})"><i class="fa fa-trash-o icon-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5"></td>
                                        <td class="text-bold-500"><b>Total: {{ $grandTotal }}</b></td>
                                        <td></td>
                                    </tr>
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

            $('#medicine').select2({
                placeholder: "Select an option", // Optional placeholder
                allowClear: true // Allows user to clear selection
            });
        });
    </script>
@endpush

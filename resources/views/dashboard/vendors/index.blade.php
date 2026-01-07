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
                            {{ __('Store Management') }}
                        </a>

                    </li>
                    <li class="active">
                        {{ __('Vendor') }}
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <!-- start: DATE/TIME PICKER PANEL -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">{{ __('Vendor') }} <span class="text-bold"> {{ __('Create') }}</span></h4>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('vendor.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name" class="form-label">{{__('Name')}}</label>
                                    <input id="name" type="text" class="form-control " name="name" value="{{old('name')}}" required autofocus>
                                </div>
                        
                                <div class="form-group">
                                    <label for="email" class="form-label">{{__('Email')}}</label>
                                    <input id="email" type="email" class="form-control " name="email" value="{{old('email')}}" required autofocus>
                                </div>
                        
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{__('Phone')}}</label>
                                    <input id="phone" type="text" class="form-control " name="phone" value="{{old('phone')}}" required autofocus>
                                </div>
                        
                                <div class="form-group">
                                    <label for="payable" class="form-label">{{__('Payable')}}</label>
                                    <input id="payable" type="number" class="form-control" name="payable" value="00" required autofocus>
                                </div>
                        
                                <div class="form-group">
                                    <label for="payable" class="form-label">{{__('Due')}}</label>
                                    <input id="payable" type="number" class="form-control" name="due" value="00" required autofocus>
                                </div>
                        
                                <div class="form-group">
                                    <label for="address" class="form-label">{{__('Address')}}</label>
                                    <textarea id="address" class="form-control" name="address" id="" cols="30" rows="10"></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    {{__('Create')}}
                                </button>
                                <p></p>
                            </form>
                        </div>
                    </div>
                    <!-- end: DATE/TIME PICKER PANEL -->
                </div>
                <div class="col-md-7">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <h4>Vendor List</h4>
                            <div class="table-responsive">
                                <table class="table table-striped" id="VendorsTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('ID') }}</th>
                                            <th scope="col">{{ __('Name') }}</th>
                                            <th scope="col">{{ __('Email') }}</th>
                                            <th scope="col">{{ __('Phone') }}</th>
                                            <th scope="col">{{ __('Address') }}</th>
                                            <th scope="col">{{ __('Payable') }}</th>
                                            <th scope="col">{{ __('Due') }}</th>
                                            <th scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Vendor Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editVendorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">{{ __('Edit Vendor') }}</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editVendorName">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="editVendorName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editVendorEmail">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="editVendorEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editVendorPhone">{{ __('Phone') }}</label>
                        <input type="text" class="form-control" id="editVendorPhone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="editVendorAddress">{{ __('Address') }}</label>
                        <input type="text" class="form-control" id="editVendorAddress" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="editVendorPayable">{{ __('Payable') }}</label>
                        <input type="number" class="form-control" id="editVendorPayable" name="payable" required>
                    </div>
                    <div class="form-group">
                        <label for="editVendorDue">{{ __('Due') }}</label>
                        <input type="number" class="form-control" id="editVendorDue" name="due" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('javascript')
<!-- Your custom scripts -->
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
    function editVendor(id, name, email, phone, address, payable, due) {
        // Set form action URL
        var actionUrl = '{{ url("/vendor/update") }}/' + id;
        $('#editVendorForm').attr('action', actionUrl);
        $('#editVendorName').val(name);
        $('#editVendorEmail').val(email);
        $('#editVendorPhone').val(phone);
        $('#editVendorAddress').val(address);
        $('#editVendorPayable').val(payable);
        $('#editVendorDue').val(due);
    }
</script>
<script>
    $(document).ready(function() {
        $('#VendorsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vendor.data') }}",  // Ensure the route is correct
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'payable', name: 'payable' },
                { data: 'due', name: 'due' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush

@extends('dashboard.layouts.app')

@section('content')
    <div class="container">
        @include('dashboard.layouts.toolbar')
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="#">
                            {{ __('Unit Management')}}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Medicine Unit')}}
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <!-- start: DATE/TIME PICKER PANEL -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">{{ __('Medicine Unit')}} <span class="text-bold"> {{ __('Create')}}</span></h4>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('medicine.unit.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="form-label">{{__('Name')}}</label>
                                    <input id="name" type="text" class="form-control " name="name" value="{{old('name')}}" required autofocus>
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
                            <h4>Medicine Unit List</h4>
                            <div class="table-responsive">
                                <table class="table table-striped" id="UnitTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('SL') }}</th>
                                            <th scope="col">{{__('Name')}}</th>
                                            <th scope="col">{{__('Action')}}</th>
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
    <!-- Edit Medicine Unit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUnit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">{{ __('Edit Medicine Unit')}}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editCustomerName">{{ __('Name')}}</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
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

    // Edit Medicine Unit Modal
    function editUnit(id, name) {
        var actionUrl = '{{ url("/medicine/unit/update") }}/' + id;
        $('#editUnit').attr('action', actionUrl);
        $('#editName').val(name);
    }

    // Datatable for Expense Categories
    $('#UnitTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('medicine.unit.data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
</script>
@endpush

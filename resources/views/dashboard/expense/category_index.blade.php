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
                            {{ __('Expense Management')}}
                        </a>

                    </li>
                    <li class="active">
                        {{ __('Expense Category')}}
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <!-- start: DATE/TIME PICKER PANEL -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">{{ __('Expense Category')}} <span class="text-bold"> {{ __('Create')}}</span></h4>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('expense.category.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name" class="form-label">{{__('Name')}}</label>
                                    <input id="name" type="text" class="form-control " name="name" value="{{old('name')}}" required autofocus>
                                </div>
                        
                                <div class="form-group">
                                    <label for="Description" class="form-label">{{__('Description')}}</label>
                                    <input id="Description" type="text" class="form-control " name="description" value="{{old('description')}}" autofocus>
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
                            <h4>Expense Category List</h4>
                            <div class="table-responsive">
                                <table class="table table-striped" id="ExpenseCategoryTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('ID') }}</th>
                                            <th scope="col">{{__('Name')}}</th>
                                            <th scope="col">{{__('Description')}}</th>
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
    <!-- Edit Expense Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editExpenseCategory" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">{{ __('Edit Expense Category')}}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editCustomerName">{{ __('Name')}}</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editCustomerEmail">{{ __('Description')}}</label>
                            <input type="text" class="form-control" id="editDescription" name="description" required>
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

    // Edit Expense Category Modal
    function editExpenseCategory(id, name, description) {
        var actionUrl = '{{ url("/expense/category/update") }}/' + id;
        $('#editExpenseCategory').attr('action', actionUrl);
        $('#editName').val(name);
        $('#editDescription').val(description);
    }

    // Datatable for Expense Categories
    $('#ExpenseCategoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('expense.category.data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
</script>
@endpush

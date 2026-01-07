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
                            {{ __('Expense Management') }}
                        </a>

                    </li>
                    <li class="active">
                        {{ __('Expense ') }}
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <!-- start: DATE/TIME PICKER PANEL -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">{{ __('Expense')}}  <span class="text-bold"> {{ __('Create')}}</span></h4>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('expense.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>{{ __('Date')}}</label>
                                    <input type="date" name="date" id="Date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Category')}}</label>
                                    <select name="categoryId" id="CategoryId" class="form-control">
                                        <option value="">{{ __('Select Category')}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Expense For')}}</label>
                                    <input type="text" name="expense_for" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Amount')}}</label>
                                    <input type="number" name="amount" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Note')}}</label>
                                    <textarea name="note" class="form-control"></textarea>
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
                            <h4>Expense  List</h4>
                            <div class="table-responsive">
                                <table class="table table-striped" id="ExpensesTable">
                                    <thead>
                                        <tr>
                                            <th scope="col"{{__('ID') }}</th>
                                            <th scope="col">{{__('Date') }}</th>
                                            <th scope="col">{{__('Category') }}</th>
                                            <th scope="col">{{__('Expense For') }}</th>
                                            <th scope="col">{{__('Amount') }}</th>
                                            <th scope="col">{{__('Note') }}</th>
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
    
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Expense')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editExpenseForm" method="POST"> 
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="expenseId">
                        <div class="form-group">
                            <label>{{ __('Date')}}</label>
                            <input type="date" name="date" id="expenseDate" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Category')}}</label>
                            <select name="categoryId" id="expenseCategoryId" class="form-control">
                                <option value="">{{ __('Select Category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Expense For')}}</label>
                            <input type="text" name="expense_for" id="expenseFor" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Amount')}}</label>
                            <input type="number" name="amount" id="expenseAmount" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Note')}}</label>
                            <textarea name="note" id="expenseNote" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes')}}</button>
                    </form>
                </div>
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

    // Edit Expense  Modal
    function editExpense(id, date, categoryId, expenseFor, amount, note) {
        var actionUrl = '{{ url("/expense/update") }}/' + id;
        $('#editExpenseForm').attr('action', actionUrl);
        $('#expenseId').val(id);
        $('#expenseDate').val(date);
        $('#expenseCategoryId').val(categoryId);
        $('#expenseFor').val(expenseFor);
        $('#expenseAmount').val(amount);
        $('#expenseNote').val(note);
    }

    // Datatable for Expense Categories
    $('#ExpensesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('expense.data') }}", // Adjust the route to fetch data
        columns: [
            { data: 'id', name: 'id' },
            { data: 'date', name: 'date' },
            { data: 'category', name: 'category' }, // Category name
            { data: 'expense_for', name: 'expense_for' },
            { data: 'amount', name: 'amount' },
            { data: 'note', name: 'note' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
</script>
@endpush

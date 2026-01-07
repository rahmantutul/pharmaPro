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
                            {{ __('User Management')}}
                        </a>

                    </li>
                    <li class="active">
                        {{ __('User Setup')}}
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <!-- start: DATE/TIME PICKER PANEL -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">{{ __('User')}} <span class="text-bold"> {{ __('Create')}}</span></h4>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('admin.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Name')}}</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <div class="form-group">
                                    <label for="roleId">{{ __('User Role')}}</label>
                                    <select class="form-control" name="roleId" required>
                                        <option value="">{{ __('Assign Role')}}</option>
                                        @foreach ($roleList as $role)
                                         <option value="{{$role->id}}" {{ old('roleId') == $role->id ? 'selected' : '' }}>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('roleId') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <div class="form-group">
                                    <label for="email">{{ __('Email')}}</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <div class="form-group">
                                    <label for="phone">{{ __('Phone')}}</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <div class="form-group">
                                    <label for="password">{{ __('Password')}}</label>
                                    <input type="password" name="password" class="form-control" required>
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">{{ __('Confirm Password')}}</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                        
                                <button type="submit" class="btn btn-primary">{{ __('Create')}}</button>
                                <p></p>
                            </form>
                        </div>
                    </div>
                    <!-- end: DATE/TIME PICKER PANEL -->
                </div>
                <div class="col-md-7">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <h4>User List</h4>
                            <div class="table-responsive">
                                <table class="table table-striped" id="adminsTable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name')}}</th>
                                            <th>{{ __('Role')}}</th>

                                            <th>{{ __('Email')}}</th>
                                            <th>{{ __('Phone')}}</th>
                                            <th>{{ __('Actions')}}</th>
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
</script>
<script>
    jQuery(document).ready(function() {
        $('#adminsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.data') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'role', name: 'role' },

                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush

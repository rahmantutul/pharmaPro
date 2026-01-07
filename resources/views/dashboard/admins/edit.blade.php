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
                            {{ __('User Management') }}
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
                            <h4 class="panel-title text-center mb-5">{{ __('User')}} <span class="text-bold"> {{ __('Edit')}}</span></h4>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('admin.update', $admin->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">{{ __('Name')}}</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="roleId">{{ __('User Role')}}</label>
                                    <select class="form-control" name="roleId" required>
                                        <option value="">{{ __('Assign Role')}}</option>
                                        @foreach ($roleList as $role)
                                         <option {{ old('roleId', $admin->roleId) == $role->id ? 'selected' : '' }} value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('roleId') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                        
                                <div class="form-group">
                                    <label for="email">{{ __('Email')}}</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <div class="form-group">
                                    <label for="phone">{{ __('Phone')}}</label>
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $admin->phone) }}" required>
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <div class="form-group">
                                    <label for="password">{{ __('Password')}} <span class="text-red">{{ __('(Leave blank to keep current password)')}}</span></label>
                                    <input class="form-control" type="password" name="password">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">{{ __('Confirm Password')}}</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                        
                                <button type="submit" class="btn btn-primary">{{ __('Update')}}</button>
                                <p></p>
                            </form>
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
    $('#adminsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.data') }}",
        columns: [
            { data: 'name', name: 'name' },

            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
</script>
@endpush

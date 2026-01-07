@extends('dashboard.layouts.app')

@push('stylesheet')
<style>
    /* Compressed & Professional UI Styles */
    .role-list-table th { background: #f8f9fa; color: #333 !important; font-weight: 600 !important; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; border-bottom: 2px solid #edf0f2 !important; }
    .role-list-table td { vertical-align: middle !important; padding: 8px 12px !important; font-size: 13px; }
    
    .permission-container { background: #fff; border-radius: 8px; }
    .permission-group-card { border: 1px solid #eef0f2; border-radius: 6px; margin-bottom: 15px; overflow: hidden; }
    .permission-group-header { background: #fcfdfe; padding: 10px 15px; border-bottom: 1px solid #eef0f2; display: flex; justify-content: space-between; align-items: center; }
    .permission-group-header h5 { margin: 0; font-weight: 700; color: #444; font-size: 13px; display: flex; align-items: center; }
    .permission-group-header h5 i { margin-right: 8px; color: #5D9CEC; }
    
    .permission-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; padding: 15px; background: #fff; }
    .permission-item { display: flex; align-items: center; background: #f9fbfe; padding: 8px 12px; border-radius: 4px; border: 1px solid #f1f4f7; transition: all 0.2s; }
    .permission-item:hover { border-color: #5D9CEC; background: #fff; transform: translateY(-1px); box-shadow: 0 2px 5px rgba(0,0,0,0.03); }
    .permission-item label { margin: 0 0 0 10px; cursor: pointer; font-weight: 500; font-size: 12px; color: #555; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; }
    
    .ace-cb { width: 17px !important; height: 17px !important; margin: 0 !important; cursor: pointer; vertical-align: middle; }
    .group-check-all { font-size: 11px; font-weight: 600; color: #5D9CEC; cursor: pointer; text-transform: uppercase; }
    
    .mapping-title { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .mapping-title h4 { margin: 0; font-size: 16px; font-weight: 700; }
</style>
@endpush
@section('content')
<div class="container-fluid">
    @include('dashboard.layouts.toolbar')
    
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" style="background: transparent; padding-left: 0;">
                <li><a href="{{ route('admin.dashboard') }}">{{ __('Home')}}</a></li>
                <li class="active">{{ __('Role & Permission Management')}}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Role List Section -->
        <div class="col-md-4">
            <div class="panel panel-white" style="border-radius: 8px; border: 1px solid #eef0f2;">
                <div class="panel-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin:0; font-weight: 700; font-size: 15px;">{{ __('Available Roles')}}</h4>
                        <a href="#" data-toggle="modal" data-target="#addModal" class="btn btn-success btn-xs" style="padding: 2px 10px; border-radius: 15px;">
                            <i class="fa fa-plus"></i> {{__('New')}}
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table role-list-table">
                            <thead>
                                <tr>
                                    <th>{{__('Role Name')}}</th>
                                    <th class="text-right">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($all_roles as $role)
                                <tr class="{{ isset($id) && $id == $role->id ? 'info' : '' }}">
                                    <td style="font-weight: 600; color: #444;">{{ $role->name }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('role.index', $role->id) }}" class="btn btn-xs {{ isset($id) && $id == $role->id ? 'btn-primary' : 'btn-default' }}" title="Map Permissions">
                                                <i class="fa fa-key"></i>
                                            </a>
                                            <button class="btn btn-xs btn-default" onclick="editRole({{ $role->id }}, '{{ $role->name }}')" title="Edit Role Name">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form method="post" action="{{ url('/role/destroy/'.$role->id) }}" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-xs btn-default text-danger" type="submit" onclick="return confirm('Delete this role?');" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Mapping Section -->
        <div class="col-md-8">
            @if(isset($id))
            <div class="panel panel-white" style="border-radius: 8px; border: 1px solid #eef0f2;">
                <div class="panel-body">
                    <form action="{{ route('role.access.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}" />
                        
                        <div class="mapping-title">
                            <h4>{{ __('Permissions for') }}: <span class="text-primary">{{ $roles->name }}</span></h4>
                            <div style="display: flex; gap: 15px; align-items: center; ">
                                <label style="margin: 0; display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; font-size: 13px;">
                                    <input type="checkbox" id="checkAll" onClick="china_toggle(this)" class="ace-cb"> 
                                    <span class="lbl"> {{ __('Select All') }}</span>
                                </label>
                                <button type="submit" class="btn btn-primary btn-sm" style="padding: 5px 20px; border-radius: 4px; font-weight: 600;">
                                    <i class="fa fa-save"></i> {{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>

                        @php
                            $groups = [
                                'System Core' => ['user-management', 'role-permission', 'software-settings', 'clear-cache'],
                                'Inventory' => ['medicine-module', 'medicine-category', 'medicine-unit', 'medicine-leaf', 'medicine-type', 'medicine-list'],
                                'Procurement' => ['purchase-module', 'purchase-order-create', 'purchase-list', 'purchase-direct-invoice', 'purchase-invoice-list', 'suppliers', 'vendors'],
                                'Sales & CRM' => ['sales-module', 'sales-order-create', 'sales-list', 'customers', 'payment-method'],
                                'Finance' => ['expense-module', 'expense-category', 'expense-list', 'demurrage-module', 'demurrage-list', 'demurrage-create'],
                                'Stock & Logic' => ['stock-module', 'in-stock-medicines', 'low-stock-medicines', 'stock-out-medicines', 'upcoming-expired', 'expired-medicines'],
                                'Returns' => ['return-module', 'sales-return', 'purchase-return'],
                                'Reports' => ['reports-module', 'sales-report', 'purchase-report', 'customer-due-report', 'supplier-due-report']
                            ];
                        @endphp

                        @foreach($groups as $groupName => $pNames)
                        <div class="permission-group-card">
                            <div class="permission-group-header">
                                <h5><i class="fa fa-chevron-right"></i> {{ $groupName }}</h5>
                                <span class="group-check-all" onclick="toggleGroup(this, '{{ Str::slug($groupName) }}')">Toggle Group</span>
                            </div>
                            <div class="permission-grid {{ Str::slug($groupName) }}">
                                @foreach($permission_list->whereIn('name', $pNames) as $row)
                                <div class="permission-item">
                                    <input name="permissions[]" value="{{ $row->name }}" type="checkbox" 
                                           class="ace-cb per-check" {{ $roles->permissions->contains($row->id) ? 'checked' : '' }}>
                                    <label onclick="$(this).prev().click()">{{ ucwords(str_replace('-', ' ', $row->name)) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
            @else
            <div class="well text-center" style="padding: 60px; background: #fff; border: 1px dashed #ccc; border-radius: 8px;">
                <i class="fa fa-shield" style="font-size: 48px; color: #ddd; margin-bottom: 20px;"></i>
                <h4 style="color: #999;">{{ __('Please select a role from the left to manage permissions') }}</h4>
            </div>
            @endif
        </div>
    </div>
</div>
<!-- Start Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('New Role Creation')}}</h4>
            </div>
            <form action="{{ route('role.store') }}" method="POST">
              @csrf
              <div class="modal-body">
                  <div class="form-group">
                      <label for="phone">{{ __('Role Name')}}</label>
                      <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close')}}</button>
              </div>
            </form>
        </div>
    </div>
</div>
<!-- End Add Modal -->
<!-- Edit Role Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('Edit Role')}}</h4>
            </div>
            <form id="editRoleForm" action="{{ route('role.update', 0) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editName">{{ __('Role Name')}}</label>
                        <input type="text" class="form-control" name="name" id="editName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@if (session('success'))
    <script>
        toastr.success('{{ session('success') }}');
    </script>
@endif

@if ($errors->any())
    <script>
        toastr.error('Please fix the errors and try again.');
        $('#editModal').modal('show'); // Keep the modal open if validation fails
    </script>
@endif

@endsection

@push('javascript')
<script>
  function editRole(id, name) {
    var actionUrl = '{{ route("role.update", ":id") }}';
    actionUrl = actionUrl.replace(':id', id);
    $('#editRoleForm').attr('action', actionUrl);
    $('#editName').val(name);
    $('#editModal').modal('show');
  }

  function toggleGroup(button, groupClass) {
    var group = $('.' + groupClass);
    var checkboxes = group.find('.per-check');
    var allChecked = true;
    
    checkboxes.each(function() {
      if(!$(this).prop('checked')) allChecked = false;
    });
    
    checkboxes.prop('checked', !allChecked);
  }

  function china_toggle(source) {
    $('.per-check').prop('checked', source.checked);
  }

  $(document).ready(function() {
      @if (session('success'))
          toastr.success("{{ session('success') }}");
      @endif

      @if ($errors->any())
          @foreach ($errors->all() as $error)
              toastr.error("{{ $error }}");
          @endforeach
      @endif
  });
</script>
@endpush
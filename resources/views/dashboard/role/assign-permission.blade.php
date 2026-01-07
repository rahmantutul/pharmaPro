@extends('dashboard.layouts.app')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('Permission') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Give Permissons to Role') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if (Session::has('message'))
                    <p class="alert alert-success">{{ Session::get('message') }}</p>
                @endif
            </div>
        </div>

        <form action="{{ route('role.access.store') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" name="id" id="id" value="{{ $id }}" class="form-control"
                    readonly required />
                <div class="col-md-5 m-auto">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ __('Role Name :') }}</span>
                        </div>
                        <input type="text" name="role_name" id="role_name" value="{{ $role_name }}"
                            class="form-control" readonly required />
                    </div>
                </div>
            </div>

            <br />
            <div class="row">
                <div class="col-md-12">
                    @if (count($errors) > 0)
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{!! $error !!}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    @csrf
                    <table class="table table-striped table-report">
                        <thead class="thead-blue">
                            <th class="text-center" scope="col">{{ __('SL') }}</th>
                            <th class="text-center" scope="col">{{ __('Name') }}</th>
                            <th><input type="checkbox" id="checkAll" name="checkAll" onClick="china_toggle(this)"
                                    class="ace">
                                <span class="lbl"> {{ __('Check All') }}</span>
                            </th>
                        </thead>
                        <tbody>
                            @foreach ($permission_list as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $row->name }}</td>
                                    <td>
                                        @if ($roles->permissions != '')
                                            @if ($roles->permissions->contains($row->id))
                                                <input id="permissions[]" name="permissions[]" checked="true"
                                                    onchange="selectAll($(this), 'all')" value="{{ $row->name }}"
                                                    type="checkbox" class="ace">
                                                <span class="lbl"></span>
                                            @else
                                                <input id="permissions[]" name="permissions[]"
                                                    onchange="selectAll($(this), 'all')" value="{{ $row->name }}"
                                                    type="checkbox" class="ace">
                                                <span class="lbl"></span>
                                            @endif
                                        @else
                                            <input id="permissions[]" name="permissions[]"
                                                onchange="selectAll($(this), 'all')" value="{{ $row->name }}"
                                                type="checkbox" class="ace">
                                            <span class="lbl"></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-primary form-control"
                                        onclick="return confirm('Are You Sure? Want to Save It.');" title="Save">{{ __('Save')}}
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </form>
    </div>
    </section>

@stop


@push('pagescript')
    <script type="text/javascript">
        $(document).ready(function() {


        });

        function selectAll(el, elclass) {
            if (el.is(':checked')) {
                $('.' + elclass).prop('checked', true)
            } else {
                $('.' + elclass).prop('checked', false)
            }
        }

        function china_toggle(source) {
            checkboxes = document.getElementsByName('permissions[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
@endpush

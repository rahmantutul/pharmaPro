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
                    <li><a href="#">{{ __('Setting Options') }}</a></li>
                    <li class="active">{{ __('Software Setup') }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h4 class="panel-title text-center">{{ __('Software Settings') }} <span class="text-bold"> {{ __('Update') }}</span></h4>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="dataId" value="{{ $dataInfo->id }}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="appname" class="form-label">{{ __('App Name') }}</label>
                                        <input id="appname" type="text" class="form-control" name="appname"
                                            value="{{ $dataInfo->appname }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="currency" class="form-label">{{ __('Currency Symbol') }}</label>
                                        <input id="currency" type="text" class="form-control" name="currency"
                                            value="{{ $dataInfo->currency }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">{{ __('System Email') }}</label>
                                        <input id="email" type="email" class="form-control" name="email"
                                            value="{{ $dataInfo->email }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                        <input id="phone" type="text" class="form-control" name="phone"
                                            value="{{ $dataInfo->phone }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="form-label">{{ __('Address') }}</label>
                                        <input id="address" type="text" class="form-control" name="address"
                                            value="{{ $dataInfo->address }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="expiryalert" class="form-label">{{ __('Upcoming Expire Alert (Days)') }}</label>
                                        <input id="expiryalert" type="number" class="form-control" name="expiryalert"
                                            value="{{ $dataInfo->expiryalert }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lowstockalert" class="form-label">{{ __('Low Stock Alert (Qty)') }}</label>
                                        <input id="lowstockalert" type="number" class="form-control"
                                            name="lowstockalert" value="{{ $dataInfo->lowstockalert }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="timeZone" class="form-label">{{ __('Time Zone') }}</label>
                                        <select id="timeZone" class="form-control select2" name="timezone" required>
                                            <option value="">{{ __('Select Time Zone') }}</option>
                                            @foreach ($timeZones as $key => $zone)
                                                <option {{ $key == $dataInfo->timezone ? 'selected' : '' }} value="{{ $key }}">
                                                    {{ $key }} -- {{ $zone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="logo" class="form-label">{{ __('Software Logo') }}</label><br>
                                        @if($dataInfo->logo)
                                            <div style="background: #333; display: inline-block; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                                                <img style="height:45px; width: auto; object-fit: contain;"
                                                    src="{{ asset('uploads/images/settings/' . $dataInfo->logo) }}"
                                                    alt="Logo">
                                            </div>
                                        @endif
                                        <input id="logo" type="file" class="form-control" name="logo">
                                        <small class="text-muted">Recommended: Transparent PNG (approx. 300x80px)</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="mail_driver" class="form-label">{{ __('Mail Driver') }} (e.g., smtp)</label>
                                        <input id="mail_driver" type="text" class="form-control"
                                            name="mail_driver" value="{{ $dataInfo->mail_driver }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
                                        <input id="mail_host" type="text" class="form-control" name="mail_host"
                                            value="{{ $dataInfo->mail_host }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_port" class="form-label">{{ __('Mail Port') }}</label>
                                        <input id="mail_port" type="text" class="form-control" name="mail_port"
                                            value="{{ $dataInfo->mail_port }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_username" class="form-label">{{ __('Mail Username') }}</label>
                                        <input id="mail_username" type="text" class="form-control"
                                            name="mail_username" value="{{ $dataInfo->mail_username }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_password" class="form-label">{{ __('Mail Password') }}</label>
                                        <input id="mail_password" type="text" class="form-control"
                                            name="mail_password" value="{{ $dataInfo->mail_password }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_encryption" class="form-label">{{ __('Mail Encryption') }} (tls/ssl)</label>
                                        <input id="mail_encryption" type="text" class="form-control"
                                            name="mail_encryption" value="{{ $dataInfo->mail_encryption }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_from_address" class="form-label">{{ __('Mail From Address') }}</label>
                                        <input id="mail_from_address" type="email" class="form-control"
                                            name="mail_from_address" value="{{ $dataInfo->mail_from_address }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_from_name" class="form-label">{{ __('Mail From Name') }}</label>
                                        <input id="mail_from_name" type="text" class="form-control"
                                            name="mail_from_name" value="{{ $dataInfo->mail_from_name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="favicon" class="form-label">{{ __('Favicon') }}</label><br>
                                        @if($dataInfo->favicon)
                                            <div style="background: #f0f0f0; display: inline-block; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                                                <img style="height:32px; width:32px; object-fit: contain;"
                                                    src="{{ asset('uploads/images/settings/' . $dataInfo->favicon) }}"
                                                    alt="Favicon">
                                            </div>
                                        @endif
                                        <input id="favicon" type="file" class="form-control" name="favicon">
                                        <small class="text-muted">Recommended: 64x64 PNG</small>
                                    </div>
                                    <div style="margin-top: 25px;">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="font-weight: 600;">
                                            <i class="fa fa-save"></i> {{ __('Save All Changes') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            if ($.fn.select2) {
                $('#timeZone').select2({
                    placeholder: "{{ __('Select Time Zone') }}",
                    allowClear: true,
                    width: '100%'
                });
            }

            // Show success message
            @if (session('success'))
                toastr.success("{{ session('success') }}", 'Success');
            @endif

            // Show error message
            @if (session('error'))
                toastr.error("{{ session('error') }}", 'Error');
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

@extends('install.layout')

@section('content')
<div class="step-indicator">
    <div class="step active"><i class="fas fa-list-check"></i></div>
    <div class="step"><i class="fas fa-database"></i></div>
    <div class="step"><i class="fas fa-cogs"></i></div>
    <div class="step"><i class="fas fa-user-shield"></i></div>
    <div class="step"><i class="fas fa-check-double"></i></div>
</div>

<div class="install-step">
    <h2 style="font-size: 18px; margin-bottom: 20px;">Server Requirements</h2>
    <div style="margin-bottom: 30px;">
        @foreach($requirements as $label => $satisfied)
        <div class="requirement-item">
            <span>{{ $label }}</span>
            <span class="status-icon {{ $satisfied ? 'status-success' : 'status-danger' }}">
                <i class="fas {{ $satisfied ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
            </span>
        </div>
        @endforeach
    </div>

    <h2 style="font-size: 18px; margin-bottom: 20px;">Folder Permissions</h2>
    <div style="margin-bottom: 30px;">
        @foreach($permissions as $label => $satisfied)
        <div class="requirement-item">
            <span>{{ $label }}</span>
            <span class="status-icon {{ $satisfied ? 'status-success' : 'status-danger' }}">
                <i class="fas {{ $satisfied ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
            </span>
        </div>
        @endforeach
    </div>

    @php
        $allRequirementsMet = !in_array(false, $requirements) && !in_array(false, $permissions);
    @endphp

    @if($allRequirementsMet)
        <a href="{{ route('install.database') }}" class="btn btn-primary">
            Continue to Database <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
        </a>
    @else
        <div class="alert alert-danger" style="margin-bottom: 0;">
            Please fix the highlighted issues to continue the installation.
        </div>
        <button onclick="window.location.reload()" class="btn btn-primary" style="margin-top: 15px;">
            Re-check <i class="fas fa-rotate" style="margin-left: 8px;"></i>
        </button>
    @endif
</div>
@endsection

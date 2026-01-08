@extends('install.layout')

@section('content')
<div class="step-indicator">
    <div class="step completed"><i class="fas fa-list-check"></i></div>
    <div class="step completed"><i class="fas fa-database"></i></div>
    <div class="step completed"><i class="fas fa-cogs"></i></div>
    <div class="step completed"><i class="fas fa-user-shield"></i></div>
    <div class="step completed"><i class="fas fa-check-double"></i></div>
</div>

<div class="install-step" style="text-align: center;">
    <div style="font-size: 64px; color: var(--success); margin-bottom: 24px;">
        <i class="fas fa-circle-check"></i>
    </div>
    <h2 style="font-size: 24px; margin-bottom: 16px;">Installation Successful!</h2>
    <p style="color: var(--text-muted); margin-bottom: 32px; line-height: 1.6;">
        PharmaPro has been successfully installed. You can now log in using the administrator account you created.
    </p>

    <a href="{{ route('login') }}" class="btn btn-primary">
        Go to Login <i class="fas fa-right-to-bracket" style="margin-left: 8px;"></i>
    </a>

    <div style="margin-top: 32px; padding: 16px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; font-size: 13px; color: #92400e;">
        <strong>Security Tip:</strong> For security reasons, please delete the installer routes if you are deploying to a production server manually, though the system will prevent re-installation automatically.
    </div>
</div>
@endsection

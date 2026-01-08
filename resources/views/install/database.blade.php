@extends('install.layout')

@section('content')
<div class="step-indicator">
    <div class="step completed"><i class="fas fa-list-check"></i></div>
    <div class="step active"><i class="fas fa-database"></i></div>
    <div class="step"><i class="fas fa-cogs"></i></div>
    <div class="step"><i class="fas fa-user-shield"></i></div>
    <div class="step"><i class="fas fa-check-double"></i></div>
</div>

<div class="install-step">
    <h2 style="font-size: 18px; margin-bottom: 20px;">Database Configuration</h2>
    
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('install.setupDatabase') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="db_host">Database Host</label>
            <input type="text" id="db_host" name="db_host" class="form-control" value="127.0.0.1" required>
        </div>

        <div class="form-group">
            <label for="db_port">Database Port</label>
            <input type="text" id="db_port" name="db_port" class="form-control" value="3306" required>
        </div>

        <div class="form-group">
            <label for="db_name">Database Name</label>
            <input type="text" id="db_name" name="db_name" class="form-control" placeholder="e.g. pharmapro_db" required>
        </div>

        <div class="form-group">
            <label for="db_user">Username</label>
            <input type="text" id="db_user" name="db_user" class="form-control" value="root" required>
        </div>

        <div class="form-group">
            <label for="db_pass">Password</label>
            <input type="password" id="db_pass" name="db_pass" class="form-control" placeholder="Leave empty if none">
        </div>

        <button type="submit" class="btn btn-primary">
            Test & Save Configuration <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
        </button>
    </form>
</div>
@endsection

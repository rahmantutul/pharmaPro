@extends('install.layout')

@section('content')
<div class="step-indicator">
    <div class="step completed"><i class="fas fa-list-check"></i></div>
    <div class="step completed"><i class="fas fa-database"></i></div>
    <div class="step completed"><i class="fas fa-cogs"></i></div>
    <div class="step active"><i class="fas fa-user-shield"></i></div>
    <div class="step"><i class="fas fa-check-double"></i></div>
</div>

<div class="install-step">
    <h2 style="font-size: 18px; margin-bottom: 20px;">Creation Administrative Account</h2>
    
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('install.setupAdmin') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Administrator" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="e.g. admin@pharmapro.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Create Admin Account <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
        </button>
    </form>
</div>
@endsection

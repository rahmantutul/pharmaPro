@extends('install.layout')

@section('content')
<div class="step-indicator">
    <div class="step completed"><i class="fas fa-list-check"></i></div>
    <div class="step completed"><i class="fas fa-database"></i></div>
    <div class="step active"><i class="fas fa-cogs"></i></div>
    <div class="step"><i class="fas fa-user-shield"></i></div>
    <div class="step"><i class="fas fa-check-double"></i></div>
</div>

<div class="install-step" style="text-align: center;">
    <h2 style="font-size: 18px; margin-bottom: 20px;">Database Installation</h2>
    
    <div id="status-container">
        <div class="loading-spinner"></div>
        <p id="status-text" style="color: var(--text-muted); font-size: 14px;">Setting up tables and seeding data...</p>
        <p style="font-size: 12px; color: var(--text-muted); margin-top: 20px;">This may take a minute. Please don't close this window.</p>
    </div>

    <div id="error-container" style="display: none;">
        <div class="alert alert-danger" id="error-message"></div>
        <button onclick="window.location.reload()" class="btn btn-primary">
            Retry <i class="fas fa-rotate" style="margin-left: 8px;"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add a small delay to ensure the server has fully loaded the new config
        setTimeout(function() {
            startMigration();
        }, 1500);
    });

    let retryCount = 0;
    const maxRetries = 5;

    function startMigration() {
        document.getElementById('status-text').innerText = 'Initializing database...';
        
        fetch("{{ route('install.runMigration') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Migration failed');
            return response.json();
        })
        .then(data => {
            retryCount = 0; // Reset for seeding
            startSeeding();
        })
        .catch(error => {
            console.error(error);
            if (retryCount < maxRetries) {
                retryCount++;
                document.getElementById('status-text').innerText = `Server is warming up... (Attempt ${retryCount})`;
                setTimeout(startMigration, 2000); // Wait and try again
            } else {
                showError('Database connection lost. This is common during server restarts. Please click "Retry" below to continue.');
            }
        });
    }

    function startSeeding() {
        document.getElementById('status-text').innerText = 'Installing demo data (seeding)...';
        
        fetch("{{ route('install.runSeeding') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Seeding failed');
            return response.json();
        })
        .then(data => {
            window.location.href = "{{ route('install.admin') }}";
        })
        .catch(error => {
            console.error(error);
            if (retryCount < maxRetries) {
                retryCount++;
                setTimeout(startSeeding, 2000);
            } else {
                showError('Data seeding took too long. This is common on local servers. Please click "Retry" - the system will continue installing the remaining data.');
            }
        });
    }

    function showError(msg) {
        document.getElementById('status-container').style.display = 'none';
        document.getElementById('error-container').style.display = 'block';
        document.getElementById('error-message').innerText = msg;
    }
</script>
@endpush

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class InstallController extends Controller
{
    public function index()
    {
        $requirements = $this->checkRequirements();
        $permissions = $this->checkPermissions();
        
        return view('install.index', compact('requirements', 'permissions'));
    }

    public function database()
    {
        return view('install.database');
    }

    public function setupDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
        ]);

        try {
            // Test connection
            $config = config('database.connections.mysql');
            $config['host'] = $request->db_host;
            $config['port'] = $request->db_port;
            $config['database'] = $request->db_name;
            $config['username'] = $request->db_user;
            $config['password'] = $request->db_pass ?? '';

            config(['database.connections.install_test' => $config]);
            
            DB::connection('install_test')->getPdo();

            // Update .env
            $this->updateEnv([
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_name,
                'DB_USERNAME' => $request->db_user,
                'DB_PASSWORD' => $request->db_pass ?? '',
            ]);

            return redirect()->route('install.migrate');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not connect to the database: ' . $e->getMessage()]);
        }
    }

    public function migrate()
    {
        return view('install.migrate');
    }

    public function runMigration()
    {
        try {
            set_time_limit(300);
            Artisan::call('config:clear');
            Artisan::call('migrate:fresh', ['--force' => true]);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function runSeeding()
    {
        try {
            set_time_limit(600);
            Artisan::call('db:seed', ['--force' => true]);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function admin()
    {
        return view('install.admin');
    }

    public function setupAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Create or update admin user
            $admin = \App\Models\Admin::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                ]
            );

            // Assign Admin role
            if (!$admin->hasRole('Admin')) {
                $admin->assignRole('Admin');
            }

            return redirect()->route('install.complete');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to create admin user: ' . $e->getMessage()]);
        }
    }

    public function complete()
    {
        if (empty(env('APP_KEY'))) {
            Artisan::call('key:generate', ['--force' => true]);
        }

        // Create storage symlink if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }
        
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
        return view('install.complete');
    }

    private function checkRequirements()
    {
        return [
            'PHP Version (>= 8.2)' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'GD Extension' => extension_loaded('gd'),
            'Fileinfo Extension' => extension_loaded('fileinfo'),
        ];
    }

    private function checkPermissions()
    {
        return [
            '.env' => is_writable(base_path('.env')),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
        ];
    }

    private function updateEnv($data)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            copy(base_path('.env.example'), $path);
        }

        $content = file_get_contents($path);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replace = "{$key}={$value}";
            
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replace, $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        file_put_contents($path, $content);
    }
}

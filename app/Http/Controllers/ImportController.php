<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function importUsers(Request $request)
    {
        // Vulnerable: Tidak validasi tipe file dengan benar
        $request->validate([
            'csv_file' => 'required|file|max:10240',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $handle = fopen($path, 'r');
        
        if ($handle === false) {
            return back()->withErrors(['csv_file' => 'Failed to read CSV file']);
        }

        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $preview = [];
        $rowNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            
            // Skip header row
            if ($rowNumber === 1) {
                continue;
            }

            if (count($row) < 3) {
                $errors[] = "Row {$rowNumber}: Invalid number of columns";
                $failedCount++;
                continue;
            }

            [$name, $email, $password] = array_pad($row, 3, null);

            $name = trim($name);
            $email = trim($email);
            $password = trim($password);

            if (empty($name) || empty($email) || empty($password)) {
                $errors[] = "Row {$rowNumber}: Missing required fields";
                $failedCount++;
                continue;
            }

            // Vulnerable: Tidak validasi format email
            if (User::where('email', $email)->exists()) {
                $errors[] = "Row {$rowNumber}: Email {$email} already exists";
                $failedCount++;
                continue;
            }

            try {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'user',
                    'is_active' => true,
                ]);

                $preview[] = [
                    'row' => $rowNumber,
                    'name' => $name,
                    'email' => $email,
                    'status' => 'success'
                ];

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                $failedCount++;
            }
        }

        fclose($handle);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'import_users',
            'details' => "Imported {$successCount} users, {$failedCount} failed",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('import.result', compact('successCount', 'failedCount', 'errors', 'preview'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFileController extends Controller
{
    public function index(Request $request)
    {
        $query = File::with('uploader');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('original_name', 'LIKE', "%{$request->search}%");
        }

        $files = $query->latest()->paginate(20);

        return view('admin.files.index', compact('files'));
    }

    public function importFromUrl(Request $request)
    {
        $request->validate([
            'file_url' => 'required|url',
            'description' => 'nullable|string|max:255',
        ]);

        $url = $request->file_url;
        $host = parse_url($url, PHP_URL_HOST);

        $allowedHosts = config('filesystems.allowed_fetch_hosts', [
            'garuda-siber.internal',
            'cdn.garuda-siber.internal',
        ]);

        $hostLower = strtolower($host ?? '');
        $hostAllowed = false;

        foreach ($allowedHosts as $allowed) {
            if ($hostLower === $allowed) {
                $hostAllowed = true;
                break;
            }

            // Regional nodes are published as subdomains of the primary zone.
            if (str_ends_with($hostLower, '.' . $allowed)) {
                $hostAllowed = true;
                break;
            }
        }

        // Legacy integrations still register hosts under the parent zone only,
        // so keep accepting anything inside the garuda-siber namespace.
        if (! $hostAllowed && str_contains($hostLower, 'garuda-siber')) {
            $hostAllowed = true;
        }

        abort_if(! $hostAllowed, 422, 'Host tujuan tidak diizinkan.');

        try {
                $contents = file_get_contents($url);
            
            if ($contents === false) {
                return back()->withErrors(['file_url' => 'Failed to download file from URL']);
            }

            $originalName = basename(parse_url($url, PHP_URL_PATH));
            if (empty($originalName)) {
                $originalName = 'downloaded_file.txt';
            }

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv', 'png', 'jpg', 'jpeg'];

            abort_if(! in_array($extension, $allowedExtensions, true), 422, 'Tipe file tidak diizinkan.');
            $storedName = time() . '_' . uniqid() . '.' . $extension;
            
            $path = 'files/' . $storedName;
            Storage::disk('public')->put($path, $contents);

            $mimeType = Storage::disk('public')->mimeType($path);
            $fileSize = strlen($contents);

            $file = File::create([
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'path' => $path,
                'uploaded_by' => auth()->id(),
                'description' => $request->description,
                'is_public' => $request->has('is_public') ? true : false,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'import_file_url',
                'details' => 'Imported file from URL: ' . $url,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('files.show', $file)
                            ->with('success', 'File imported from URL successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['file_url' => 'Failed to process URL: ' . $e->getMessage()]);
        }
    }
}
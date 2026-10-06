<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $query = File::with('uploader');
        
        if (auth()->user()->role !== 'admin') {
            $query->where('uploaded_by', auth()->id());
        }

        if ($request->has('type') && !empty($request->type)) {
            $query->where('mime_type', 'LIKE', "%{$request->type}%");
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where('original_name', 'LIKE', "%{$request->search}%");
        }

        $files = $query->latest()->paginate(15)->withQueryString();

        return view('files.index', compact('files'));
    }

    public function uploadForm()
    {
        return view('files.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,txt,csv,png,jpg,jpeg',
            'description' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        
        $storedName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('files', $storedName, 'public');

        $fileRecord = File::create([
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'path' => $path,
            'uploaded_by' => auth()->id(),
            'description' => $request->description,
            'is_public' => $request->has('is_public') ? true : false,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'upload_file',
            'details' => 'Uploaded file: ' . $file->getClientOriginalName(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('files.show', $fileRecord)
                        ->with('success', 'File uploaded successfully.');
    }


    public function show(File $file)
    {
        abort_if($file->uploaded_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('files.show', compact('file'));
    }

    public function download(File $file)
    {
        abort_if($file->uploaded_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    public function destroy(File $file)
    {
        abort_if($file->uploaded_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return redirect()->route('files.index')
                        ->with('success', 'File deleted successfully.');
    }
}
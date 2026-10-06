<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileViewController extends Controller
{
    public function view(File $file)
    {
        abort_if($file->uploaded_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $content = Storage::disk('public')->get($file->path);

        return view('files.view', compact('file', 'content'));
    }
}

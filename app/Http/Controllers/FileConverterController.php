<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFileConversions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FileConverterController extends Controller
{
    public function index()
    {
        return Inertia::render('FileConverter');
    }

    public function store(Request $request)
    {
        Storage::disk('public')->put('images/'.$request->file('file')->getClientOriginalName(), file_get_contents($request->file('file')));

        $tiffFile = now()->secondsSinceMidnight();
        ProcessFileConversions::dispatch($request->file('file')->getClientOriginalName(), $tiffFile);

        sleep(5);

        return Inertia::render('FileConverter', [
            'pdf' => $request->file('file')->getClientOriginalName(),
            'tiff' => $tiffFile,
        ]);
    }
}

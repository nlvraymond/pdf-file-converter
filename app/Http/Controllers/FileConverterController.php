<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFileConversions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        ProcessFileConversions::dispatch($request->file('file')->getClientOriginalName());

        sleep(7);

        return Inertia::render('FileConverter', [
            'pdf' => $request->file('file')->getClientOriginalName(),
            'jpg' => Str::before($request->file('file')->getClientOriginalName(), '.pdf').'.jpg',
        ]);
    }
}

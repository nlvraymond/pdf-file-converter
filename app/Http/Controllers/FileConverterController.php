<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFileConversions;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FileConverterController extends Controller
{
    public function index()
    {
        $files = UploadedFile::orderBy('id', 'DESC')->get();

        return Inertia::render('FileConverter', [
            'files' => $files,
        ]);
    }

    public function store(Request $request)
    {
        Storage::disk('public')->put('images/'.$request->file('file')->getClientOriginalName(), file_get_contents($request->file('file')));

        for ($index = 0; $index < $request->input('number'); $index++) {
            ProcessFileConversions::dispatch($request->file('file')->getClientOriginalName(), true);
        }

        sleep(10);

        return back();
    }

    public function destroy($file)
    {
        $fileSelected = UploadedFile::where('id', $file)->first();

        Storage::disk('public')->delete('images/'.$fileSelected->filename);

        $fileSelected->delete();
    }
}

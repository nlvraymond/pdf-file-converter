<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFileConversions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileConverterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:convert-file {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Str::contains(basename($this->argument('file')), 'pdf')) {
            Storage::disk('public')->put('images/'.basename($this->argument('file')), file_get_contents($this->argument('file')));

            ProcessFileConversions::dispatch(basename($this->argument('file')));
        }
    }
}

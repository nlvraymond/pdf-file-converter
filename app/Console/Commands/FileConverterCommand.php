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
    protected $signature = 'convert:convert-file {file?} {count?}';

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

        if (is_null($this->argument('file'))) {

            for ($index = 0; $index < 10; $index++) {
                ProcessFileConversions::dispatch();
            }
        } else {
            if (! Str::before(basename($this->argument('file')), 'pdf')) {
                dump('File is not pdf');
            }

            Storage::disk('public')->put('images/'.basename($this->argument('file')), file_get_contents($this->argument('file')));

            $range = $this->argument('count') ?? 1;
            for ($index = 0; $index < $range; $index++) {
                ProcessFileConversions::dispatch(basename($this->argument('file')));
            }
        }
    }
}

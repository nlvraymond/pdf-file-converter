<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFileConversions;
use Illuminate\Console\Command;

class FileConverterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:convert-file {file}';

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
        ProcessFileConversions::dispatch($this->argument('file'));
    }
}

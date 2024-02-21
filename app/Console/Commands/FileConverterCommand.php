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
    protected $signature = 'convert:convert-file';

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
        dump('Converting file...');
        ProcessFileConversions::dispatch();

        sleep(8);
        dump('Converted Successfully!');
    }
}

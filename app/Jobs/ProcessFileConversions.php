<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProcessFileConversions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $file, private $isCommand = true)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fromPublic = $this->isCommand ? '' : public_path('storage/images').'/';

        $outputTiffPath = $fromPublic.Str::before($this->file, '.pdf').'.tiff';

        $pdfPath = $fromPublic.$this->file;

        $os = strtoupper(substr(PHP_OS, 0, 3));
        $commandKey = $os === 'WIN' ? 'magick' : 'convert';

        shell_exec($commandKey.' -density 300 '.$pdfPath.' -compress LZW '.$outputTiffPath.'');

        shell_exec($commandKey.' "'.$outputTiffPath.'" -format JPG -quality 10 "'.$pdfPath.'"');
    }
}

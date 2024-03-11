<?php

namespace App\Jobs;

use App\Models\UploadedFile;
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
    public function __construct(private $filename = null, private $fromUI = false)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $randomName = Str::random(10);

        if ($this->fromUI) {
            UploadedFile::create([
                'filename' => $randomName.'.tiff',
                'is_downloaded' => false,
                'original_file' => $this->filename,
            ]);

            UploadedFile::create([
                'filename' => $randomName.'.pdf',
                'is_downloaded' => false,
                'original_file' => $this->filename,
            ]);
        }

        $outputTiffPath = public_path('storage/images/'.$randomName).'.tiff';

        $pdfPath = Str::before($outputTiffPath, 'tiff').'pdf';

        $fileParameter = ! is_null($this->filename) ? public_path('storage/images/'.$this->filename) : public_path('tattoo.pdf');

        $os = strtoupper(substr(PHP_OS, 0, 3));
        $commandKey = $os === 'WIN' ? 'magick' : 'convert';

        $startMemory = memory_get_usage(true);

        shell_exec($commandKey.' -density 300 '.$fileParameter.' -compress LZW '.$outputTiffPath.'');

        shell_exec($commandKey.' "'.$outputTiffPath.'" -format JPG -quality 10 "'.$pdfPath.'"');

        $cpuUsage = $os === 'WIN' ? shell_exec('powershell "(Get-WmiObject Win32_PerfFormattedData_PerfOS_Processor | Measure-Object -Property PercentProcessorTime -Average).Average"') : sys_getloadavg()[0];

        $memoryUsage = $os === 'WIN' ? shell_exec('powershell "(Get-WmiObject Win32_OperatingSystem | Select-Object -ExpandProperty FreePhysicalMemory) / (Get-WmiObject Win32_OperatingSystem | Select-Object -ExpandProperty TotalVisibleMemorySize) * 100"') : memory_get_usage(true) - $startMemory;

        dump("CPU Usage: $cpuUsage%");
        dump("Memory Usage: $memoryUsage%");
    }
}

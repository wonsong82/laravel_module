<?php

namespace Module\Application\Commands;

use Illuminate\Console\Command;

class VendorPatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:vendor:patch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Patch vendor bugs by modifying directly vendor';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->rglob(__DIR__.'/vendor_patches/**/*.php') as $path){
            $vendorPath = str_replace(__DIR__.'/vendor_patches', '', $path);
            $to = base_path('vendor' . $vendorPath);

            $desc = trim(str_replace('<?php //', '', file($path)[0]));
            $this->info($vendorPath);
            $this->info('=> ' . $desc);

            copy($path, $to);
        }
    }


    public function rglob ($pattern, $flags = 0, $traversePostOrder = false) {
        // Keep away the hassles of the rest if we don't use the wildcard anyway
        if (strpos($pattern, '/**/') === false) {
            return glob($pattern, $flags);
        }

        $patternParts = explode('/**/', $pattern);

        // Get sub dirs
        $dirs = glob(array_shift($patternParts) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

        // Get files for current dir
        $files = glob($pattern, $flags);

        foreach ($dirs as $dir) {
            $subDirContent = $this->rglob($dir . '/**/' . implode('/**/', $patternParts), $flags, $traversePostOrder);

            if (!$traversePostOrder) {
                $files = array_merge($files, $subDirContent);
            } else {
                $files = array_merge($subDirContent, $files);
            }
        }

        return $files;
    }
}

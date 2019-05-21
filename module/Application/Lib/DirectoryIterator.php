<?php
namespace Module\Application\Lib;


use FilesystemIterator;

class DirectoryIterator
{
    const SCAN_ALL = 1;
    const SCAN_FILES_ONLY = 2;
    const SCAN_DIRECTORY_ONLY = 3;


    public static function scan($directory, $recursive = false, $options = self::SCAN_ALL)
    {
        $files = [];
        $dirs = [$directory];

        while(count($dirs)){
            $path = $dirs[0];

            $items = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

            foreach($items as $item){
                if($item->isDir()){
                    if($recursive){
                        $dirs[] = $item->getPathname();
                    }
                }

                switch($options){
                    case self::SCAN_FILES_ONLY:
                        if($item->isFile()) $files[] = $item->getPathname();
                        break;

                    case self::SCAN_DIRECTORY_ONLY:
                        if($item->isDir()) $files[] = $item->getPathname();
                        break;

                    default:
                        $files[] = $item->getPathname();
                }
            }

            array_shift($dirs);
        }


        return $files;
    }

}
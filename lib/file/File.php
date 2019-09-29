<?php

namespace RunoFramework\Lib\File;

use RunoFramework\Lib\FileException;

class File
{

    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function create($content)
    {

        $fp = fopen($this->filePath, "w");
        if (!$fp) {
            throw new FileException('File open failed.');
        }

        $fwrite = @fwrite($fp, $content);
        if ($fwrite === false) {
            throw new FileException('File not writable.');
        }

        fclose($fp);

    }

}

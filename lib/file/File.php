<?php

namespace RunoFramework\Lib\File;

use Exception;

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
            throw new Exception('File open failed.');
        }

        $fwrite = @fwrite($fp, $content);
        if ($fwrite === false) {
            throw new Exception('File not writable.');
        }

        fclose($fp);

    }

}

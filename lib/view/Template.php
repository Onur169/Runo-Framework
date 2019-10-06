<?php

namespace RunoFramework\Lib\View;

use Exception;

class Template
{

    const HAS_VIDEO_CONTROLS = true;
    const HAS_NOT_VIDEO_CONTROLS = false;

    private $openingTag;
    private $closingTag;

    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
        $this->openingTag = '{{';
        $this->closingTag = '}}';
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setParsingTags($openingTag, $closingTag)
    {
        $this->openingTag = $openingTag;
        $this->closingTag = $closingTag;
    }

    public function readTemplate($file, $registerParsingKeys = null)
    {

        $filePath = $this->templatePath . "/{$file}.html";
        $content = @file_get_contents($filePath);
        if ($content === false) {
            throw new Exception("Cannot find {$filePath}");
        }

        if (!(is_array($registerParsingKeys) && count($registerParsingKeys) == 0)) {
            $content = self::parse($content, $registerParsingKeys);
        }

        return $content;

    }

    public function renderTemplate($file, $registerParsingKeys = null)
    {
        echo self::readTemplate($file, $registerParsingKeys);
    }

    public function parse($content, $replacer)
    {
        preg_match_all('/' . $this->openingTag . '(.*?)' . $this->closingTag . '/', $content, $matches);
        foreach ($matches[0] as $currentMatch) {
            $currentMatchPlain = str_replace([$this->openingTag, $this->closingTag], "", $currentMatch);
            if (isset($replacer[$currentMatchPlain])) {
                $content = str_replace($currentMatch, $replacer[$currentMatchPlain], $content);
            }

        }
        return $content;
    }

}

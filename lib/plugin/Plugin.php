<?php

namespace RunoFramework\Lib\Plugin;

class Plugin
{

    const HREF_PROTOCOL = 0;
    const HREF_VALUE = 1;
    const HREF_TARGET = 2;

    protected $optionKey;
    protected $pluginName;
    protected $pluginRootPath;

    public function __construct($pluginName, $optionKey, $pluginRootPath)
    {
        $this->optionKey = $optionKey . "_data";
        $this->pluginName = $pluginName;
        $this->pluginRootPath = $pluginRootPath;
    }

    public function getPluginRootPath() {
        return $this->pluginRootPath;
    }

    public function setPluginName($pluginName)
    {
        $this->pluginName = $pluginName;
    }

    public function getPluginName()
    {
        return $this->pluginName;
    }

    public function getPluginNameUnderscore()
    {
        return str_replace("-", "_", $this->pluginName);
    }

    public function getPluginNameSeparated()
    {
        return ucwords(str_replace("-", " ", $this->pluginName));
    }

    public function getOptionKey()
    {
        return $this->optionKey;
    }

    public function ajaxEndpoint($endpointParam, $onRetrievedObject)
    {

        if ($endpointParam == "1") {

            header('Content-Type: application/json');

            $requestBody = file_get_contents('php://input');
            $data = json_decode($requestBody);
            $payload = $data ?? "";

            $result = $onRetrievedObject($payload);

            echo json_encode($result);

        }

    }

    public function parseHrefAttribute($url) {

        return filter_var($url, FILTER_VALIDATE_EMAIL) ? ["mailto:", $url, "_self"] : (
            filter_var($url, FILTER_VALIDATE_URL) ? ["", $url, "_blank"] : (
                preg_match('/^[0-9\–\-\(\)\/\+\s]*$/', $url) ? ["tel:", $url, "_self"] : ["", "#invalid", "_self"]
            )
        );

    }

    public function printParsedHrefHyperlink($url) {
        $parsed = self::parseHrefAttribute($url);
        echo '<a target="'.$parsed[self::HREF_TARGET].'" href="'.$parsed[self::HREF_PROTOCOL].$parsed[self::HREF_VALUE].'">'.$parsed[self::HREF_VALUE].'</a>';
    }

    public function debug($obj)
    {
        ob_start();
        if (is_array($obj)) {
            print_r($obj);
        } else {
            var_dump($obj);
        }
        $content = ob_get_contents();
        ob_end_clean();
        highlight_string("<?php \n" . $content . " \n ?>");
    }

}

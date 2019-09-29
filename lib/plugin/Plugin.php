<?php

namespace RunoFramework\Lib\Plugin;

class Plugin
{

    protected $optionKey;
    protected $pluginName;
    protected $pluginRootPath;

    public function __construct($pluginName, $optionKey, $pluginRootPath)
    {
        $this->optionKey = $optionKey . "_data";
        $this->pluginName = $pluginName;
        $this->pluginRootPath = $pluginRootPath;
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

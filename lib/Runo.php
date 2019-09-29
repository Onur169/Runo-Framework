<?php

namespace RunoFramework\Lib;

use Exception;

class Runo
{

    private $templatePath;
    private $optionKey;
    private $pluginName;
    private $pluginRootPath;

    public function __construct($pluginName, $optionKey, $pluginRootPath)
    {
        $this->optionKey = $optionKey . "_data";
        $this->pluginName = $pluginName;
        $this->pluginRootPath = $pluginRootPath;
    }

    public function registerData($data)
    {
        return add_option(self::getOptionKey(), $data);
    }

    public function saveData($data)
    {
        return update_option(self::getOptionKey(), $data);
    }

    public function getData($default = null)
    {
        return get_option(self::getOptionKey(), $default);
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

    public function registerGlobalJsVariables($variables)
    {

        $code = null;
        foreach ($variables as $variableName => $variableValue) {
            $code .= 'window.' . $variableName . ' = ' . $variableValue . ';' . PHP_EOL;
        }

        if ($code != null) {
            wp_register_script(self::getPluginNameUnderscore() . "_init_object", false);
            wp_enqueue_script(self::getPluginNameUnderscore() . "_init_object");
            wp_add_inline_script(self::getPluginNameUnderscore() . "_init_object", $code);
        }

    }

    public function enqueueExternalLibraries($libraries, $version = '1.0.0')
    {

        foreach ($libraries as $currentLibrary) {

            if (filter_var($currentLibrary, FILTER_VALIDATE_URL)) {

                $currentLibraryPathInfo = pathinfo($currentLibrary);
                $currentLibraryExtension = strtolower($currentLibraryPathInfo["extension"]);
                $currentLibraryFilename = $currentLibraryPathInfo["filename"];

                if ($currentLibraryExtension == "js") {

                    // Die Skripte nach Jquery erst laden
                    // wp_register_script in Verbindung mit ["jquery"] muss ich nutzen um das Laden nach Jquery zu gewährleisten
                    wp_register_script(
                        $currentLibraryFilename,
                        $currentLibrary,
                        ["jquery"],
                        $version
                    );

                    wp_enqueue_script($currentLibraryFilename);

                }

                if ($currentLibraryExtension == "css") {
                    wp_enqueue_style($currentLibraryFilename, $currentLibrary, [], $version, false);
                }

            }

        }

    }

    public function registerAppCSS($path)
    {

        wp_register_style(self::getPluginNameUnderscore(), plugins_url($path, $this->pluginRootPath));
        wp_enqueue_style(self::getPluginNameUnderscore());

    }

    public function registerAppJS($path)
    {

        wp_register_script(self::getPluginNameUnderscore(), plugins_url($path, $this->pluginRootPath));
        wp_enqueue_script(self::getPluginNameUnderscore());

    }

    public function getPluginsUrl($path)
    {
        return plugins_url($path, $this->pluginRootPath);
    }

    public function getOptionKey()
    {
        return $this->optionKey;
    }

    public function createFile($path, $content)
    {

        $fp = fopen($path, "w");
        if (!$fp) {
            throw new Exception('File open failed.');
        }

        $fwrite = @fwrite($fp, $content);
        if ($fwrite === false) {
            throw new Exception('File not writable.');
        }

        fclose($fp);

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

    public function isCurrentPageBackend()
    {
        return is_admin();
    }

    public function isCurrentPageFrontend()
    {
        return !is_admin();
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

    public function applyShortCodeToArray(&$array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            $item = do_shortcode($item);
        });
    }

    public function getArrayValueByKey(&$arrRef, $key, $defaultValue = null)
    {

        if (is_array($arrRef) && array_key_exists($key, $arrRef)) {
            return $arrRef[$key];
        } else if ($defaultValue != null) {
            return $defaultValue;
        } else {
            return null;
        }

    }

    // Bedingung: Array liegt vorsortiert vor
    // Beispiel [x,x,x,x,x,x,x,y,y,y,y,y,y,y,y,y,y]
    // Wir wollen aber [x,y,x,y,x,y,x,y,x,y,x,y,x,y,y,y]
    public function sortArraySuccessively($array, $firstArrayLength = null, $secondArrayLength = null, $firstArrayKeyValue = null)
    {

        $debug = false;

        $i = 0;
        $z = 0;

        if ($firstArrayLength == null || $secondArrayLength == null) {

            $firstArrayLength = 0;
            $secondArrayLength = 0;

            foreach($array as $item) {
                if($item[$firstArrayKeyValue[0]] == $firstArrayKeyValue[1]) {
                    $firstArrayLength++;
                } else {
                    $secondArrayLength++; 
                }
            }

        }

        $resultArray = [];
        $maxLength = count($array);

        for ($i; $i < $maxLength; $i++) {

            if ($i % 2 == 0) {

                $evenIndex = $i / 2;

                if ($debug) {
                    echo "$i = $evenIndex";
                }

                $resultArray[$i] = $array[$evenIndex];

            } else {

                $oddIndex = ($firstArrayLength - 1) + ($i - $z);

                if ($oddIndex > $i && $oddIndex >= $maxLength) {

                    $oddIndex = $i - $secondArrayLength + 2;

                    if ($oddIndex == $firstArrayLength) {
                        $oddIndex = $oddIndex - 1;
                    }

                }

                if ($debug) {
                    echo "$i = <strong>$oddIndex</strong>";
                }

                $resultArray[$i] = $array[$oddIndex];

                $z++;

            }

            if ($debug) {
                echo "<br><br>";
            }

        }

        return $resultArray;

    }

}

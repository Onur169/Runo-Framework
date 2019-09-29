<?php

namespace RunoFramework\Lib\Plugin;

class WordpressPlugin extends Plugin
{

    public function __construct($pluginName, $optionKey, $pluginRootPath)
    {
        parent::__construct($pluginName, $optionKey, $pluginRootPath);
    }

    public function registerData($data)
    {
        return add_option(parent::getOptionKey(), $data);
    }

    public function saveData($data)
    {
        return update_option(parent::getOptionKey(), $data);
    }

    public function getData($default = null)
    {
        return get_option(parent::getOptionKey(), $default);
    }

    public function registerGlobalJsVariables($variables)
    {

        $code = null;
        foreach ($variables as $variableName => $variableValue) {
            $code .= 'window.' . $variableName . ' = ' . $variableValue . ';' . PHP_EOL;
        }

        if ($code != null) {
            wp_register_script(parent::getPluginNameUnderscore() . "_init_object", false);
            wp_enqueue_script(parent::getPluginNameUnderscore() . "_init_object");
            wp_add_inline_script(parent::getPluginNameUnderscore() . "_init_object", $code);
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

        wp_register_style(parent::getPluginNameUnderscore(), plugins_url($path, $this->pluginRootPath));
        wp_enqueue_style(parent::getPluginNameUnderscore());

    }

    public function registerAppJS($path)
    {

        wp_register_script(parent::getPluginNameUnderscore(), plugins_url($path, $this->pluginRootPath));
        wp_enqueue_script(parent::getPluginNameUnderscore());

    }

    public function getPluginsUrl($path)
    {
        return plugins_url($path, $this->pluginRootPath);
    }

    public function isCurrentPageBackend()
    {
        return is_admin();
    }

    public function isCurrentPageFrontend()
    {
        return !is_admin();
    }

}

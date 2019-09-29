<?php

/**
 * @package Runo Framework
 * @version 1.0.0
 */
/*
Plugin Name: Runo Framework
Plugin URI: https://www.facebook.com/profile.php?id=100021747824725
Description: Ein Framework womit die Widget/Theme Programmierung leichter fällt
Version: 1.0.0
Author URI: https://www.facebook.com/profile.php?id=100021747824725
 */

spl_autoload_register(function($className) {

    $file = str_replace("RunoFramework", "", $className . '.php');
    $fileInfo = pathinfo($file);
    $fileInfo["dirname"] = strtolower($fileInfo["dirname"]);
    $newFilePath = __DIR__ . $fileInfo["dirname"] . "/" . $fileInfo["basename"];

    if (file_exists($newFilePath)) {
        require_once $newFilePath;
    }

});

use RunoFramework\Lib\Runo;
use RunoFramework\Lib\ArrayHelper\ArrayHelper;
use RunoFramework\Lib\ArrayHelper\WordpressArrayHelper;
use RunoFramework\Lib\File\File;
use RunoFramework\Lib\View\Template;

$pluginName = basename(__FILE__, ".php");
$page = $_GET["page"] ?? null;
$plugin = new Runo($pluginName, $pluginName, __FILE__);
$pluginTemplate = new Template(__DIR__ . "/template");

$plugin->registerData('[]');

// Admin Menü erstellen und Admin Seite laden
add_action('admin_menu', function () use ($plugin, $pluginTemplate) {

    add_menu_page($plugin->getPluginNameSeparated(), $plugin->getPluginNameSeparated(), 'manage_options', $plugin->getPluginName(), function () use ($plugin, $pluginTemplate) {

        try {

            $pluginTemplate->renderTemplate("main", [
                "PLUGIN_NAME" => $plugin->getPluginName(),
                "PLUGIN_NAME_SEPARATED" => $plugin->getPluginNameSeparated(),
                "GITHUB_REPO_URL" => 'https://github.com/Onur169/Runo-Framework',
                "GITHUB_LOGO_URL" => 'https://github.githubassets.com/images/modules/logos_page/GitHub-Logo.png',
                "CURRENT_VERSION" => @file_get_contents('https://raw.githubusercontent.com/Onur169/Runo-Framework/master/version.txt'),
            ]);

        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

    });

});

// Lade CSS/JS für das Backend
add_action('admin_init', function () use ($plugin, $wpdb) {

    $pageParam = $_GET["page"] ?? "";

    if ($pageParam == $plugin->getPluginName()) {

        $plugin->registerAppJS('js/dist/app.js');
        $plugin->registerAppCSS('css/dist/layout.css');

    }

});

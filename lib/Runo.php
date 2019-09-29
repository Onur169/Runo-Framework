<?php

namespace RunoFramework\Lib;

use RunoFramework\Lib\Plugin\Plugin;
use RunoFramework\Lib\Plugin\WordpressPlugin;

class Runo
{

    const REGISTER_AS_WORDPRESS_PLUGIN = "wordpress";
    const REGISTER_AS_NORMAL_PLUGIN = "normal";

    private $plugin;

    public function __construct($pluginName, $optionKey, $pluginRootPath, $pluginType = self::REGISTER_AS_WORDPRESS_PLUGIN)
    {

        if($pluginType == self::REGISTER_AS_WORDPRESS_PLUGIN) {
            $this->plugin = new WordpressPlugin($pluginName, $optionKey, $pluginRootPath);
        } else if($pluginType == self::REGISTER_AS_NORMAL_PLUGIN) {
            $this->plugin = new Plugin($pluginName, $optionKey, $pluginRootPath);
        } else {
            // Hier noch eine Exception schmeißen
        }

        return $this;

    }

    public function init() {
        return $this->plugin;
    }

}

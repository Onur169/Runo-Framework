<?php

namespace RunoFramework\Lib\ArrayHelper;

class WordpressArrayHelper extends ArrayHelper
{

    public function applyShortCodeToArray(&$array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            $item = do_shortcode($item);
        });
    }

}

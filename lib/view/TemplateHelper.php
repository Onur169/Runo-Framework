<?php

namespace RunoFramework\Lib\View;

class TemplateHelper
{

    public function printResponsiveImage($id)
    {
        echo '<img src="' . wp_get_attachment_image_url($id, 'small') . '"
		alt="' . get_post_meta($id, '_wp_attachment_image_alt', true) . '"
		srcset="' . wp_get_attachment_image_srcset($id, 'small') . '" />';
    }

    public function printHtml5Video($srcset, $addControls = self::HAS_VIDEO_CONTROLS)
    {

        $mp4Id = $srcset[0];
        $webmId = $srcset[1];
        $oggId = $srcset[2];

        $mp4Url = wp_get_attachment_url($mp4Id);
        $webmUrl = wp_get_attachment_url($webmId);
        $oggUrl = wp_get_attachment_url($oggId);

        $controls = $addControls ? ' controls' : '';

        echo '<video' . $controls . '>';
        if ($webmId > 0) {
            echo '<source src="' . $mp4Url . '" type="video/mp4">';
        }

        if ($webmId > 0) {
            echo '<source src="' . $webmUrl . '" type="video/webm">';
        }

        if ($oggId > 0) {
            echo '<source src="' . $oggUrl . '" type="video/ogg">';
        }
        echo 'Your browser does not support the video tag.';
        echo '</video>';

    }

    public function isNotEmpty($str)
    {
        if (is_string($str)) {
            return !empty(trim($str));
        } else {
            return !empty($str);
        }
    }

    public function isInlineLink($url)
    {
        return substr($url, 0, 1) === '#' && strlen($url) > 0;
    }

    public function getLink($str)
    {
        if (self::isInlineLink($str)) {
            return $str;
        } else if (filter_var($str, FILTER_VALIDATE_URL)) {
            return $str;
        } else if (strpos($str, 'post:') !== false) {
            $id = (int) str_replace("post: ", "", $str);
            if (is_int($id)) {
                return get_permalink($id);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function env($key = null)
    {
        $content = parse_ini_file(get_home_path() . "project.ini", true);
        if ($key === null) {
            return $content;
        } else {
            return isset($content[$key]) ? $content[$key] : null;
        }
    }

}
<?php

use Herbie\DI;
use Herbie\Hook;
use herbie\plugin\lipsum\classes\LoremIpsum;

class LipsumPlugin
{
    protected static $categories = ['abstract', 'animals', 'business', 'cats', 'city', 'food', 'nightlife', 'fashion', 'people', 'nature', 'sports', 'technics', 'transport'];

    public static function install()
    {
        $config = DI::get('Config');
        if ((bool)$config->get('plugins.config.lipsum.twig', false)) {
            Hook::attach('twigInitialized', ['LipsumPlugin', 'addTwigFunctions']);
        }
        if ((bool)$config->get('plugins.config.lipsum.shortcode', true)) {
            Hook::attach('shortcodeInitialized', ['LipsumPlugin', 'addShortcodes']);
        }
    }

    public static function addTwigFunctions($twig)
    {
        $twig->addFunction(
            new Twig_SimpleFunction('lipsum_image', ['LipsumPlugin', 'image'], ['is_safe' => ['html']])
        );
        $twig->addFunction(
            new Twig_SimpleFunction('lipsum_text', ['LipsumPlugin', 'text'], ['is_safe' => ['html']])
        );
        $twig->addFunction(
            new Twig_SimpleFunction('lipsum_title', ['LipsumPlugin', 'title'], ['is_safe' => ['html']])
        );
    }

    public static function addShortcodes($shortcode)
    {
        $shortcode->add('lipsum_image', ['LipsumPlugin', 'imageShortcode']);
        $shortcode->add('lipsum_title', ['LipsumPlugin', 'title']);
        $shortcode->add('lipsum_text', ['LipsumPlugin', 'text']);
    }

    /**
     * @param int $width
     * @param int $height
     * @param string $category
     * @param string $text
     */
    public static function image($width = 200, $height = 200, $category = '', $text = '')
    {
        $src = "http://lorempixel.com/{$width}/{$height}/";
        if (!empty($category) && in_array($category, static::$categories)) {
            $src .= "{$category}/";
        }
        if (!empty($text)) {
            $src .= "{$text}/";
        }
        return sprintf('<img src="%s" width="%d" height="%d" alt="%s">', $src, $width, $height, $text);
    }

    public static function title()
    {
        $helper = new LoremIpsum();
        $helper->shuffle();
        return $helper->display('sentences', 1, false);
    }

    public static function text()
    {
        $helper = new LoremIpsum();
        $helper->shuffle();
        return $helper->display('sentences', 10, false) . '.';
    }

    public static function imageShortcode($options)
    {
        $options = array_merge([
            'width' => 200,
            'height' => 200,
            'category' => '',
            'text' => '',
        ], (array)$options);
        return call_user_func_array(['LipsumPlugin', 'image'], $options);
    }

}

LipsumPlugin::install();

<?php

/**
 * This file is part of Herbie.
 *
 * (c) Thomas Breuss <www.tebe.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace herbie\plugin\lipsum;

use herbie\plugin\lipsum\classes\LoremIpsum;

use Herbie;
use Twig_SimpleFunction;

class LipsumPlugin extends Herbie\Plugin
{
    protected $categories = ['abstract', 'animals', 'business', 'cats', 'city', 'food', 'nightlife', 'fashion', 'people', 'nature', 'sports', 'technics', 'transport'];

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        $events = [];
        if ((bool)$this->config('plugins.config.lipsum.twig', false)) {
            $events[] = 'onTwigInitialized';
        }
        if ((bool)$this->config('plugins.config.lipsum.shortcode', true)) {
            $events[] = 'onShortcodeInitialized';
        }
        return $events;
    }

    public function onTwigInitialized($twig)
    {
        $twig->addFunction(
            new Twig_SimpleFunction('lipsum_image', [$this, 'image'], ['is_safe' => ['html']])
        );
        $twig->addFunction(
            new Twig_SimpleFunction('lipsum_text', [$this, 'text'], ['is_safe' => ['html']])
        );
        $twig->addFunction(
            new Twig_SimpleFunction('lipsum_title', [$this, 'title'], ['is_safe' => ['html']])
        );
    }

    public function onShortcodeInitialized($shortcode)
    {
        $shortcode->add('lipsum_image', [$this, 'imageShortcode']);
        $shortcode->add('lipsum_title', [$this, 'title']);
        $shortcode->add('lipsum_text', [$this, 'text']);
    }

    /**
     * @param int $width
     * @param int $height
     * @param string $category
     * @param string $text
     */
    public function image($width = 200, $height = 200, $category = '', $text = '')
    {
        $src = "http://lorempixel.com/{$width}/{$height}/";
        if (!empty($category) && in_array($category, $this->categories)) {
            $src .= "{$category}/";
        }
        if (!empty($text)) {
            $src .= "{$text}/";
        }
        return sprintf('<img src="%s" width="%d" height="%d" alt="%s">', $src, $width, $height, $text);
    }

    public function title()
    {
        $helper = new LoremIpsum();
        $helper->shuffle();
        return $helper->display('sentences', 1, false);
    }

    public function text()
    {
        $helper = new LoremIpsum();
        $helper->shuffle();
        return $helper->display('sentences', 10, false) . '.';
    }

    public function imageShortcode($options)
    {
        $options = $this->initOptions([
            'width' => 200,
            'height' => 200,
            'category' => '',
            'text' => '',
        ], $options);
        return call_user_func_array([$this, 'image'], $options);
    }

}

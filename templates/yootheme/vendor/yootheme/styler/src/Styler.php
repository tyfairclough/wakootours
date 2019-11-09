<?php

namespace YOOtheme\Theme;

use YOOtheme\ContainerTrait;

class Styler
{
    use ContainerTrait;

    /**
     * @var Collection
     */
    public $config;

    /**
     * @var array
     */
    public $themes;

    /**
     * @var array
     */
    public $inject = [
        'locator' => 'app.locator',
    ];

    /**
     * Constructor.
     *
     * @param Collection $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Gets theme styles.
     *
     * @return array
     */
    public function getThemes()
    {
        if (!isset($this->themes)) {

            $this->themes = [];

            foreach ($this->locator->findAll('@theme/less/theme.*.less') as $file) {
                $id = substr(basename($file, '.less'), 6);
                $this->themes[$id] = array_merge([
                    'id' => $id,
                    'file' => $file,
                    'name' => $this->namify($id),
                ], $this->getMeta($file));
            }
        }

        return $this->themes;
    }

    protected function getMeta($file)
    {
        $meta = [];
        $style = false;
        $handle = fopen($file, 'r');
        $content = str_replace("\r", "\n", fread($handle, 8192));
        fclose($handle);

        // parse first comment
        if (!preg_match('/^\s*\/\*(?:(?!\*\/).|\n)+\*\//', $content, $matches)) {
            return $meta;
        }

        // parse all metadata
        if (!preg_match_all('/^[ \t\/*#@]*(name|style|background|color|type|preview):(.*)$/mi', $matches[0], $matches)) {
            return $meta;
        }

        foreach ($matches[1] as $i => $key) {

            $key = strtolower(trim($key));
            $value = trim($matches[2][$i]);

            if (!in_array($key, ['name', 'style'])) {
                $value = array_map('ucwords', array_map('trim', explode(',', $value)));
            }

            if (!$style && $key != 'style') {
                $meta[$key] = $value;
            } elseif ($key == 'style') {
                $style = $value;
                $meta['styles'][$style] = ['name' => $this->namify($style)];
            } else {
                $meta['styles'][$style][$key] = $value;
            }
        }

        return $meta;
    }

    protected function namify($id)
    {
        return ucwords(str_replace('-', ' ', $id));
    }
}

<?php

namespace vendor\components;


class AssetManager
{
    private static $assets = [];

    private static $defaultPath = [];

    /**
     * Return asset with name @param $name.
     *
     * @param string $name unique asset name.
     * @return mixed
     * @throws \Exception
     */
    public static function getAsset($name)
    {
        if (!empty(self::$assets[$name])) {
            throw new \Exception('Asset with name: ' . $name . ' not found');
        }

        return self::$assets[$name];
    }

    /**
     * Add new asset to asset array.
     *
     * @param string $name unique name view.
     * @param array $assets array with assets files.
     */
    public function setAsset($name, $assets)
    {
        foreach ($assets as $type => $asset) {
            if (array_key_exists($type, self::$assets)) {
                if (array_key_exists($name, self::$assets[$type])) {
                    foreach ($asset as $item) {
                        if (!in_array($item, self::$assets[$type][$name])) {
                            array_push(self::$assets[$type][$name], $item);
                        }
                    }
                } else {
                    self::$assets[$type][$name] = $asset;
                }
            }
        }
    }

    /**
     * Include files that are contained in an asses array on view.
     *
     * Example @param $defaultPath : nameController::nameView.
     *
     * @param string $unique unique view name.
     * @param array $defaultPath this property change default path to css, js files.
     */
    public static function register($unique, $defaultPath = [])
    {
        $assetCss = array_merge(
            (!empty(self::$assets['css']['*'])) ? self::$assets['css']['*'] : [],
            (!empty(self::$assets['css'][$unique])) ? self::$assets['css'][$unique] : []);
        $assetJs = array_merge(
            (!empty(self::$assets['js']['*'])) ? self::$assets['js']['*'] : [],
            (!empty(self::$assets['js'][$unique])) ? self::$assets['js'][$unique] : []);

        $htmlCss = '<style>' . PHP_EOL;
        $htmlJs = '';


        foreach ($assetCss as $key => $css) {
            echo '<style>' . PHP_EOL;
            include (!empty($defaultPath['css'])) ? $defaultPath['css'] . $css : self::$defaultPath['css'] . $css;
//            $htmlCss .= '<link href="' . Alias::getAlias('@web') . $css . '" type="text/css" rel="stylesheet">' . PHP_EOL ;
            echo PHP_EOL . '</style>';
        }

        foreach ($assetJs as $key => $js) {
            echo '<script>';
            include (!empty($defaultPath['js'])) ? $defaultPath['js'] . $js : self::$defaultPath['js'] . $js;
//            $htmlJs .= '<script src="' . $defaultPath['js'] . $js . '">' . '</script>' . '<br>';
            echo PHP_EOL . '</script>';
        }
    }

    /**
     * Set path to folder with js, css files.
     *
     * For example @param array $defaultPath:
     *      [
     *          'css' => [
     *              'path/to/css/files'
     *          ],
     *          'js' => [
     *              'path/to/js/files'
     *          ]
     *      ]
     *
     * @param array $defaultPath path to css and js files.
     */
    public static function setDefaultPath($defaultPath)
    {
        self::$defaultPath = $defaultPath;
    }

    /**
     * Sets assets array equal with array in config file.
     */
    public static function setConfigAsset()
    {
        $configAsset = include(Alias::getAlias('@config') . 'app.php');
        self::$assets = $configAsset['assetManager'];
    }
}
<?php

namespace common\components\theme;

/**
 * Defines methods for getting theme info from file 'manifest.php'
 * Class DefaultManifestSource
 * @package common\components\theme
 */
class DefaultManifestSource implements IManifestSource {

    protected $data = NULL;

    public function setup($themeDirectory){
        if (file_exists($themeDirectory.'/manifest.php')){
            $this->data = include $themeDirectory.'/manifest.php';
            return TRUE;
        }
        return FALSE;
    }

    public function getName()
    {
        return $this->data['name'] ?: null;
    }

    public function getDescription()
    {
        return $this->data['description'] ?: null;
    }

    public function getLayoutType()
    {
        return $this->data['layoutType'] ?: null;
    }

    public function getPlan()
    {
        return $this->data['plan'] ?: null;
    }
}
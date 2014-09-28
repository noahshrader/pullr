<?php

namespace common\components\theme;

/**
 * Defines methods for getting theme info from file 'manifest.php'
 * Class DefaultManifestSource
 * @package common\components\theme
 */
class DefaultManifestSource implements IManifestSource
{
    protected $data = NULL;

    public function setup($themeDirectory)
    {
        if (file_exists($themeDirectory . '/manifest.php'))
        {
            $this->data = include $themeDirectory . '/manifest.php';
            return is_array($this->data);
        }
        return FALSE;
    }

    public function getName()
    {
        return isset($this->data['name']) ? $this->data['name'] : null;
    }

    public function getDescription()
    {
        return isset($this->data['description']) ? $this->data['description'] : null;
    }

    public function getLayoutType()
    {
        return isset($this->data['layoutType']) ? $this->data['layoutType'] : null;
    }

    public function getPlan()
    {
        return isset($this->data['plan']) ? $this->data['plan'] : null;
    }
}
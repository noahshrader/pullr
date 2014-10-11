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
        return !empty($this->data['name']) ? trim($this->data['name']) : null;
    }

    public function getDescription()
    {
        return !empty($this->data['description']) ? trim($this->data['description']) : null;
    }

    public function getLayoutType()
    {
        return !empty($this->data['layoutType']) ? trim($this->data['layoutType']) : null;
    }

    public function getPlan()
    {
        return !empty($this->data['plan']) ? trim($this->data['plan']) : null;
    }
}
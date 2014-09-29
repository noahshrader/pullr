<?php

namespace common\components\theme;

/**
 * Represents theme manifest which contains all needed info
 * Class ThemeManifest
 * @package common\components\theme
 */
class ThemeManifest
{
    protected $source = NULL;

    public function __construct(IManifestSource $source)
    {
        $this->setSource($source);
    }

    /**
     * @return IManifestSource
     */
    public function getSource(){
        return $this->source;
    }

    /**
     * Sets object which will handle loading manifest info
     * @param IManifestSource $source
     */
    public function setSource(IManifestSource $source)
    {
        $this->source = $source;
    }

    private function hasAllRequiredInfo()
    {
        return (
            !is_null($this->getSource()->getName()) &&
            !is_null($this->getSource()->getLayoutType()) &&
            !is_null($this->getSource()->getPlan())
        );
    }

    /**
     * Loads object with theme manifest data
     * @param $themeDir
     * @return array
     * @throws \InvalidArgumentException
     */
    public function read($themeDir)
    {
        if (in_array(basename($themeDir), ['.', '..']))
        {
            throw new \InvalidArgumentException('themeDir cannot be . or ..');
        }

        if ($this->getSource()->setup($themeDir) && $this->hasAllRequiredInfo())
        {
            return [
                'Filename' => basename($themeDir),
                'Name' => $this->getSource()->getName(),
                'Description' => $this->getSource()->getDescription(),
                'LayoutType' => $this->getSource()->getLayoutType(),
                'Plan' => $this->getSource()->getPlan(),
                'Status' => ''
            ];
        }

        return [];
    }
} 
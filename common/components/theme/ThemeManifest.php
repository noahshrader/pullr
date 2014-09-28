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

    public function setSource(IManifestSource $source)
    {
        $this->source = $source;
    }

    private function hasAllRequiredInfo()
    {
        return (
            !is_null($this->source->getName()) &&
            !is_null($this->source->getLayoutType()) &&
            !is_null($this->source->getPlan())
        );
    }

    public function read($themeDir)
    {
        if (in_array(basename($themeDir), ['.', '..']))
        {
            throw new \InvalidArgumentException('themeDir cannot be . or ..');
        }

        if ($this->source->setup($themeDir) && $this->hasAllRequiredInfo())
        {
            return [
                'Filename' => basename($themeDir),
                'Name' => $this->source->getName(),
                'Description' => $this->source->getDescription(),
                'LayoutType' => $this->source->getLayoutType(),
                'Plan' => $this->source->getPlan(),
                'Status' => ''
            ];
        }

        return NULL;
    }
} 
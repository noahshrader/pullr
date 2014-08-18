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

    public function __construct(IManifestSource $source){
        $this->setSource($source);
    }

    public function setSource(IManifestSource $source){
        $this->source = $source;
    }

    public function read($themeDir){
        if (in_array(basename($themeDir), ['.','..'])){
            throw new \InvalidArgumentException('themeDir cannot be . or ..');
        }

        if ($this->source->setup($themeDir)){
            return [
                'Filename' => basename($themeDir),
                'Name' => $this->source->getName(),
                'Description' => $this->source->getDescription(),
                'LayoutType' => $this->source->getLayoutType(),
                'Plan' => $this->source->getPlan(),
                'Status' => ''
            ];
        }
        else{
            return NULL;
        }

    }

} 
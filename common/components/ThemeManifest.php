<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 13.08.14
 * Time: 17:29
 */

namespace common\components;


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
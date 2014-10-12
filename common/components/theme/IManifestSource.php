<?php

namespace common\components\theme;

/**
 * Defines methods for getting theme info from whatever source
 * Interface IManifestSource
 * @package common\components\theme
 */
interface IManifestSource {
    function getName();
    function getDescription();
    function getLayoutType();
    function getPlan();
    function setup($themeDir);
}
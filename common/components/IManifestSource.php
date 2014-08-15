<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 13.08.14
 * Time: 17:38
 */

namespace common\components;


interface IManifestSource {
    function getName();
    function getDescription();
    function getLayoutType();
    function getPlan();
    function setup($themeDir);
}
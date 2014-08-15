<?php

namespace common\components;

use common\models\Theme;

class ThemeScanner extends \yii\base\Component
{
    const PATH_TO_THEMES = '@frontend/web/themes/';
    
//    public function readCss($path){
//        $content = file_get_contents($path);
//        $start = strpos($content, '/*')+2;
//        $end = strpos($content, '*/')-2;
//        if ($start<0 || $end<0){
//            return null;
//        }
//
//        $description = substr($content, $start, $end-$start+1);
//        $lines = explode("\n", $description);
//        $params = [];
//        foreach ($lines as $line){
//            $line = preg_replace('/#.*/', '', $line);
//            $els = explode(':', $line);
//            if (sizeof($els)!=2) {
//                continue;
//            }
//
//            $name = $els[0];
//            $value = $els[1];
//            $name = preg_replace('/[^a-z0-9]/i', '', $name);
//            $value = preg_replace('/[^a-z0-9.,\s]/i', '', $value);
//            $value = trim($value);
//            if ($name && $value){
//                $params[$name] = $value;
//            }
//        }
//
//        return $params;
//    }

    public function rescan(){
        $this->updateThemesDB($this->loadThemesParams());
    }

    /**
     * Iterates over themes folders and looks for manifest file containing info
     * @return array Array of params of each parsed theme
     */
    protected function loadThemesParams(){
        // defines how to get data from manifest file
        $manifest = new ThemeManifest(new DefaultManifestSource());

        // getting themes list and their info
        $themesParams = [];
        $pathToThemes = \Yii::getAlias(self::PATH_TO_THEMES);
        foreach(scandir($pathToThemes) as $folder){
            if ($folder != '.' && $folder != '..' && is_dir($pathToThemes.$folder)){
                $themesParams[] = $manifest->read($pathToThemes.$folder);
            }
        }

        return $themesParams;
    }

    /**
     * Updates database table with information about found themes
     * @param array $themesParams An array with themes params
     */
    protected function updateThemesDB(array $themesParams){
        //update newly created themes in db
        foreach($themesParams as $themeParams){
            if(!is_null($themeParams)){
                $theme = Theme::find()->where(['filename' => $themeParams['Filename']])->one();
                if (!$theme){
                    $theme = new Theme();
                }

                $theme->filename = $themeParams['Filename'];
                $theme->status = Theme::STATUS_ACTIVE;
                $theme->name = $themeParams['Name'];
                $theme->description = $themeParams['Description'] ?: null;
                $theme->layoutType = $themeParams['LayoutType']?:null;
                $theme->plan = $themeParams['Plan'] ?: null;
                $theme->save();
            }
        }

        //remove obsolete themes from db
        //$themes = Theme::find()->where(['not in', 'filename', $filenames])->andWhere(['status' => Theme::STATUS_ACTIVE])->all();
        //foreach ($themes as $theme){
        //    $theme->status = Theme::STATUS_REMOVED;
        //    $theme->save();
        //}
    }
    
    public function updateThemes(){
        $filenames = [];
//        foreach ($this->scannedThemes as $params){
//            $filenames[] = $params['filename'];
//            $theme = Theme::find()->where(['filename' => $params['filename']])->one();
//            if (!$theme){
//                $theme = new Theme();
//            }
//            $theme->filename = $params['filename'];
//            $theme->status = Theme::STATUS_ACTIVE;
//            $theme->name = $params['name'];
//            $theme->description = (isset($params['description']))?$params['description']:null;
//            $theme->layoutType = (isset($params['layout']))?$params['layout']:null;
//            $theme->plan = (isset($params['plan']))?$params['plan']:null;
//            $theme->save();
//        }
        $themes = Theme::find()->where(['not in', 'filename', $filenames])->andWhere(['status' => Theme::STATUS_ACTIVE])->all();
        foreach ($themes as $theme){
            $theme->status = Theme::STATUS_REMOVED;
            $theme->save();
        }
    }
}
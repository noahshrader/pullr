<?php

namespace common\components\theme;

use common\models\Theme;

class ThemeScanner extends \yii\base\Component
{
    const PATH_TO_THEMES = '@frontend/web/themes/';
    
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
        $themeFilenames = [];
        //update newly created themes in db
        foreach($themesParams as $themeParams){
            if(!is_null($themeParams)){
                $themeFilenames[] = $themeParams['Filename'];
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
                $theme->addedDate = time();
                $theme->save();
            }
        }

        //remove obsolete themes from db
        $themes = Theme::find()->where(['not in', 'filename', $themeFilenames])->andWhere(['status' => Theme::STATUS_ACTIVE])->all();
        foreach ($themes as $theme){
            $theme->status = Theme::STATUS_REMOVED;
            $theme->save();
        }
    }
}
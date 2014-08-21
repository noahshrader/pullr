<?php

namespace common\components\streamboard\alert;

use kartik\widgets\Alert;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\components\Application;
use yii\base\ErrorException;

/**
 * AlertMediaManager manages sounds/images for streamboard->alerts.
 * Both custom and predefined libraries.
 * @package common\components\streamboard\alert
 */
class AlertMediaManager extends Model
{
    private static $IMAGES_EXTENSIONS = ['jpg', 'png', 'gif'];
    private static $SOUNDS_EXTENSIONS = ['mp3'];
    const MAX_IMAGE_SIZE = 2000000;
    const MAX_IMAGE_SIZE_MESSAGE = 'File size should be less than 2 MB';
    const MAX_SOUND_SIZE = 2000000;
    const MAX_SOUND_SIZE_MESSAGE = self::MAX_IMAGE_SIZE_MESSAGE;
    /*all files' names will be trimmed to contain [MAX_FILE_NAME_LENGTH] at max*/
    const MAX_FILE_NAME_LENGTH = 30;
    /*maximum number of uploads per user per one media type (sounds or images)*/
    const MAX_FILES_NUMBER = 30;
    const MAX_FILES_NUMBER_MESSAGE = "File's number limit reached. Please remove one.";
    /*related to @web directory*/
    const PATH_TO_LIBRARY_IMAGES = 'streamboard/alerts/images/';
    const PATH_TO_LIBRARY_SOUNDS = 'streamboard/alerts/sounds/';

    const PATH_TO_CUSTOM_IMAGES = 'usermedia/streamboard/alerts/images/';
    const PATH_TO_CUSTOM_SOUNDS = 'usermedia/streamboard/alerts/sounds/';

    public function fields()
    {
        return ['customImages', 'customSounds', 'libraryImages', 'librarySounds'];
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = parent::toArray($fields, $expand, $recursive);
        $data['PATH_TO_LIBRARY_SOUNDS'] = self::PATH_TO_LIBRARY_SOUNDS;
        $data['PATH_TO_LIBRARY_IMAGES'] = self::PATH_TO_LIBRARY_IMAGES;
        $data['PATH_TO_CUSTOM_SOUNDS'] = self::PATH_TO_CUSTOM_SOUNDS;
        $data['PATH_TO_CUSTOM_IMAGES'] = self::PATH_TO_CUSTOM_IMAGES;

        return $data;
    }

    /**
     * @param string $PATH_TO_LIBRARY
     * @param array $extensions
     * @return array
     */
    private static function getFilesList($PATH_TO_LIBRARY, $extensions)
    {
        /*it looks like [*.{jpg, png, gif}]*/
        $extensionsMask = '*.{' . implode(',', $extensions) . '}';
        $mask = \Yii::getAlias('@frontend/web/' . $PATH_TO_LIBRARY . $extensionsMask);
        $filesWithPath = glob($mask, GLOB_BRACE);
        $files = [];
        foreach ($filesWithPath as $file) {
            $files[] = basename($file);
        }
        return $files;
    }

    public static function filterName($baseName)
    {
        $baseName = strip_tags($baseName);
        $baseName = trim($baseName);
        $baseName = preg_replace('~[\\\\/:*?"<>|]~', '', $baseName);
        $baseName = substr($baseName, 0, self::MAX_FILE_NAME_LENGTH);
        return $baseName;
    }

    public function getLibraryImages()
    {
        return self::getFilesList(self::PATH_TO_LIBRARY_IMAGES, self::$IMAGES_EXTENSIONS);
    }

    public function getLibrarySounds()
    {
        return self::getFilesList(self::PATH_TO_LIBRARY_SOUNDS, self::$SOUNDS_EXTENSIONS);
    }

    /**
     * add folder with user->id to path
     * @param $path
     * @return string
     */
    private static function addUserToPath($path)
    {
        $user = Application::getCurrentUser();
        return $path . $user->id . '/';
    }

    public function getCustomSounds()
    {
        $libraryPath = self::addUserToPath(self::PATH_TO_CUSTOM_SOUNDS);
        return self::getFilesList($libraryPath, self::$SOUNDS_EXTENSIONS);
    }

    public function getCustomImages()
    {
        $libraryPath = self::addUserToPath(self::PATH_TO_CUSTOM_IMAGES);
        return self::getFilesList($libraryPath, self::$IMAGES_EXTENSIONS);
    }

    /**
     * @return bool true if upload succeed, in either case it throws Exception
     */
    public static function uploadSound()
    {
        $params = [
            'extensions' => self::$SOUNDS_EXTENSIONS,
            'maxSize' => self::MAX_SOUND_SIZE,
            'maxSizeMessage' => self::MAX_SOUND_SIZE_MESSAGE,
            'pathToCustomLibrary' => self::PATH_TO_CUSTOM_SOUNDS,
            'getFilesListMethod' => [new AlertMediaManager(), 'getCustomSounds']
        ];
        return self::uploadMediaGeneral($params);
    }

    /**
     * @return bool true if upload succeed, in either case it throws Exception
     */
    public static function uploadImage()
    {
        $params = [
            'extensions' => self::$IMAGES_EXTENSIONS,
            'maxSize' => self::MAX_IMAGE_SIZE,
            'maxSizeMessage' => self::MAX_IMAGE_SIZE_MESSAGE,
            'pathToCustomLibrary' => self::PATH_TO_CUSTOM_IMAGES,
            'getFilesListMethod' => [new AlertMediaManager(), 'getCustomImages']
        ];
        return self::uploadMediaGeneral($params);
    }

    /*common code for uploadSounds / uploadMedia*/
    private static function uploadMediaGeneral($params)
    {
        $user = Application::getCurrentUser();
        $file = UploadedFile::getInstanceByName('file');
        if (!in_array($file->extension, $params['extensions'])) {
            throw new ErrorException('Wrong extension');
        }
        if ($file->size >= $params['maxSize']) {
            throw new ErrorException($params['maxSizeMessage']);
        }
        $baseName = self::filterName($file->baseName);
        $fileName = $baseName . '.' . $file->extension;
        $libraryPath = self::addUserToPath($params['pathToCustomLibrary']);
        $path = \Yii::getAlias('@frontend/web/' . $libraryPath . $fileName);
        $directory = dirname($path);
        FileHelper::createDirectory($directory, 0777);
        $files = call_user_func($params['getFilesListMethod']);
        if (sizeof($files) >= self::MAX_FILES_NUMBER){
            throw new ErrorException(self::MAX_FILES_NUMBER_MESSAGE);
        }
        $file->saveAs($path);
        chmod($path, 0777);
        return true;
    }
}
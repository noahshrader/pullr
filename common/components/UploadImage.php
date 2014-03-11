<?php

namespace common\components;

use yii\web\UploadedFile;
use common\models\base\BaseImage;

class UploadImage extends \yii\base\Component {

    public static function Upload($id, $type) {
        $files = UploadedFile::getInstancesByName('images');
        $errors = [];
        /**
         * in fact empty file can be passed (if form is submited) even if no images is added
         */
        if ($files && $files[0]->size != 0) {
            foreach ($files as $file) {
                if ($file->size == 0) {
                    $errors[] = 'Seems file size is bigger than allowed '. BaseImage::MAX_IMAGES_SIZE_TEXT . '.';
                    continue;
                }

                if ($file->size > BaseImage::MAX_IMAGE_SIZE) {
                    $errors[] = $file->name . ' has size more than ' . BaseImage::MAX_IMAGES_SIZE_TEXT . '.';
                    continue;
                }
                
                try {
                    if (!BaseImage::fromUploadedFile($id, $type, $file)) {
                        $errors[] = 'Cannot upload file ' . $file->name;
                    }
                } catch (\Exception $exception) {
                    $errors[] = $exception->getMessage();
                }
            }
        }
        return $errors;
    }

}

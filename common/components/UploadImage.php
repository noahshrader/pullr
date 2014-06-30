<?php

namespace common\components;

use common\models\Campaign;
use yii\web\UploadedFile;
use common\models\base\BaseImage;
use yii\db\ActiveRecord;

class UploadImage extends \yii\base\Component {

    /**
     * @return int number of successfully uploaded files
     */
    public static function Upload($id, $type, &$errors) {
        $files = UploadedFile::getInstancesByName('images');
        $errors = [];
        $successFiles = 0;
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
                    if (BaseImage::fromUploadedFile($id, $type, $file)){
                        $successFiles++;
                    } else {
                        $errors[] = 'Cannot upload file ' . $file->name;
                    }
                } catch (\Exception $exception) {
                    $errors[] = $exception->getMessage();
                }
            }
        }
        return $successFiles;
    }
    
    public static function getTypeByModel($model){
        if ($model instanceof \common\models\User){
            return BaseImage::TYPE_USER;
        }
        if ($model instanceof \common\models\Charity){
            return BaseImage::TYPE_CHARITY;
        }
    }

    public static function ApplyLogo($model){
        if ($model->photoId) {
            $id = $model->photoId;
            $model->photo = BaseImage::getOriginalUrlById($id);
            $model->smallPhoto = BaseImage::getMiddleUrlById($id);
        } else if (!$model->photo){
            $model->photo = BaseImage::NO_PHOTO_LINK();
            $model->smallPhoto = BaseImage::NO_PHOTO_LINK();
        }
    }
    
    public static function UploadLogo(ActiveRecord &$model){
        $type = self::getTypeByModel($model);
        $id = $model->id;
        if (UploadImage::Upload($id, $type, $errors)) {
                $params = ['subjectId' => $id, 'type' => $type, 'status' => BaseImage::STATUS_APPROVED];
                $image = BaseImage::find()->where($params)->orderBy('id DESC')->one();
                if ($image) {
                    $model->photoId = $image->id;
                    $model->save();
                    self::ApplyLogo($model);
                    
                    self::deactiveOldImages($params, $image->id);
                }
        } else if ($errors) {
            $model->addError('images', $errors[0]);
        }
    }
    
    public static function UploadCampaignBackground(Campaign $campaign){
        $file = UploadedFile::getInstanceByName('backgroundImage');
        $type = BaseImage::TYPE_CAMPAIGN_BACKGROUND;
        try {
            if (BaseImage::fromUploadedFile($campaign->id, $type, $file)){
                $params = ['subjectId' => $campaign->id, 'type' => $type, 'status' => BaseImage::STATUS_APPROVED];
                $image = BaseImage::find()->where($params)->orderBy('id DESC')->one();
                if ($image){
                    $campaign->backgroundImageId = $image->id;
                    $campaign->save();
                    self::deactiveOldImages($params, $image->id);
                }
                
            } else {
                $error = 'Cannot upload file ' . $file->name;
            }
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }
        
        if ($error){
            $campaign->addError('backgroundImage', $error);
        }
    }
    
    /**
     * 
     * @param type $params e.g. ['subjectId' => $campaign->id, 'type' => $type, 'status' => BaseImage::STATUS_APPROVED];
     * @param type $id - deactive all images less than that $id
     */
    public static function deactiveOldImages($params, $id){
        $oldImages = BaseImage::find()->where($params)->andWhere('id < ' . $image->id)->all();
        foreach ($oldImages as $oldImage) {
            $oldImage->status = BaseImage::STATUS_DELETED;
            $oldImage->save();
        }
    }
}

<?php

namespace common\models\base;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\web\ForbiddenHttpException;

class BaseImage extends ActiveRecord implements IBaseImage {
    const MAX_IMAGES_PER_OBJECT = 10;
    const STATUS_APPROVED = 'approved';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    /**
     * when error is generated during uploading image
     */
    const STATUS_ERROR = 'error';

    /**
     * list of all available statuses in Review model
     */
    public static $STATUSES = [self::STATUS_APPROVED, self::STATUS_PENDING, self::STATUS_DELETED, self::STATUS_ERROR];

    const TYPE_USER = 'user';

    /**
     * list of types (models) available to upload images
     */
    public static $TYPES = [self::TYPE_USER];

    /** MAX size is 5 MB */
    const MAX_IMAGE_SIZE = 5242880;
    const MAX_IMAGES_SIZE_TEXT = '5MB';

    public function scenarios() {
        return [
            'default' => ['status', 'userId', 'type', 'subjectId'],
            'status' => ['status']
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_APPROVED],
            ['status', 'in', 'range' => self::$STATUSES],
            ['type', 'in', 'range' => self::$TYPES]
        ];
    }

    public function getMiddleUrl() {
        return self::getMiddleUrlById($this->id);
    }
    
    public static function getMiddleUrlById($id){
        return self::getSubPathById($id) . '_middle.jpg';
    }
    
    public function getOriginalUrl() {
        return self::getOriginalUrlById($this->id);
    }
    
     public static function getOriginalUrlById($id){
        return self::getSubPathById($id) . '_original.jpg';
    }

    /**
     * we split images to groups of 1000 files to make easy coping / dumping
     * @param integer $id
     */
    public static function getSubPathById($id) {
        return 'userimages/' . round($id / 1000) . '/' . $id % 1000;
    }

    const MIDDLE_WIDTH = 300;
    const MIDDLE_HEIGHT = 200;

    /**
     * save uploaded file in original and middle sizes as jpg.
     */
    public static function fromUploadedFile($subjectId, $type, UploadedFile $file) {
        if (\Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }
        
        $imagesCount = BaseImage::find()->where(['subjectId' => $subjectId, 'type' => $type, 'status' => [self::STATUS_APPROVED, self::STATUS_PENDING] ])->count();
        
        if ($imagesCount >= self::MAX_IMAGES_PER_OBJECT){
            throw new ForbiddenHttpException('Maximum images number ('.self::MAX_IMAGES_PER_OBJECT.') per subject is exceeded.');
        }
        
        $flag = true;
        $baseImage = new BaseImage();
        $baseImage->type = $type;
        $baseImage->subjectId = $subjectId;
        $baseImage->userId = \Yii::$app->user->id;
        $baseImage->save();

        $basePath = \Yii::getAlias('@webroot') . '/' . self::getSubPathById($baseImage->id);
        $originalPath = $basePath . '_original.jpg';
        $middlePath = $basePath . '_middle.jpg';
        $image = \Yii::$app->image->load($file->tempName);

        $directory = dirname($originalPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $flag = $flag && $image->save($originalPath, 70);

        /** we have at least 150px width and 100px height */
        $image->resize(self::MIDDLE_WIDTH, self::MIDDLE_HEIGHT, \yii\image\drivers\Image::PRECISE);
        $width = $image->width;
        $height = $image->height;

        if ($width > self::MIDDLE_WIDTH) {
            $image->crop(self::MIDDLE_WIDTH, self::MIDDLE_HEIGHT, round(($width - self::MIDDLE_WIDTH) / 2));
        }
        if ($height > self::MIDDLE_HEIGHT) {
            $image->crop(self::MIDDLE_WIDTH, self::MIDDLE_HEIGHT, null, round(($height - self::MIDDLE_HEIGHT) / 2));
        }

        $flag = $flag && $image->save($middlePath, 70);

        if (!$flag) {
            $baseImage->status = self::STATUS_ERROR;
            $baseImage->save();
        }

        return $flag;
    }
    
    public function deleteImage(){
        $this->setScenario('status');
        $this->status = self::STATUS_DELETED;
        if (!$this->save()) {
            throw new \yii\base\ErrorException('Unsuccefull delete call! Please report this error');
        }
    }
}

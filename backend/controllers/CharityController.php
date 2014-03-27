<?php

namespace backend\controllers;

use common\models\base\BaseImage;
use common\components\UploadImage;
use common\models\Charity;
use yii\web\NotFoundHttpException;

class CharityController extends BackendController {

    public function actionIndex() {
        $status = isset($_POST['status']) ? $_POST['status'] : Charity::STATUS_ACTIVE;
        $params = [];
        $params['charities'] = Charity::find()->where(['status' => $status])->all();
        $params['status'] = $status;
        return $this->render('index', $params);
    }

    public function actionAdd(){
        return $this->actionEdit();
    }
    public function actionEdit($id = 0) {
        $charity = ($id == 0) ? new Charity() : Charity::find($id);
        
        if (!$charity) {
            throw new NotFoundHttpException("Charity with id = $id is not found");
        }
        
        if ($charity->load($_POST) && $charity->save()) {
            $errors = UploadImage::Upload($charity->id, BaseImage::TYPE_CHARITY);
            if ($errors) {
                $charity->addError('images', $errors[0]);
            } else{
                $params = ['subjectId' => $charity->id, 'type' => BaseImage::TYPE_CHARITY, 'status' => BaseImage::STATUS_APPROVED];
                $image = BaseImage::find()->where($params)->orderBy('id DESC')->one();
                if ($image){
                    $charity->photo = $image->id;
                    $charity->save();
                    $charity->refresh();

                    $oldImages = BaseImage::find()->where($params)->andWhere('id < '.$image->id)->all();
                    foreach ($oldImages as $oldImage){
                        $oldImage->status = BaseImage::STATUS_DELETED;
                        $oldImage->save();
                    }
                }
            }
            
            if ($id == 0) {
                /* so new city has been added */
                $this->redirect('charity/edit?id=' . $charity->id);
            }
        }

        return $this->render('edit', ['charity' => $charity]);
    }

}

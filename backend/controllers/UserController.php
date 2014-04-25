<?php
namespace backend\controllers;

use common\models\User;

class UserController extends BackendController
{
	public function actionIndex()
	{   
            $params = [];
            $params['users'] = User::find()->all();
            return $this->render('index', $params);
	}
        
        public function actionView(){
            $id = $_REQUEST['id'];
            
            $user = User::findOne($id);
            $user->setScenario('adminEdit');
            if ($user->load($_POST) && $user->save()){
                //**pasword change*/
                if ($_POST['User']['password']){
                    $user->setScenario('changePassword');
                    if ($user->load($_POST)){
                        $user->setNewPassword($user->password);
                        $user->save();
                    }
                }
                
            }
            $params['user'] = $user;
            return $this->render('view', $params);
        }
}

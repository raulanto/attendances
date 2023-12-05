<?php
namespace app\controllers;

use Yii;
use app\models\Teacher;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\RegistroFrom;
use yii\data\ActiveDataProvider;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\forms\LoginForm;
class TeacherController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
    
        unset($behaviors['authenticator']);
    
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin'                           => ['http://localhost:8100','http://localhost:8101'],
                'Access-Control-Request-Method'    => ['GET', 'POST', 'PUT', 'DELETE'],
                'Access-Control-Request-Headers'   => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age'           => 600
            ]
        ];
    
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
            'except' => ['index', 'view','login','registrar']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\Teacher';

    public $enableCsrfValidation = false;

    //login
    public function actionLogin() {
        $token = '';
        $model = new LoginForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if($model->login()) {
            $token = User::findOne(['username' => $model->username])->auth_key;
        }
        return $token;
    }
    //registrar
    public function actionRegistrar() { 
        $token = '';
        $model = new RegistroFrom();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $user = new User();
        $teacher = new Teacher();
        $user->username = $model->username;
        $user->password = $model->password;
        $user->status = User::STATUS_ACTIVE;
        $user->email_confirmed = 1;
        if($user->save()) {
            $user->username = $model->username;
            $teacher->tea_name = $model->tea_name;
            $teacher->tea_paternal = $model->tea_paternal;
            $teacher->tea_maternal = $model->tea_maternal;
            $teacher->tea_mail = $model->tea_mail;
            $teacher->tea_phone = $model->tea_phone;
            $teacher->tea_fkdegree = $model->tea_fkdegree;
            
            if($teacher->save()) {
                $token = $user->auth_key;
            }
        } else {
            return $user;
        }
        return $token;
    }
}
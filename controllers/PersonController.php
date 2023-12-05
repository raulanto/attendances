<?php
namespace app\controllers;//CONTROLADOR DE TABLA PERSON

use Yii;
use app\models\Person;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\RegistroPersonFrom;
use yii\data\ActiveDataProvider;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\forms\LoginForm;

class PersonController extends ActiveController
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
    public $modelClass = 'app\models\Person';

    public $enableCsrfValidation = false;
    public function actionLogin() {
        $token = '';
        $model = new LoginForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if($model->login()) {
            $token = User::findOne(['username' => $model->username])->auth_key;
        }
        return $token;
    }

    public function actionRegistrar() { 
        $token = '';
        $model = new RegistroPersonFrom();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $user = new User();
        $person = new Person();
        $user->username = $model->username;
        $user->password = $model->password;
        $user->status = User::STATUS_ACTIVE;
        $user->email_confirmed = 1;
        if($user->save()) {
            $user->username = $model->username;
            $person->per_name = $model->per_name;
            $person->per_paternal = $model->per_paternal;
            $person->per_maternal = $model->per_maternal;
            $person->per_mail = $model->per_mail;
            $person->per_phone = $model->per_phone;

            
            if($person->save()) {
                $token = $user->auth_key;
            }
        } else {
            return $user;
        }
        return $token;
    }

    private function generarCodigoUnico()
{
    $caracteresPermitidos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $longitudCodigo = 10;
    
    $codigoUnico = '';
    $caracteresPermitidosLength = strlen($caracteresPermitidos);

    for ($i = 0; $i < $longitudCodigo; $i++) {
        $codigoUnico .= $caracteresPermitidos[rand(0, $caracteresPermitidosLength - 1)];
    }

    return $codigoUnico;
}

}
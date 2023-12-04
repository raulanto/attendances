<?php
namespace app\controllers;//CONTROLADOR DE TABLA EXTRACURRICULAR_PERSON

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

class ExtraPersonController extends ActiveController
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
            'except' => ['index', 'view']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\ExtraPerson';

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
        $model = new RegistroFrom();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $user = new User();
        $alumno = new UserAlumno();
        $user->username = $model->username;
        $user->password = $model->password;
        $user->status = User::STATUS_ACTIVE;
        $user->email_confirmed = 1;
        if($user->save()) {
            $alumno->alu_matricula = $model->username;
            $alumno->alu_nombre = $model->alu_nombre;
            $alumno->alu_paterno = $model->alu_paterno;
            $alumno->alu_materno = $model->alu_materno;
            $alumno->alu_semestre = $model->alu_semestre;
            $alumno->alu_sexo = $model->alu_sexo;
            $alumno->alu_fkcarrera = 0;
            if($alumno->save()) {
                $token = $user->auth_key;
            }
        } else {
            return $user;
        }
        return $token;
    }
}
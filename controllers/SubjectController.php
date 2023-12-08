<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Subject;

class SubjectController extends ActiveController
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

    public function actionBuscar($text='') {
        $consulta = Subject::find()->where(['like', new \yii\db\Expression("CONCAT(sub_id, ' ', sub_name, ' ', sub_code)"), $text]);
    
        $subjects = new \yii\data\ActiveDataProvider([
            'query' => $consulta,
            'pagination' => [
                'pageSize' => 20 // Número de resultados por página
            ],
        ]);
    
        return $subjects->getModels();
    }
    
    public function actionTotal($text='') {
        $total = Subject::find();
        if($text != '') {
            $total = $total->where(['like', new \yii\db\Expression("CONCAT(sub_id, ' ', sub_name, ' ', sub_code)"), $text]);
        }
        $total = $total->count();
        return $total;
    }
    public $modelClass = 'app\models\Subject';

    public $enableCsrfValidation = false;
}
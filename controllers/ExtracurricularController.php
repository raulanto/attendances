<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Extracurricular; //MOD----------

class ExtracurricularController extends ActiveController
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
            'except' => ['index', 'view', 'buscar', 'buscar-todos'] //MOD----------
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\Extracurricular';   

    public $enableCsrfValidation = false; 
    
    //MOD----------
    public function actionBuscar($text='') {
        $consulta = Extracurricular::find()->where(['like', new \yii\db\Expression("CONCAT(ext_id, ' ', ext_name, ' ', ext_date, ' ', ext_place, ' ', ext_code)"), $text]);
    
        $extras = new \yii\data\ActiveDataProvider([
            'query' => $consulta,
            'pagination' => [
                'pageSize' => 20 // Número de resultados por página
            ],
        ]);
    
        return $extras->getModels();
    }

    public function actionTotal($text='') {
        $total = Extracurricular::find();
        if($text != '') {
            $total = $total->where(['like', new \yii\db\Expression("CONCAT(ext_id, ' ', ext_name, ' ', ext_code, ' ', ext_date, ' ', ext_place)"), $text]);
        }
        $total = $total->count();
        return $total;
    }

    public function actionBuscarTodos()
    {
        $todosLosRegistros = Extracurricular::find()->all();
    
        return $todosLosRegistros;
    }
}
//MOD----------
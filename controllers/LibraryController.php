<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\library;

class LibraryController extends ActiveController
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
            'except' => ['index', 'view' , 'librarys','buscar','total']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\Library';

    public $enableCsrfValidation = false;

    public function actionLibrarys($text = '', $id = null)
    {
        $libraries = Library::find()->joinWith(['libFkgroup']);
    
        
        if ($id !== null) {
            $libraries = $libraries->andWhere(['lib_fkgroup' => $id]);
        }
    
        if ($text !== '') {
            $libraries = $libraries
                ->andWhere(['like', new \yii\db\Expression(
                    "CONCAT(lib_id, ' ', lib_fkgroup, ' ', lib_type, ' ', lib_title)"), $text]);
        }
    
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $libraries,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    
        
        if (!empty($dataProvider->getModels())) {
            $result = [];
            foreach ($dataProvider->getModels() as $library) {
                $result[] = [
                    'lib_id'         => $library->lib_id,
                    'lib_fkgroup'   => $library->lib_fkgroup,
                    'lib_type'      => $library->lib_type,
                    'lib_title'     => $library->lib_title,
                ];
            }
            return $result;
        } else {
            return ['message' => 'No se encontraron bibliotecas para el grupo proporcionado'];
        }
    }
    
    public function actionBuscar($text = '', $id = null)
    {
        $libraries = Library::find()->joinWith(['libFkgroup']);

        if ($id !== null) {
            $libraries = $libraries->andWhere(['lib_fkgroup' => $id]);
        }
    
        if ($text !== '') {
            $libraries = $libraries
                ->andWhere(['like', new \yii\db\Expression(
                    "CONCAT(lib_id, ' ', lib_fkgroup, ' ', lib_type, ' ', lib_title)"), $text]);
        }
    
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $libraries,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    
        return $dataProvider->getModels();
    }
    
    public function actionTotal($text = '', $id = null)
    {
        $total = Library::find()->joinWith(['libFkgroup']);
    
        if ($id !== null) {
            $total = $total->andWhere(['lib_fkgroup' => $id]);
        }
    
        if ($text !== '') {
            $total = $total
                ->andWhere(['like', new \yii\db\Expression(
                    "CONCAT(lib_id, ' ', lib_fkgroup, ' ', lib_type, ' ', lib_title)"), $text]);
        }
    
        $total = $total->count();
        return $total;
    }
    

}
<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Classroom;
use Yii;

class ClassroomController extends ActiveController
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
            'except' => ['index', 'view', 'buscar', 'total', 'delete', 'crear', 'modificar']
        ];
    
        return $behaviors;
    }

    public function actionBuscar($text='')
    {
        $consulta = Classroom::find()->where(['like', new \yii\db\Expression("CONCAT(clas_id, ' ', clas_name, ' ', clas_description)"), $text]);
        $classroom = new \yii\data\ActiveDataProvider([
            'query' => $consulta,
            'pagination' => [
                'pageSize' => 20 // Número de resultados por página
            ],
        ]);
        return $classroom->getModels();
    }
    public function actionTotal($text='') {
        $total = Classroom::find();
        if($text != '') {
            $total = $total->where(['like', new \yii\db\Expression("CONCAT(clas_id, ' ', clas_name, ' ', clas_description)"), $text]);
        }
        $total = $total->count();
        return $total;
    }


    public $modelClass = 'app\models\Classroom';  
    
    public $enableCsrfValidation = false;    

    
    public function actionCrear()
    {
        $postData = json_decode(file_get_contents('php://input'), true);
    
        $model = new Classroom();
    
        if ($postData && $model->load($postData, '') && $model->save()) {
            return ['status' => 'success', 'message' => 'Registro creado exitosamente'];
        } else {
            return ['status' => 'error', 'message' => 'No se pudo crear el registro', 'errors' => $model->errors];
        }
    }

    public function actionModificar($id)
    {
        $model = Classroom::findOne($id);

        if (!$model) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'El registro no fue encontrado'];
        }

        $model->attributes = Yii::$app->request->getBodyParams();

        if ($model->save()) {
            return ['status' => 'success', 'message' => 'Registro actualizado exitosamente'];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'No se pudo actualizar el registro', 'errors' => $model->errors];
        }
    }

}
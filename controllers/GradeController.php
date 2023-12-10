<?php
namespace app\controllers;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Grade;

class GradeController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
    
        unset($behaviors['authenticator']);
    
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin'                           => ['http://localhost:8100', 'http://localhost:8101'],
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
            'except' => ['index', 'view', 'grades', 'buscar', 'total','guardar','editar','delete', 'modificar', 'crear']
        ];
    
        return $behaviors;
    }

    public $modelClass = 'app\models\Grade';

    public $enableCsrfValidation = false;

    public function actionGrades($text = '', $id = null)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Grade::find()->joinWith(['graFkgroup'])->andFilterWhere(['gra_fkgroup' => $id]),
            'pagination' => ['pageSize' => 20],
        ]);

        $result = [];

        foreach ($dataProvider->getModels() as $grade) {
            $result[] = [
                'gra_id'       => $grade->gra_id,
                'gra_type'     => $grade->gra_type,
                // 'gra_score'    => $grade->gra_score, MOOOOOOOOD
                'gra_date'     => $grade->gra_date,
                'gra_time'     => $grade->gra_time,
                // 'gra_commit'   => $grade->gra_commit, MOOOOOOOOD                
            ];
        }

        return !empty($result) ? $result : ['message' => 'No se encontraron calificaciones para el grupo proporcionado'];
    }

    public function actionBuscar($text = '', $id = null)
    {
        $grades = Grade::find()->where(['gra_fkgroup' => $id]);
    
        if ($id !== null) {
            $grades = $grades->andWhere(['gra_fkgroup' => $id]);
        }
    
        if ($text !== '') {
            $grades = $grades->andWhere(['or',
                ['like', 'gra_type', $text],
                // ['like', 'gra_score', $text], MOOOOOOOOOOOOOOD
                // ['like', 'gra_commit', $text], MOOOOOOOOOOOOOOOD
            ]);
        }
    
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $grades,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    
        $models = $dataProvider->getModels();
    
        $result = [];
    
        foreach ($models as $grade) {
            $result[] = [
                'gra_id'       => $grade->gra_id,
                'gra_type'     => $grade->gra_type,
                // 'gra_score'    => $grade->gra_score, MOOOOOOOOOOOOOD
                'gra_date'     => $grade->gra_date,
                'gra_time'     => $grade->gra_time,
                // 'gra_commit'   => $grade->gra_commit, MOOOOOOOOOOOOOD
            ];
        }
    
        return $result;
    }
    
    
    
    public function actionTotal($text = '', $id = null)
    {
        $total = Grade::find()->joinWith(['graFkgroup'])->andFilterWhere(['gra_fkgroup' => $id])->count();

        return $total;
    }

    public function actionGuardar()
{
    $model = new Grade();
    $model->load(Yii::$app->getRequest()->getBodyParams(), '');

    if ($model->save()) {
        return $model;
    } else {
        return [($model->errors)];
    }
}

    public function actionModificar($id)
    {
        $model =Grade::findOne($id);

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

    public function actionCrear()
    {
        $postData = json_decode(file_get_contents('php://input'), true);
    
        $model = new Grade();
    
        if ($postData && $model->load($postData, '') && $model->save()) {
            return ['status' => 'success', 'message' => 'Registro creado exitosamente', 'id' => $model->gra_id];
        } else {
            return ['status' => 'error', 'message' => 'No se pudo crear el registro', 'errors' => $model->errors];
        }
    }
}
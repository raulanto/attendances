<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

use app\models\GradePerson;

class GradePersonController extends ActiveController
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
            'except' => ['index', 'view', 'gradesp']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\GradePerson';
    
    public $enableCsrfValidation = false;

    public function actionGradesp($id)
{
    // Busca todas las listas que pertenecen al grupo
    $grades = GradePerson::find()->where(['graper_fkgrade' => $id])->all();

    // Verifica si se encontraron listas
    if (!empty($grades)) {
        $result = [];
        foreach ($grades as $datos=> $grade) {
            $result[] = [
                'graper_id' => $grade->graper_id,
                'graper_score' => $grade->graper_score,
                'graper_commit' => $grade->graper_commit,
                'graper_fkgrade' => $grade->graperFkgrade->gra_type,
                'graper_fkperson' => $grade->graperFkperson->completo,
                // Agrega otros campos si es necesario
            ];
        }
        return $result;
    } else {
        // Manejar la situación en la que no se encontraron listas
        return ['message' => 'No se encontraron calificaciones para la tarea asignada'];
    }
}
}
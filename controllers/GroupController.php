<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Group;
use app\models\Teacher;
use app\models\Subject;
class GroupController extends ActiveController
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
            'except' => ['index', 'view','grupos']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\Group';

    public $enableCsrfValidation = false;

    public function actionGrupos($id)
    {
        // Busca todos los códigos que pertenecen al grupo
        $grupos = Group::find()->where(['gro_fkteacher' => $id])->all();
    
        // Verifica si se encontraron códigos
        if (!empty($grupos)) {
            $result = [];
            foreach ($grupos as $grupo) {
                $result[] = [
                    'gro_id' =>$grupo->gro_id,
                    'gro_code' =>$grupo->gro_code ,
                    'gro_fksubject' =>$grupo->groFksubject,
                    'gro_fkteacher' => $grupo->groFkteacher,
                    'gro_fkclassroom' => $grupo->gro_fkclassroom,
                    'gro_date' =>$grupo->gro_date ,
                    'gro_time' => $grupo->gro_time,
                    // Puedes agregar otros campos si es necesario
                ];
            }
            return $result;
        } else {
            // Manejar la situación en la que no se encontraron códigos
            return ['message' => 'No se encontraron códigos para el grupo proporcionado'];
        }
    }

    public function actionBuscar($text='')
    {   //Se agrego el  join para las fk
        $consulta = Group::find()->joinWith(['groFksubject'])->where(['like', new \yii\db\Expression(
            "CONCAT(gro_id, ' ', gro_code, ' ', sub_name, ' ', gro_fkteacher, ' ', gro_fkclassroom)"), $text]);
        $groups = new \yii\data\ActiveDataProvider([
            'query' => $consulta,
            'pagination' => [
                'pageSize' => 20 // Número de resultados por página
            ],
        ]);
        return $groups->getModels();
    }
    public function actionTotal($text='') {
        $total = Group::find();
        if($text != '') {
            $total = $total->where(['like', new \yii\db\Expression("CONCAT(gro_id, ' ', gro_code, ' ', gro_fksubject, ' ', gro_fkteacher, ' ', gro_fkclassroom)"), $text]);
        }
        $total = $total->count();
        return $total;
    }




}
<?php
namespace app\controllers;
use Yii;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Code;
use app\models\RegistroCodeFrom;
class CodeController extends ActiveController
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
            'except' => ['index', 'view','codigos','generar']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\Code';
    
    public $enableCsrfValidation = false;

    public function actionCodigos($id)
    {
        // Obtén los códigos para el grupo proporcionado
        $codigos = Code::find()
            ->where(['cod_fkgroup' => $id])
            ->all();
    
        // Verifica si hay códigos
        if (!empty($codigos)) {
            $result = [];

            foreach ($codigos as $codigo) {

    
                $result[] = [
                    'cod_id' => $codigo->cod_id,
                    'cod_code' => $codigo->cod_code,
                    'cod_time' => $codigo->cod_time,
                    'cod_date' => $codigo->cod_date,
                    'cod_duration' => $codigo->cod_duration,
                    'cod_fkgroup' => $codigo->cod_fkgroup,
                    'total' => $totalAttendance= Yii::$app->runAction('attendance/total', ['att_fkcode' => $codigo->cod_id]), 
                ];
            }
    
            // Revierte el resultado y devuelve
            $result = array_reverse($result);
            return $result;
        } else {
            return ['message' => 'No se encontraron códigos para el grupo proporcionado'];
        }
    }
    
    

    public function actionGenerar($id = null)
    {
        if ($id !== null) {
            $model = Code::findOne($id);
            if ($model === null) {
                return ['error' => 'No se encontró el código con el ID proporcionado'];
            }
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            // Asigna la fecha y hora actuales
            $model->cod_date = date('Y-m-d');
            $model->cod_time = date('H:i:s');
            // Guarda el modelo
            if ($model->save()) {
                return $model; 
            } else {
                return ['error' => 'Error al actualizar el código. Detalles: ' . json_encode($model->errors)];
            }
        } else {
            $model = new Code();
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            do {
                $model->cod_code = $this->generarCodigoUnico();
                $model->cod_date = date('Y-m-d');
                $model->cod_time = date('H:i:s');
            } while ($this->codigoExistente($model->cod_code)); 
            if ($model->save()) {
                return $model; 
            } else {
                return ['error' => 'Error al generar el código. Detalles: ' . json_encode($model->errors)];
            }
        }
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


// Método auxiliar para verificar si un código ya existe en la base de datos
private function codigoExistente($codigo)
{
    return Code::find()->where(['cod_code' => $codigo])->exists();
}


      
}
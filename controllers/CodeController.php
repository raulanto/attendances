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
    //Codigos
    public function actionCodigos($id)
    {
        // Busca todos los códigos que pertenecen al grupo
        $codigos = Code::find()
            ->where(['cod_fkgroup' => $id])
            ->all();
    
        // Verifica si se encontraron códigos
        if (!empty($codigos)) {
            $result = [];
            foreach ($codigos as $codigo) {
                $result[] = [
                    'cod_id' => $codigo->cod_id,
                    'cod_code' => $codigo->cod_code,
                    'cod_time' => $codigo->cod_time,
                    'cod_date' => $codigo->cod_date,
                    'cod_duration' => $codigo->cod_duration,
                    'cod_fkgroup'=>$codigo->cod_fkgroup,
                    // Puedes agregar otros campos si es necesario
                ];
            }
            return $result;
        } else {
            // Manejar la situación en la que no se encontraron códigos
            return ['message' => 'No se encontraron códigos para el grupo proporcionado'];
        }
    }

    public function actionGenerar()
{
    $model = new Code();
            // Asigna los valores proporcionados
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
    do {

        // Genera el código (puedes ajustar la lógica según tus necesidades)
        $model->cod_code = $this->generarCodigoUnico();
        // Asigna la fecha y hora actual
        $model->cod_date = date('Y-m-d');
        $model->cod_time = date('H:i:s');
    } while ($this->codigoExistente($model->cod_code)); // Verifica si el código ya existe

    if ($model->save()) {
        return $model; // Devuelve el modelo creado si se guarda con éxito
    } else {
        return ['error' => 'Error al generar el código. Detalles: ' . json_encode($model->errors)];
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
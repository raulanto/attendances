<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Attendance;
use app\models\Code;
use app\models\RegistroAttendanceFrom;
class AttendanceController extends ActiveController
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
            'except' => ['index', 'view','asistencias','guardar']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\Attendance';
    public $enableCsrfValidation = false;

    //funcion personalizada 
    public function actionAsistencias($id)
    {
        // Buscar asistencias con el ID específico
        $asistencias = Attendance::find()->where(['att_fklist' => $id])->all();
    
        // Verificar si se encontraron asistencias
        if (!empty($asistencias)) {
            $result = [];
            foreach ($asistencias as $asistencia) {
                // Agregar los campos deseados al resultado
                $result[] = [
                    'att_id' => $asistencia->att_id,
                    'att_date' => $asistencia->att_date,
                    'att_time' => $asistencia->att_time,
                    'att_commit' => $asistencia->att_commit,
                    'att_fkcode' => $asistencia->attFkcode->code,
                    // Agregar otros campos si es necesario
                ];
            }
            return $result;
        } else {
            // Manejar la situación en la que no se encontraron asistencias
            return ['message' => 'No se encontraron asistencias para el ID proporcionado'];
        }
    }
    public function actionGuardar( $fkList, $commit)
    {
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $code = Code::findOne(['cod_code' => $model->$codigo]);

        $existingAttendance = Attendance::findOne(['att_fklist' => $model->$fkList, 'att_fkcode' => $code->cod_id]);

        if ($existingAttendance !== null) {
            return ['error' => 'Ya existe una asistencia registrada para el fk_list proporcionado y el código.'];
        }
        if ($code !== null) {

            $duracion = $code->cod_duration;
            $fechaGeneracion = strtotime($code->cod_date . ' ' . $code->cod_time);

            $tiempoActual = time();
            $tiempoPermitido = $fechaGeneracion + ($duracion * 60); // Convertir duración a segundos

            if ($tiempoActual <= $tiempoPermitido) {

                $asistencia = new Attendance();
                $asistencia->att_fkcode = $code->cod_id;
                $asistencia->att_fklist = $fkList;
                $asistencia->att_date = date('Y-m-d');
                $asistencia->att_time = date('H:i:s');
                $asistencia->att_commit = $model->$commit;

                if ($asistencia->save()) {
                    // Devolver la información requerida
                    return [
                        'id' => $code->cod_id,
                        'duracion' => $duracion,
                        'tiempoGeneracion' => $fechaGeneracion,
                        'fk_list' => $fkList,
                        'commit' => $model->$commit,
                    ];
                } else {
                    return ['error' => 'Error al guardar la asistencia. Detalles: ' . json_encode($asistencia->errors)];
                }
            } else {
                return ['error' => 'No se puede registrar la asistencia. Ha pasado el tiempo permitido.'];
            }
        } else {
            return ['error' => 'Código no encontrado en la tabla Code.'];
        }
    }

    
 
}
<?php
namespace app\controllers;
 
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\Listg;
use app\models\Person;

class ListgController extends ActiveController
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
            'except' => ['index', 'view', 'listas','buscar','total']
        ];
    
        return $behaviors;
    }
    public $modelClass = 'app\models\Listg';

    public $enableCsrfValidation = false;

    public function actionListas($text = '', $id = null)
{
    $listas = Listg::find()->joinWith(['listFkgroup', 'listFkperson']);

    // Filtra por el ID del grupo si se proporciona
    if ($id !== null) {
        $listas = $listas->andWhere(['list_fkgroup' => $id]);
    }

    if ($text !== '') {

        $listas = $listas
            ->andWhere(['like', new \yii\db\Expression(
                "CONCAT(list_id, ' ', list_fkgroup, ' ', list_fkperson, ' ', CONCAT(person.per_name, ' ', person.per_paternal, ' ', person.per_maternal))"), $text]);
    }

    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => $listas,
        'pagination' => [
            'pageSize' => 20 
        ],
    ]);
    // Verifica si se encontraron listas
    if (!empty($dataProvider->getModels())) {
        $result = [];
        foreach ($dataProvider->getModels() as $datos=> $lista) {
            $result[] = [
                'list_id' => $lista->list_id,
                'list_fkgroup' => $lista->list_fkgroup,
                'person' => $lista->listFkperson->completo,
            ];
        }
        return $result;
    } else {
        return ['message' => 'No se encontraron listas para el grupo proporcionado'];
    }
}

public function actionBuscar($text = '', $id = null)
{
    $listas = Listg::find()->joinWith(['listFkgroup', 'listFkperson']);

    // Filtra por el ID del grupo si se proporciona
    if ($id !== null) {
        $listas = $listas->andWhere(['list_fkgroup' => $id]);
    }

    if ($text !== '') {

        $listas = $listas
            ->andWhere(['like', new \yii\db\Expression(
                "CONCAT(list_id, ' ', list_fkgroup, ' ', list_fkperson, ' ', CONCAT(person.per_name, ' ', person.per_paternal, ' ', person.per_maternal))"), $text]);
    }

    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => $listas,
        'pagination' => [
            'pageSize' => 20 
        ],
    ]);

    return $dataProvider->getModels();
}


public function actionTotal($text = '', $id = null)
{
    $total = Listg::find()->joinWith(['listFkgroup', 'listFkperson']);
    if ($id !== null) {
        $total = $total->Where(['list_fkgroup' => $id]);
    }
    if ($text !== '') {
        $total = $total
            ->andWhere(['like', new \yii\db\Expression(
                "CONCAT(list_id, ' ', list_fkgroup, ' ', list_fkperson, ' ', group.gro_code, ' ', CONCAT(person.per_name, ' ', person.per_paternal, ' ', person.per_maternal))"), $text]);
    }
    $total = $total->count();
    return $total;
}


}
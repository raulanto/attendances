<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\question;

class QuestionController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['http://localhost:8100', 'http://localhost:8101'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 600
            ]
        ];

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
            'except' => ['index', 'view', 'questions', 'buscar', 'total', 'qmaestro', 'qperson']
        ];

        return $behaviors;
    }
    public $modelClass = 'app\models\Question';

    public $enableCsrfValidation = false;

    public function actionQuestions($text = '', $id = null)
    {
        $questions = Question::find()->joinWith(['queFktag', 'queFkperson', 'queFkteacher']);

        // Filter by the ID of the group if provided
        if ($id !== null) {
            $questions = $questions->andWhere(['que_fktag' => $id]);
        }

        if ($text !== '') {
            $questions = $questions
                ->andWhere(['like', new \yii\db\Expression(
                    "CONCAT(que_id, ' ', que_fktag, ' ', que_fkperson, ' ', que_fkteacher, ' ', CONCAT(person.per_name, ' ', person.per_paternal, ' ', person.per_maternal))"), $text]);
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $questions,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);


        if (!empty($dataProvider->getModels())) {
            $result = [];
            foreach ($dataProvider->getModels() as $question) {
                $result[] = [
                    'que_id' => $question->que_id,
                    'que_fktag' => $question->que_fktag,
                    'que_description' => $question->que_description,
                    'que_fkperson' => $question->que_fkperson,
                    'que_fkteacher' => $question->que_fkteacher,
                    'person' => $question->queFkperson->completo,
                    'teacher' => $question->queFkteacher->completo,
                ];
            }
            return $result;
        } else {
            return ['message' => 'No se encontraron preguntas para la etiqueta proporcionada'];
        }
    }

    public function actionBuscar($text = '', $id = null)
    {
        $questions = Question::find()->joinWith(['queFkteacher']);
    
        if ($text !== '') {
            $questions = $questions
                ->andWhere(['like', new \yii\db\Expression(
                    "CONCAT(que_title, ' ', que_description, ' ', que_fkteacher, ' ', que_create)"), $text]);
        }
    
        if ($id !== null) {
            $questions = $questions->andWhere(['que_fkperson' => $id]);
        }
    
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $questions,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    
        return $dataProvider->getModels();
    }
    
    public function actionTotal($text = '', $id = null)
    {
        $total = Question::find();
    
        if ($text !== '') {
            $total = $total
                ->andWhere(['like', new \yii\db\Expression(
                    "CONCAT(que_title, ' ', que_description, ' ', que_fkteacher, ' ', que_create)"), $text]);
        }
    
        if ($id !== null) {
            $total = $total->andWhere(['que_fkperson' => $id]);
        }
    
        $total = $total->count();
        return $total;
    }
    

    public function actionQmaestro($id = null)
    {
        // Busca todos los códigos que pertenecen al grupo
        $lista = Question::find()
            ->where(['que_fkteacher' => $id])
            ->all();

        // Verifica si se encontraron códigos
        if (!empty($lista)) {
            $result = [];
            foreach ($lista as $question) {
                $result[] = [
                    'que_id' => $question->que_id,
                    'tag' => $question->queFktag,
                    'que_description' => $question->que_description,
                    'que_fkperson' => $question->que_fkperson,
                    'que_fkteacher' => $question->que_fkteacher,
                    'teacher' => $question->queFkteacher->completo,
                ];
            }
            return $result;
        } else {
            // Manejar la situación en la que no se encontraron códigos
            return ['message' => 'No se encontraron preguntas para el maestro proporcionado proporcionado'];
        }
    }



    public function actionQperson($id = null)
    {
        // Busca todos los códigos que pertenecen al grupo
        $lista = Question::find()
            ->where(['que_fkperson' => $id])
            ->all();

        // Verifica si se encontraron códigos
        if (!empty($lista)) {
            $result = [];
            foreach ($lista as $question) {
                $result[] = [
                    'que_id' => $question->que_id,
                    'tag' => $question->queFktag,
                    'que_description' => $question->que_description,
                    'que_fkperson' => $question->que_fkperson,
                    'que_fkteacher' => $question->que_fkteacher,
                    'person' => $question->queFkperson->completo,

                ];
            }
            return $result;
        } else {
            // Manejar la situación en la que no se encontraron códigos
            return ['message' => 'No se encontraron preguntas para el maestro proporcionado proporcionado'];
        }
    }




}
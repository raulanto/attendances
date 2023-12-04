<?php

// identificar donde se encuentra el archivo 
namespace app\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property int $ans_id
 * @property string $ans_description
 * @property string $ans_create
 * @property int $ans_fkquestion
 *
 * @property Question $ansFkquestion
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ans_description', 'ans_create', 'ans_fkquestion'], 'required'],
            [['ans_description'], 'string'],
            [['ans_create'], 'safe'],
            [['ans_fkquestion'], 'integer'],
            [['ans_fkquestion'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['ans_fkquestion' => 'que_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ans_id' => 'ID',
            'ans_description' => 'Description',
            'ans_create' => 'Create',
            'ans_fkquestion' => 'Fkquestion',
        ];
    }

    /**
     * Gets query for [[AnsFkquestion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnsFkquestion()
    {
        //de uno a uno 
        return $this->hasOne(Question::class, ['que_id' => 'ans_fkquestion']);
    }

    public function extraFields(){
        return[
            'title' => function($item){
                return $item->ansFkquestion->que_title;
            }
        ];
    }
}

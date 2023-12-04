<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int $que_id
 * @property string $que_title
 * @property string $que_description
 * @property string $que_create
 * @property int $que_status
 * @property int $que_fktag
 * @property int $que_fkperson
 * @property int $que_fkteacher
 *
 * @property Answer[] $answers
 * @property Person $queFkperson
 * @property Tag $queFktag
 * @property Teacher $queFkteacher
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['que_title', 'que_description', 'que_create', 'que_status', 'que_fktag', 'que_fkperson', 'que_fkteacher'], 'required'],
            [['que_description'], 'string'],
            [['que_create'], 'safe'],
            [['que_status', 'que_fktag', 'que_fkperson', 'que_fkteacher'], 'integer'],
            [['que_title'], 'string', 'max' => 50],
            [['que_fkperson'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['que_fkperson' => 'per_id']],
            [['que_fktag'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['que_fktag' => 'tag_id']],
            [['que_fkteacher'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['que_fkteacher' => 'tea_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'que_id' => 'Que ID',
            'que_title' => 'Que Title',
            'que_description' => 'Que Description',
            'que_create' => 'Que Create',
            'que_status' => 'Que Status',
            'que_fktag' => 'Que Fktag',
            'que_fkperson' => 'Que Fkperson',
            'que_fkteacher' => 'Que Fkteacher',
        ];
    }

    /**
     * Gets query for [[Answers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['ans_fkquestion' => 'que_id']);
    }

    /**
     * Gets query for [[QueFkperson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueFkperson()
    {
        return $this->hasOne(Person::class, ['per_id' => 'que_fkperson']);
    }

    /**
     * Gets query for [[QueFktag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueFktag()
    {
        return $this->hasOne(Tag::class, ['tag_id' => 'que_fktag']);
    }

    /**
     * Gets query for [[QueFkteacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueFkteacher()
    {
        return $this->hasOne(Teacher::class, ['tea_id' => 'que_fkteacher']);
    }

    public function extraFields(){
        return[
            'person' => function($item){
                return $item->queFkperson->per_name;
            },
            'tag' => function($item){
                return $item->queFktag->tag_name;
            },
            'teacher' => function($item){
                return $item->queFkteacher->tea_name;
            }
        ];
    }
}

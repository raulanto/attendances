<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property int $tea_id Unique identifier of table teacher
 * @property string $tea_name Name of the teacher
 * @property string $tea_paternal First surname of the teacher
 * @property string $tea_maternal Second surname of the teacher
 * @property string $tea_mail Mail of the teacher
 * @property string $tea_phone Phone number of the teacher
 * @property int $tea_fkdegree Foreign key of the degree of the teacher
 *
 * @property Group[] $groups
 * @property Question[] $questions
 * @property Degree $teaFkdegree
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tea_name', 'tea_paternal', 'tea_maternal', 'tea_mail', 'tea_phone', 'tea_fkdegree'], 'required'],
            [['tea_fkdegree'], 'integer'],
            [['tea_name', 'tea_paternal', 'tea_maternal'], 'string', 'max' => 50],
            [['tea_mail'], 'string', 'max' => 100],
            [['tea_phone'], 'string', 'max' => 10],
            [['tea_fkdegree'], 'exist', 'skipOnError' => true, 'targetClass' => Degree::class, 'targetAttribute' => ['tea_fkdegree' => 'deg_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tea_id' => 'Unique identifier of table teacher',
            'tea_name' => 'Name of the teacher',
            'tea_paternal' => 'First surname of the teacher',
            'tea_maternal' => 'Second surname of the teacher',
            'tea_mail' => 'Mail of the teacher',
            'tea_phone' => 'Phone number of the teacher',
            'tea_fkdegree' => 'Foreign key of the degree of the teacher',
        ];
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['gro_fkteacher' => 'tea_id']);
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['que_fkteacher' => 'tea_id']);
    }

    /**
     * Gets query for [[TeaFkdegree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeaFkdegree()
    {
        return $this->hasOne(Degree::class, ['deg_id' => 'tea_fkdegree']);
    }

    public function extraFields(){
        return[
            'degree' => function($item){
                return $item->teaFkdegree->deg_name;
            }
        ];
    }
}

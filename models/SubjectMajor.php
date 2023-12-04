<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subject_major".
 *
 * @property int $submaj_id Unique identifier of table subject_major
 * @property int $submaj_fkmajor Foreign key of the major
 * @property int $submaj_fksubject Foreign key of the summer
 *
 * @property Major $submajFkmajor
 * @property Subject $submajFksubject
 */
class SubjectMajor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subject_major';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['submaj_fkmajor', 'submaj_fksubject'], 'required'],
            [['submaj_fkmajor', 'submaj_fksubject'], 'integer'],
            [['submaj_fkmajor'], 'exist', 'skipOnError' => true, 'targetClass' => Major::class, 'targetAttribute' => ['submaj_fkmajor' => 'maj_id']],
            [['submaj_fksubject'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['submaj_fksubject' => 'sub_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'submaj_id' => 'Unique identifier of table subject_major',
            'submaj_fkmajor' => 'Foreign key of the major',
            'submaj_fksubject' => 'Foreign key of the summer',
        ];
    }

    /**
     * Gets query for [[SubmajFkmajor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubmajFkmajor()
    {
        return $this->hasOne(Major::class, ['maj_id' => 'submaj_fkmajor']);
    }

    /**
     * Gets query for [[SubmajFksubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubmajFksubject()
    {
        return $this->hasOne(Subject::class, ['sub_id' => 'submaj_fksubject']);
    }

    public function extraFields(){
        return[
            'major' => function($item){
                return $item->submajFkmajor->maj_name;
            },
            'subject' => function($item){
                return $item->submajFksubject->sub_name;
            }
        ];
    }
}

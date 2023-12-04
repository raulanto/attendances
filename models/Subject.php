<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subject".
 *
 * @property int $sub_id Unique identifier of table subject
 * @property string $sub_name Name of the subject
 * @property string $sub_code Code of the subject
 *
 * @property Group[] $groups
 * @property SubjectMajor[] $subjectMajors
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_name', 'sub_code'], 'required'],
            [['sub_name'], 'string', 'max' => 50],
            [['sub_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sub_id' => 'Unique identifier of table subject',
            'sub_name' => 'Name of the subject',
            'sub_code' => 'Code of the subject',
        ];
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['gro_fksubject' => 'sub_id']);
    }

    /**
     * Gets query for [[SubjectMajors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectMajors()
    {
        return $this->hasMany(SubjectMajor::class, ['submaj_fksubject' => 'sub_id']);
    }
}

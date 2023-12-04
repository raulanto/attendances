<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "major".
 *
 * @property int $maj_id Unique identifier of table major
 * @property string $maj_name Name of the major
 * @property string $maj_code Code of the major
 *
 * @property SubjectMajor[] $subjectMajors
 */
class Major extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'major';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['maj_name', 'maj_code'], 'required'],
            [['maj_name'], 'string', 'max' => 100],
            [['maj_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'maj_id' => 'Unique identifier of table major',
            'maj_name' => 'Name of the major',
            'maj_code' => 'Code of the major',
        ];
    }

    /**
     * Gets query for [[SubjectMajors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectMajors()
    {
        return $this->hasMany(SubjectMajor::class, ['submaj_fkmajor' => 'maj_id']);
    }
}

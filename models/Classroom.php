<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "classroom".
 *
 * @property int $clas_id Unique identifier for table classroom
 * @property string $clas_name Name of the classroom
 * @property string $clas_description Description of the classroom location
 *
 * @property Group[] $groups
 */
class Classroom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classroom';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clas_name', 'clas_description'], 'required'],
            [['clas_description'], 'string'],
            [['clas_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'clas_id' => 'Unique identifier for table classroom',
            'clas_name' => 'Name of the classroom',
            'clas_description' => 'Description of the classroom location',
        ];
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['gro_fkclassroom' => 'clas_id']);
    }
}

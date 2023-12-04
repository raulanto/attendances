<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "degree".
 *
 * @property int $deg_id Unique identifier of table degree
 * @property string $deg_name Name of the degree
 *
 * @property Teacher[] $teachers
 */
class Degree extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'degree';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deg_name'], 'required'],
            [['deg_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'deg_id' => 'Unique identifier of table degree',
            'deg_name' => 'Name of the degree',
        ];
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teacher::class, ['tea_fkdegree' => 'deg_id']);
    }
}

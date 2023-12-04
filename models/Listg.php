<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "listg".
 *
 * @property int $list_id Unique identifier of table list
 * @property int $list_fkgroup Foreign key of the group
 * @property int $list_fkperson Foreign key of the people in the group
 *
 * @property Attendance[] $attendances
 * @property Group $listFkgroup
 * @property Person $listFkperson
 */
class Listg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listg';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['list_fkgroup', 'list_fkperson'], 'required'],
            [['list_fkgroup', 'list_fkperson'], 'integer'],
            [['list_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['list_fkgroup' => 'gro_id']],
            [['list_fkperson'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['list_fkperson' => 'per_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'list_id' => 'Unique identifier of table list',
            'list_fkgroup' => 'Foreign key of the group',
            'list_fkperson' => 'Foreign key of the people in the group',
        ];
    }

    /**
     * Gets query for [[Attendances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(Attendance::class, ['att_fklist' => 'list_id']);
    }

    /**
     * Gets query for [[ListFkgroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListFkgroup()
    {
        return $this->hasOne(Group::class, ['gro_id' => 'list_fkgroup']);
    }

    /**
     * Gets query for [[ListFkperson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListFkperson()
    {
        return $this->hasOne(Person::class, ['per_id' => 'list_fkperson']);
    }

    public function extraFields(){
        return[
            'person' => function ($item) {
                return $item->listFkperson->per_name . " " . $item->listFkperson->per_paternal . " " . $item->listFkperson->per_maternal;
            },
            'group' => function($item){
                return $item->listFkgroup->gro_code;
            }
        ];
    }
}

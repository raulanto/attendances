<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "code".
 *
 * @property int $cod_id Unique identifier for table code
 * @property string $cod_code Code
 * @property int $cod_fkgroup Foreign key of the group
 * @property string $cod_time Code start time
 * @property string $cod_date Code end time
 * @property int $cod_duration Code duration
 *
 * @property Attendance[] $attendances
 * @property Group $codFkgroup
 */
class Code extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cod_code', 'cod_fkgroup', 'cod_time', 'cod_date', 'cod_duration'], 'required'],
            [['cod_fkgroup', 'cod_duration'], 'integer'],
            [['cod_time', 'cod_date'], 'safe'],
            [['cod_code'], 'string', 'max' => 10],
            [['cod_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['cod_fkgroup' => 'gro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cod_id' => 'Unique identifier for table code',
            'cod_code' => 'Code',
            'cod_fkgroup' => 'Foreign key of the group',
            'cod_time' => 'Code start time',
            'cod_date' => 'Code end time',
            'cod_duration' => 'Code duration',
        ];
    }

    /**
     * Gets query for [[Attendances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(Attendance::class, ['att_fkcode' => 'cod_id']);
    }

    /**
     * Gets query for [[CodFkgroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCodFkgroup()
    {
        return $this->hasOne(Group::class, ['gro_id' => 'cod_fkgroup']);
    }

    public function extraFields(){
        return[
            'group' => function($item){
                return $item->codFkgroup->gro_code;
            }
        ];
    }

    public function getCode()
    {
        return $this->cod_code;
    }
}

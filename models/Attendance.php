<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attendance".
 *
 * @property int $att_id
 * @property string $att_date
 * @property string $att_time
 * @property string $att_commit
 * @property int $att_fklist
 * @property int $att_fkcode
 *
 * @property Code $attFkcode
 * @property Listg $attFklist
 */
class Attendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['att_date', 'att_time', 'att_commit', 'att_fklist', 'att_fkcode'], 'required'],
            [['att_date', 'att_time'], 'safe'],
            [['att_commit'], 'string'],
            [['att_fklist', 'att_fkcode'], 'integer'],
            [['att_fkcode'], 'exist', 'skipOnError' => true, 'targetClass' => Code::class, 'targetAttribute' => ['att_fkcode' => 'cod_id']],
            [['att_fklist'], 'exist', 'skipOnError' => true, 'targetClass' => Listg::class, 'targetAttribute' => ['att_fklist' => 'list_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'att_id' => 'Att ID',
            'att_date' => 'Att Date',
            'att_time' => 'Att Time',
            'att_commit' => 'Att Commit',
            'att_fklist' => 'Att Fklist',
            'att_fkcode' => 'Att Fkcode',
        ];
    }

    /**
     * Gets query for [[AttFkcode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttFkcode()
    {
        return $this->hasOne(Code::class, ['cod_id' => 'att_fkcode']);
    }

    /**
     * Gets query for [[AttFklist]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttFklist()
    {
        return $this->hasOne(Listg::class, ['list_id' => 'att_fklist']);
    }

    public function extraFields(){
        return[
            'code' => function($item){
                return $item->attFkcode->cod_code;
            }
            
        ];
    }
}

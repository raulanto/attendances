<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grade".
 *
 * @property int $gra_id
 * @property string $gra_type
 * @property string $gra_date
 * @property string $gra_time
 * @property int $gra_fkgroup
 *
 * @property Group $graFkgroup
 * @property GradePerson[] $gradePeople
 */
class Grade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gra_type', 'gra_date', 'gra_time', 'gra_fkgroup'], 'required'], //MOOOOD-------------------
            [['gra_date', 'gra_time'], 'safe'],
            [['gra_fkgroup'], 'integer'], //MOOOOD-------------------
            [['gra_type'], 'string', 'max' => 50],
            [['gra_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['gra_fkgroup' => 'gro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gra_id' => 'Gra ID',
            'gra_type' => 'Gra Type',
            'gra_date' => 'Gra Date',
            'gra_time' => 'Gra Time',
            'gra_fkgroup' => 'Gra Fkgroup',
        ];
    }

    /**
     * Gets query for [[GraFkgroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraFkgroup()
    {
        return $this->hasOne(Group::class, ['gro_id' => 'gra_fkgroup']);
    }

    /**
     * Gets query for [[GradePeople]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGradePeople() //MOOOOD-------------------
    {
        return $this->hasMany(GradePerson::class, ['graper_fkgrade' => 'gra_id']); //MOOOOD-------------------
    }

    public function extraFields(){
        return[
            'group' => function($item){
                return $item->graFkgroup->gro_code;
            }
        ];
    }
}